<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("payrolls", function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel employees (relasi One-to-Many)
            $table
                ->foreignId("employee_id")
                ->constrained("employees")
                ->onDelete("cascade");
            // Bulan penggajian, format: '01' s/d '12'
            $table->string("bulan", 2);
            // Tahun penggajian, format: '2025', '2026', dst
            $table->string("tahun", 4);
            // Total tunjangan yang diterima pegawai (default 0)
            $table->integer("total_tunjangan")->default(0);
            // Total potongan dari absensi Alpa (default 0)
            $table->integer("total_potongan")->default(0);
            // Gaji bersih = gaji_pokok + total_tunjangan - total_potongan
            $table->integer("gaji_bersih");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("payrolls");
    }
};
