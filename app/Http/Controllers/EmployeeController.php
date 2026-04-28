<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Tampilkan daftar semua pegawai.
     * Menggunakan eager loading 'user' agar tidak terjadi N+1 query.
     */
    public function index()
    {
        // Ambil semua data pegawai beserta data user-nya, urutkan dari terbaru, dan batasi 10 per halaman
        $employees = Employee::with("user")->latest()->paginate(10);

        return view("employees.index", compact("employees"));
    }

    /**
     * Tampilkan form untuk membuat pegawai baru.
     */
    public function create()
    {
        return view("employees.create");
    }

    /**
     * Simpan data pegawai baru ke database.
     * Proses ini membuat 2 record sekaligus: User + Employee (dalam 1 transaksi).
     */
    public function store(Request $request)
    {
        // Validasi semua input dari form
        $request->validate(
            [
                "name" => "required|string|max:255",
                "email" => "required|email|unique:users,email",
                "password" => "required|string|min:8|confirmed",
                "nik" => "required|string|unique:employees,nik",
                "jabatan" => "required|string|max:255",
                "gaji_pokok" => "required|integer|min:0",
            ],
            [
                // Pesan error dalam Bahasa Indonesia
                "name.required" => "Nama lengkap wajib diisi.",
                "email.required" => "Alamat email wajib diisi.",
                "email.unique" => "Email ini sudah terdaftar.",
                "password.min" => "Password minimal 8 karakter.",
                "password.confirmed" => "Konfirmasi password tidak cocok.",
                "nik.required" => "NIK wajib diisi.",
                "nik.unique" => "NIK ini sudah terdaftar.",
                "jabatan.required" => "Jabatan wajib diisi.",
                "gaji_pokok.required" => "Gaji pokok wajib diisi.",
                "gaji_pokok.min" => "Gaji pokok tidak boleh negatif.",
            ],
        );

        // Gunakan DB Transaction agar jika salah satu gagal, keduanya dibatalkan
        DB::transaction(function () use ($request) {
            // Langkah 1: Buat akun User dengan role 'pegawai'
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role" => "pegawai",
            ]);

            // Langkah 2: Buat data Employee yang terhubung ke User tersebut
            Employee::create([
                "user_id" => $user->id,
                "nik" => $request->nik,
                "jabatan" => $request->jabatan,
                "gaji_pokok" => $request->gaji_pokok,
            ]);
        });

        return redirect()
            ->route("employees.index")
            ->with("success", "Data pegawai berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail satu pegawai.
     * Menggunakan eager loading untuk relasi yang dibutuhkan.
     */
    public function show(Employee $employee)
    {
        // Muat relasi user, attendances (terbaru), dan payrolls untuk halaman detail
        $employee->load([
            "user",
            "attendances" => function ($q) {
                $q->latest("tanggal")->take(10);
            },
            "payrolls" => function ($q) {
                $q->latest();
            },
        ]);

        return view("employees.show", compact("employee"));
    }

    /**
     * Tampilkan form edit data pegawai.
     */
    public function edit(Employee $employee)
    {
        // Muat relasi user agar data nama & email bisa ditampilkan di form
        $employee->load("user");

        return view("employees.edit", compact("employee"));
    }

    /**
     * Perbarui data pegawai di database.
     * Update dilakukan pada tabel 'users' (nama, email) DAN 'employees' (nik, jabatan, gaji).
     */
    public function update(Request $request, Employee $employee)
    {
        // Validasi input; 'unique' dikecualikan untuk record milik employee ini sendiri
        $request->validate(
            [
                "name" => "required|string|max:255",
                "email" =>
                    "required|email|unique:users,email," . $employee->user_id,
                "nik" =>
                    "required|string|unique:employees,nik," . $employee->id,
                "jabatan" => "required|string|max:255",
                "gaji_pokok" => "required|integer|min:0",
            ],
            [
                "name.required" => "Nama lengkap wajib diisi.",
                "email.required" => "Alamat email wajib diisi.",
                "email.unique" => "Email ini sudah digunakan oleh akun lain.",
                "nik.required" => "NIK wajib diisi.",
                "nik.unique" => "NIK ini sudah digunakan oleh pegawai lain.",
                "jabatan.required" => "Jabatan wajib diisi.",
                "gaji_pokok.required" => "Gaji pokok wajib diisi.",
                "gaji_pokok.min" => "Gaji pokok tidak boleh negatif.",
            ],
        );

        // Gunakan DB Transaction agar update user & employee konsisten
        DB::transaction(function () use ($request, $employee) {
            // Langkah 1: Update data di tabel 'users' (nama & email)
            $employee->user->update([
                "name" => $request->name,
                "email" => $request->email,
            ]);

            // Langkah 2: Update data di tabel 'employees' (nik, jabatan, gaji_pokok)
            $employee->update([
                "nik" => $request->nik,
                "jabatan" => $request->jabatan,
                "gaji_pokok" => $request->gaji_pokok,
            ]);
        });

        return redirect()
            ->route("employees.index")
            ->with("success", "Data pegawai berhasil diperbarui.");
    }

    /**
     * Hapus data pegawai dari database.
     * Karena ada 'onDelete cascade' di migration, data attendance & payroll
     * milik pegawai ini akan ikut terhapus secara otomatis.
     */
    public function destroy(Employee $employee)
    {
        // Simpan referensi ke user sebelum dihapus
        $user = $employee->user;

        // Hapus akun user yang terkait.
        // Karena ada 'onDelete cascade' di database, data "employee", "attendance",
        // dan "payroll" milik akun ini akan otomatis terikut terhapus dengan bersih.
        $user->delete();

        return redirect()
            ->route("employees.index")
            ->with("success", "Data pegawai berhasil dihapus.");
    }
}
