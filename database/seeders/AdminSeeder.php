<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi data awal database.
     * Membuat: 1 akun Admin + 3 akun Pegawai beserta data absensi contoh.
     */
    public function run(): void
    {
        // ============================================================
        // LANGKAH 1: Buat akun Admin
        // ============================================================
        $admin = User::create([
            "name" => "Administrator",
            "email" => "admin@payroll.com",
            "password" => Hash::make("password"), // Password default: 'password'
            "role" => "admin",
        ]);

        $this->command->info("✅ Admin dibuat: {$admin->email}");

        // ============================================================
        // LANGKAH 2: Buat 3 akun Pegawai beserta data Employee-nya
        // ============================================================
        $pegawaiData = [
            [
                "name" => "Budi Santoso",
                "email" => "budi@payroll.com",
                "nik" => "3201010101010001",
                "jabatan" => "Staff IT",
                "gaji_pokok" => 5000000,
            ],
            [
                "name" => "Siti Rahayu",
                "email" => "siti@payroll.com",
                "nik" => "3201010101010002",
                "jabatan" => "Staff Keuangan",
                "gaji_pokok" => 4500000,
            ],
            [
                "name" => "Ahmad Fauzi",
                "email" => "ahmad@payroll.com",
                "nik" => "3201010101010003",
                "jabatan" => "Staff HRD",
                "gaji_pokok" => 4000000,
            ],
        ];

        $employees = [];

        foreach ($pegawaiData as $data) {
            // Buat akun User dengan role 'pegawai'
            $user = User::create([
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => Hash::make("password"), // Password default: 'password'
                "role" => "pegawai",
            ]);

            // Buat data Employee yang terhubung ke User di atas
            $employee = Employee::create([
                "user_id" => $user->id,
                "nik" => $data["nik"],
                "jabatan" => $data["jabatan"],
                "gaji_pokok" => $data["gaji_pokok"],
            ]);

            $employees[] = $employee;

            $this->command->info(
                "✅ Pegawai dibuat: {$data["name"]} ({$data["email"]})",
            );
        }

        // ============================================================
        // LANGKAH 3: Buat data absensi contoh untuk bulan ini
        // Setiap pegawai mendapatkan 10 hari absensi dengan variasi status.
        // ============================================================
        $bulanIni = date("m"); // Bulan sekarang, format: '04'
        $tahunIni = date("Y"); // Tahun sekarang, format: '2026'

        // Pola absensi untuk masing-masing pegawai:
        // Pegawai 1 (Budi): 8 Hadir, 1 Izin, 1 Alpa
        // Pegawai 2 (Siti): 9 Hadir, 1 Izin, 0 Alpa
        // Pegawai 3 (Ahmad): 7 Hadir, 1 Izin, 2 Alpa
        $pola = [
            [
                "Hadir",
                "Hadir",
                "Hadir",
                "Hadir",
                "Izin",
                "Hadir",
                "Hadir",
                "Alpa",
                "Hadir",
                "Hadir",
            ],
            [
                "Hadir",
                "Hadir",
                "Hadir",
                "Hadir",
                "Hadir",
                "Izin",
                "Hadir",
                "Hadir",
                "Hadir",
                "Hadir",
            ],
            [
                "Hadir",
                "Alpa",
                "Hadir",
                "Hadir",
                "Izin",
                "Hadir",
                "Alpa",
                "Hadir",
                "Hadir",
                "Hadir",
            ],
        ];

        foreach ($employees as $index => $employee) {
            // Ambil pola absensi untuk pegawai ini
            $statusPola = $pola[$index];

            foreach ($statusPola as $hari => $status) {
                // Buat tanggal: mulai dari tanggal 1 bulan ini, +$hari hari
                $tanggal = \Carbon\Carbon::create(
                    $tahunIni,
                    $bulanIni,
                    1,
                )->addDays($hari);

                // Lewati jika tanggal sudah melewati hari ini
                // (agar data terlihat realistis)
                if ($tanggal->isFuture()) {
                    continue;
                }

                // Simpan data absensi ke tabel 'attendances'
                Attendance::create([
                    "employee_id" => $employee->id,
                    "tanggal" => $tanggal->format("Y-m-d"),
                    "status" => $status,
                ]);
            }

            $this->command->info(
                "✅ Data absensi dibuat untuk: {$employee->user->name}",
            );
        }

        // ============================================================
        // RINGKASAN AKUN yang bisa digunakan untuk login
        // ============================================================
        $this->command->newLine();
        $this->command->info("====================================");
        $this->command->info("  AKUN LOGIN YANG TERSEDIA:");
        $this->command->info("====================================");
        $this->command->info("  👑 ADMIN");
        $this->command->info("     Email   : admin@payroll.com");
        $this->command->info("     Password: password");
        $this->command->info("------------------------------------");
        $this->command->info("  👤 PEGAWAI 1");
        $this->command->info("     Email   : budi@payroll.com");
        $this->command->info("     Password: password");
        $this->command->info("  👤 PEGAWAI 2");
        $this->command->info("     Email   : siti@payroll.com");
        $this->command->info("     Password: password");
        $this->command->info("  👤 PEGAWAI 3");
        $this->command->info("     Email   : ahmad@payroll.com");
        $this->command->info("     Password: password");
        $this->command->info("====================================");
    }
}
