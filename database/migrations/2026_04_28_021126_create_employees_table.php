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
        Schema::create("employees", function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel users (relasi One-to-One)
            $table
                ->foreignId("user_id")
                ->constrained("users")
                ->onDelete("cascade");
            // Nomor Induk Karyawan, harus unik
            $table->string("nik")->unique();
            // Jabatan / posisi pegawai
            $table->string("jabatan");
            // Gaji pokok dalam satuan rupiah (integer)
            $table->integer("gaji_pokok");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("employees");
    }
};
