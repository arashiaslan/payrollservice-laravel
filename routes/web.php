<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Support\Facades\Route;

// Halaman utama: redirect ke dashboard
Route::get("/", function () {
    return redirect()->route("dashboard");
});

// ============================================================
// ROUTE YANG MEMBUTUHKAN LOGIN (middleware: auth)
// ============================================================
Route::middleware(["auth"])->group(function () {
    // ----------------------------------------------------------
    // DASHBOARD — logika berbeda untuk admin dan pegawai
    // ----------------------------------------------------------
    Route::get("/dashboard", function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // ---- Data statistik untuk tampilan dashboard admin ----

            // Hitung total pegawai yang terdaftar
            $totalPegawai = Employee::count();

            // Hitung absensi hari ini (semua status)
            $absensiHariIni = Attendance::where("tanggal", today())->count();

            // Hitung jumlah 'Hadir' hari ini
            $hadirHariIni = Attendance::where("tanggal", today())
                ->where("status", "Hadir")
                ->count();

            // Hitung total slip gaji yang sudah digenerate untuk bulan & tahun ini
            $totalPayrollBulanIni = Payroll::where("bulan", date("m"))
                ->where("tahun", date("Y"))
                ->count();

            // Ambil 5 data absensi terbaru untuk tabel ringkasan
            $absensiTerbaru = Attendance::with("employee.user")
                ->latest("tanggal")
                ->take(5)
                ->get();

            // Nama bulan saat ini dalam Bahasa Indonesia
            $namaBulanIni = Payroll::getNamaBulan(date("m"));

            return view(
                "dashboard",
                compact(
                    "totalPegawai",
                    "absensiHariIni",
                    "hadirHariIni",
                    "totalPayrollBulanIni",
                    "absensiTerbaru",
                    "namaBulanIni",
                ),
            );
        }

        // ---- Data untuk tampilan dashboard pegawai ----

        // Ambil data employee milik user yang sedang login
        $employee = $user->employee;

        // Cek apakah pegawai sudah absen hari ini
        $todayAttendance = $employee
            ? Attendance::where("employee_id", $employee->id)
                ->where("tanggal", today())
                ->first()
            : null;

        // Ambil 10 absensi terbaru milik pegawai ini
        $recentAttendances = $employee
            ? Attendance::where("employee_id", $employee->id)
                ->latest("tanggal")
                ->take(10)
                ->get()
            : collect();

        // Ambil 6 payroll terbaru milik pegawai ini
        $recentPayrolls = $employee
            ? Payroll::where("employee_id", $employee->id)
                ->orderByDesc("tahun")
                ->orderByDesc("bulan")
                ->take(6)
                ->get()
            : collect();

        return view(
            "dashboard",
            compact(
                "employee",
                "todayAttendance",
                "recentAttendances",
                "recentPayrolls",
            ),
        );
    })->name("dashboard");

    // ----------------------------------------------------------
    // ABSEN MANDIRI — pegawai mencatat kehadirannya sendiri
    // dari tombol di dashboard (bukan route admin)
    // ----------------------------------------------------------
    Route::post("/my-attendance", [
        AttendanceController::class,
        "storeSelf",
    ])->name("my.attendance.store");

    // ----------------------------------------------------------
    // ROUTE PROFIL (dari Breeze — untuk semua user yang login)
    // ----------------------------------------------------------
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    // ----------------------------------------------------------
    // ABSENSI INDEX — semua user yang login bisa melihat
    // ----------------------------------------------------------
    Route::get("attendances", [AttendanceController::class, "index"])->name(
        "attendances.index",
    );

    // ----------------------------------------------------------
    // PENGGAJIAN — index & show bisa diakses semua user yang login
    // (controller akan memfilter data sesuai role masing-masing)
    // ----------------------------------------------------------
    Route::get("payrolls", [PayrollController::class, "index"])->name(
        "payrolls.index",
    );
    Route::get("payrolls/{payroll}", [PayrollController::class, "show"])->name(
        "payrolls.show",
    );

    // ----------------------------------------------------------
    // ROUTE KHUSUS ADMIN (middleware: can:admin)
    // Hanya user dengan role 'admin' yang boleh mengakses.
    // Menggunakan Gate yang didefinisikan di AppServiceProvider.
    // ----------------------------------------------------------
    Route::middleware(["can:admin"])->group(function () {
        // Manajemen data pegawai (CRUD lengkap)
        Route::resource("employees", EmployeeController::class);

        // Absensi: create, store, edit, update, destroy — hanya admin
        Route::get("attendances/create", [
            AttendanceController::class,
            "create",
        ])->name("attendances.create");
        Route::post("attendances", [
            AttendanceController::class,
            "store",
        ])->name("attendances.store");
        Route::get("attendances/{attendance}/edit", [
            AttendanceController::class,
            "edit",
        ])->name("attendances.edit");
        Route::patch("attendances/{attendance}", [
            AttendanceController::class,
            "update",
        ])->name("attendances.update");
        Route::delete("attendances/{attendance}", [
            AttendanceController::class,
            "destroy",
        ])->name("attendances.destroy");

        // Penggajian: preview, create, store, destroy — hanya admin
        Route::get("payrolls/preview", [
            PayrollController::class,
            "preview",
        ])->name("payrolls.preview");
        Route::get("payrolls/create", [
            PayrollController::class,
            "create",
        ])->name("payrolls.create");
        Route::post("payrolls", [PayrollController::class, "store"])->name(
            "payrolls.store",
        );
        Route::delete("payrolls/{payroll}", [
            PayrollController::class,
            "destroy",
        ])->name("payrolls.destroy");
    });
});

// Load route autentikasi bawaan Breeze (login, register, logout, dll)
require __DIR__ . "/auth.php";
