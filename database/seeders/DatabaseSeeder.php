<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Memanggil AdminSeeder untuk mengisi data awal (admin + pegawai + absensi).
     */
    public function run(): void
    {
        // Panggil AdminSeeder sebagai satu-satunya seeder utama
        $this->call([AdminSeeder::class]);
    }
}
