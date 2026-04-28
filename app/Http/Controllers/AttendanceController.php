<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{
    /**
     * Tampilkan daftar semua absensi.
     * Mendukung filter berdasarkan: pegawai, bulan, dan tahun.
     */
    public function index(Request $request)
    {
        // Ambil parameter filter dari URL query string
        $employeeId = $request->get("employee_id");
        $bulan = $request->get("bulan");
        $tahun = $request->get("tahun", date("Y")); // Default: tahun sekarang

        // Mulai query dengan eager loading relasi 'employee' dan 'user'
        $query = Attendance::with("employee.user")->latest("tanggal");

        // Terapkan filter pegawai jika dipilih
        if ($employeeId) {
            $query->where("employee_id", $employeeId);
        }

        // Terapkan filter bulan jika dipilih
        if ($bulan) {
            $query->whereMonth("tanggal", $bulan);
        }

        // Terapkan filter tahun (selalu aktif, default tahun ini)
        if ($tahun) {
            $query->whereYear("tanggal", $tahun);
        }

        // Ambil hasil query (sudah difilter)
        $attendances = $query->get();

        // Ambil semua pegawai untuk dropdown filter di view
        $employees = Employee::with("user")->orderBy("id")->get();

        return view(
            "attendances.index",
            compact("attendances", "employees", "employeeId", "bulan", "tahun"),
        );
    }

    /**
     * Tampilkan form untuk mencatat absensi baru.
     */
    public function create()
    {
        // Ambil semua pegawai untuk pilihan di form dropdown
        $employees = Employee::with("user")->orderBy("id")->get();

        return view("attendances.create", compact("employees"));
    }

    /**
     * Simpan data absensi baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate(
            [
                "employee_id" => "required|exists:employees,id",
                "tanggal" => "required|date",
                "status" => "required|in:Hadir,Izin,Alpa",
            ],
            [
                // Pesan error dalam Bahasa Indonesia
                "employee_id.required" => "Pegawai wajib dipilih.",
                "employee_id.exists" => "Pegawai yang dipilih tidak valid.",
                "tanggal.required" => "Tanggal absensi wajib diisi.",
                "tanggal.date" => "Format tanggal tidak valid.",
                "status.required" => "Status kehadiran wajib dipilih.",
                "status.in" => "Status hanya boleh: Hadir, Izin, atau Alpa.",
            ],
        );

        // Cek apakah absensi untuk pegawai di tanggal tersebut sudah ada
        // Satu pegawai hanya boleh memiliki satu record absensi per hari
        $alreadyExists = Attendance::where("employee_id", $request->employee_id)
            ->where("tanggal", $request->tanggal)
            ->exists();

        if ($alreadyExists) {
            // Kembalikan ke form dengan pesan error jika sudah ada
            return back()
                ->withInput()
                ->withErrors([
                    "tanggal" =>
                        "Absensi untuk pegawai ini pada tanggal tersebut sudah tercatat.",
                ]);
        }

        // Simpan data absensi baru ke tabel 'attendances'
        Attendance::create([
            "employee_id" => $request->employee_id,
            "tanggal" => $request->tanggal,
            "status" => $request->status,
        ]);

        return redirect()
            ->route("attendances.index")
            ->with("success", "Data absensi berhasil dicatat.");
    }

    /**
     * Tampilkan form edit data absensi.
     * Menggunakan Route Model Binding: Laravel otomatis mencari Attendance berdasarkan ID di URL.
     */
    public function edit(Attendance $attendance)
    {
        // Ambil semua pegawai untuk pilihan di form dropdown
        $employees = Employee::with("user")->orderBy("id")->get();

        // Muat relasi employee agar data pegawai bisa ditampilkan di form
        $attendance->load("employee.user");

        return view("attendances.edit", compact("attendance", "employees"));
    }

    /**
     * Perbarui data absensi di database.
     */
    public function update(Request $request, Attendance $attendance)
    {
        // Validasi input dari form
        $request->validate(
            [
                "employee_id" => "required|exists:employees,id",
                "tanggal" => "required|date",
                "status" => "required|in:Hadir,Izin,Alpa",
            ],
            [
                "employee_id.required" => "Pegawai wajib dipilih.",
                "employee_id.exists" => "Pegawai yang dipilih tidak valid.",
                "tanggal.required" => "Tanggal absensi wajib diisi.",
                "tanggal.date" => "Format tanggal tidak valid.",
                "status.required" => "Status kehadiran wajib dipilih.",
                "status.in" => "Status hanya boleh: Hadir, Izin, atau Alpa.",
            ],
        );

        // Cek duplikasi: pastikan tidak ada absensi lain (selain record ini sendiri)
        // untuk pegawai yang sama pada tanggal yang sama
        $alreadyExists = Attendance::where("employee_id", $request->employee_id)
            ->where("tanggal", $request->tanggal)
            // Kecualikan record yang sedang diedit (berdasarkan ID-nya)
            ->where("id", "!=", $attendance->id)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    "tanggal" =>
                        "Absensi untuk pegawai ini pada tanggal tersebut sudah tercatat.",
                ]);
        }

        // Perbarui data absensi di database
        $attendance->update([
            "employee_id" => $request->employee_id,
            "tanggal" => $request->tanggal,
            "status" => $request->status,
        ]);

        return redirect()
            ->route("attendances.index")
            ->with("success", "Data absensi berhasil diperbarui.");
    }

    /**
     * Hapus data absensi dari database.
     */
    public function destroy(Attendance $attendance)
    {
        // Hapus record absensi ini dari tabel 'attendances'
        $attendance->delete();

        return redirect()
            ->route("attendances.index")
            ->with("success", "Data absensi berhasil dihapus.");
    }

    /**
     * Pegawai mencatat kehadirannya sendiri (dari dashboard).
     * Method ini KHUSUS untuk user dengan role 'pegawai'.
     * Status yang diizinkan: hanya 'Hadir' dan 'Izin' (bukan 'Alpa').
     */
    public function storeSelf(Request $request)
    {
        // Validasi input status
        $request->validate(
            [
                "status" => "required|in:Hadir,Izin",
            ],
            [
                "status.required" => "Status kehadiran wajib dipilih.",
                "status.in" => "Status hanya boleh: Hadir atau Izin.",
            ],
        );

        // Ambil data employee milik user yang sedang login
        $employee = auth()->user()->employee;

        // Pastikan user ini punya data employee (sudah terdaftar sebagai pegawai)
        if (!$employee) {
            return redirect()
                ->route("dashboard")
                ->with(
                    "error",
                    "Data pegawai Anda tidak ditemukan. Hubungi admin.",
                );
        }

        // Cek apakah absensi hari ini sudah pernah dicatat
        $sudahAbsen = Attendance::where("employee_id", $employee->id)
            ->where("tanggal", today())
            ->exists();

        if ($sudahAbsen) {
            return redirect()
                ->route("dashboard")
                ->with("error", "Absensi hari ini sudah tercatat sebelumnya.");
        }

        // Simpan data absensi ke tabel 'attendances'
        Attendance::create([
            "employee_id" => $employee->id,
            "tanggal" => today(), // Tanggal hari ini otomatis
            "status" => $request->status,
        ]);

        return redirect()
            ->route("dashboard")
            ->with(
                "success",
                "Absensi hari ini berhasil dicatat: {$request->status}.",
            );
    }
}
