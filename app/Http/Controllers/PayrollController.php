<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PayrollController extends Controller
{
    /**
     * Tampilkan daftar slip gaji.
     * Mendukung filter berdasarkan: bulan dan tahun.
     */
    public function index(Request $request)
    {
        // Cek apakah user yang login adalah admin
        if (auth()->user()->isAdmin()) {
            // === ADMIN: tampilkan semua payroll dengan filter bulan & tahun ===

            // Ambil parameter filter dari URL query string
            $bulan = $request->get("bulan", date("m")); // Default: bulan sekarang
            $tahun = $request->get("tahun", date("Y")); // Default: tahun sekarang

            // Ambil semua payroll sesuai filter, beserta data pegawai & user-nya
            $payrolls = Payroll::with("employee.user")
                ->where("bulan", $bulan)
                ->where("tahun", $tahun)
                ->latest()
                ->paginate(15)
                ->withQueryString();

            return view(
                "payrolls.index",
                compact("payrolls", "bulan", "tahun"),
            );
        } else {
            // === PEGAWAI: hanya tampilkan payroll milik employee mereka sendiri ===

            // Ambil data employee yang terhubung ke user yang sedang login
            $employee = auth()->user()->employee;

            // Ambil payroll milik employee ini, diurutkan dari terbaru (tahun DESC, bulan DESC)
            $payrolls = Payroll::with("employee.user")
                ->where("employee_id", $employee->id)
                ->orderByDesc("tahun")
                ->orderByDesc("bulan")
                ->paginate(15)
                ->withQueryString();

            // Kirim ke view dengan flag isPegawai=true, bulan & tahun null (tidak ada filter)
            return view("payrolls.index", [
                "payrolls" => $payrolls,
                "isPegawai" => true,
                "bulan" => null,
                "tahun" => null,
            ]);
        }
    }

    /**
     * Endpoint JSON: kembalikan preview kalkulasi gaji untuk satu pegawai.
     * Dipanggil via JavaScript (fetch) saat admin memilih pegawai + periode di form generate.
     *
     * Query params: employee_id, bulan, tahun
     */
    public function preview(Request $request): JsonResponse
    {
        // Validasi parameter yang dikirim dari JavaScript
        $request->validate([
            "employee_id" => "required|exists:employees,id",
            "bulan" => "required|string|size:2",
            "tahun" => "required|string|size:4",
        ]);

        // Ambil data pegawai beserta relasi user (untuk nama)
        $employee = Employee::with("user")->findOrFail($request->employee_id);

        // Hitung jumlah hari Alpa pada bulan & tahun yang dipilih
        $jumlahAlpa = $employee->countAlpa($request->bulan, $request->tahun);

        // Hitung estimasi potongan: setiap 1 hari Alpa = Rp 100.000
        $estimasiPotongan = $jumlahAlpa * 100000;

        // Estimasi gaji bersih belum termasuk tunjangan (tunjangan diisi belakangan)
        $estimasiGajiBersih = $employee->gaji_pokok - $estimasiPotongan;

        // Kembalikan data dalam format JSON
        return response()->json([
            "nama" => $employee->user->name,
            "nik" => $employee->nik,
            "jabatan" => $employee->jabatan,
            "gaji_pokok" => $employee->gaji_pokok,
            "gaji_pokok_fmt" =>
                "Rp " . number_format($employee->gaji_pokok, 0, ",", "."),
            "jumlah_alpa" => $jumlahAlpa,
            "estimasi_potongan" => $estimasiPotongan,
            "estimasi_potongan_fmt" =>
                "Rp " . number_format($estimasiPotongan, 0, ",", "."),
            // Catatan: estimasi_gaji_bersih ini BELUM termasuk tunjangan
            "estimasi_gaji_bersih" => $estimasiGajiBersih,
            "estimasi_gaji_bersih_fmt" =>
                "Rp " . number_format($estimasiGajiBersih, 0, ",", "."),
        ]);
    }

    /**
     * Tampilkan form untuk generate payroll (pilih bulan, tahun, dan pegawai).
     */
    public function create()
    {
        // Ambil semua pegawai untuk pilihan di form
        $employees = Employee::with("user")->orderBy("id")->get();

        return view("payrolls.create", compact("employees"));
    }

    /**
     * Proses generate payroll berdasarkan input form.
     * Mendukung generate untuk SATU pegawai atau SEMUA pegawai sekaligus.
     *
     * Logika kalkulasi (sesuai context.md):
     *   - total_potongan = jumlah_alpa * Rp 100.000
     *   - gaji_bersih    = gaji_pokok + total_tunjangan - total_potongan
     *   - Idempotency: jika sudah ada, UPDATE; jika belum, INSERT
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate(
            [
                "bulan" => "required|string|size:2",
                "tahun" => "required|string|size:4",
                "employee_id" => "nullable|exists:employees,id",
                "total_tunjangan" => "nullable|integer|min:0",
            ],
            [
                // Pesan error dalam Bahasa Indonesia
                "bulan.required" => "Bulan wajib dipilih.",
                "bulan.size" =>
                    "Format bulan tidak valid (harus 2 digit, misal: 04).",
                "tahun.required" => "Tahun wajib diisi.",
                "tahun.size" =>
                    "Format tahun tidak valid (harus 4 digit, misal: 2026).",
                "employee_id.exists" => "Pegawai yang dipilih tidak valid.",
                "total_tunjangan.min" => "Total tunjangan tidak boleh negatif.",
            ],
        );

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        // Tunjangan default 0 jika tidak diisi
        $total_tunjangan = (int) $request->get("total_tunjangan", 0);

        // Tentukan daftar pegawai yang akan digenerate payroll-nya
        if ($request->filled("employee_id")) {
            // Hanya satu pegawai yang dipilih
            $employees = Employee::where("id", $request->employee_id)->get();
        } else {
            // Semua pegawai (generate massal)
            $employees = Employee::all();
        }

        // Jika tidak ada pegawai sama sekali, kembalikan dengan pesan error
        if ($employees->isEmpty()) {
            return back()->withErrors([
                "employee_id" => "Tidak ada data pegawai yang bisa digenerate.",
            ]);
        }

        // Loop setiap pegawai dan panggil method generatePayroll() dari Model
        foreach ($employees as $employee) {
            // Semua logika kalkulasi ada di dalam Employee::generatePayroll()
            // (Fat Models, Skinny Controllers — sesuai context.md)
            $employee->generatePayroll($bulan, $tahun, $total_tunjangan);
        }

        // Hitung jumlah pegawai yang berhasil digenerate untuk pesan sukses
        $jumlah = $employees->count();

        return redirect()
            ->route("payrolls.index", ["bulan" => $bulan, "tahun" => $tahun])
            ->with(
                "success",
                "Payroll untuk {$jumlah} pegawai bulan " .
                    \App\Models\Payroll::getNamaBulan($bulan) .
                    " {$tahun} berhasil digenerate.",
            );
    }

    /**
     * Tampilkan detail slip gaji satu pegawai.
     * Menggunakan Route Model Binding: Laravel otomatis mencari Payroll berdasarkan ID di URL.
     */
    public function show(Payroll $payroll)
    {
        // Muat relasi employee dan user untuk ditampilkan di slip gaji
        $payroll->load("employee.user");

        // === CEK AKSES: pegawai hanya boleh melihat slip gaji miliknya sendiri ===
        if (!auth()->user()->isAdmin()) {
            // Ambil data employee milik user yang sedang login
            $employee = auth()->user()->employee;

            // Jika payroll ini bukan milik employee mereka, tolak akses
            if ($payroll->employee_id !== $employee->id) {
                abort(403, "Anda tidak memiliki akses ke slip gaji ini.");
            }
        }
        // Jika admin, boleh lihat semua slip gaji tanpa pembatasan

        return view("payrolls.show", compact("payroll"));
    }

    /**
     * Hapus data slip gaji dari database.
     */
    public function destroy(Payroll $payroll)
    {
        // Simpan info bulan & tahun sebelum dihapus, untuk redirect kembali ke filter yang sama
        $bulan = $payroll->bulan;
        $tahun = $payroll->tahun;

        // Hapus record payroll ini dari tabel 'payrolls'
        $payroll->delete();

        return redirect()
            ->route("payrolls.index", ["bulan" => $bulan, "tahun" => $tahun])
            ->with("success", "Data slip gaji berhasil dihapus.");
    }
}
