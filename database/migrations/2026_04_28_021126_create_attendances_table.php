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
        Schema::create("attendances", function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel employees (relasi One-to-Many)
            $table
                ->foreignId("employee_id")
                ->constrained("employees")
                ->onDelete("cascade");
            // Tanggal absensi
            $table->date("tanggal");
            // Status kehadiran: Hadir, Izin, atau Alpa
            $table->enum("status", ["Hadir", "Izin", "Alpa"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("attendances");
    }
};
