<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        "employee_id", // Referensi ke tabel employees
        "tanggal", // Tanggal absensi (format: YYYY-MM-DD)
        "status", // Status kehadiran: 'Hadir', 'Izin', atau 'Alpa'
    ];

    /**
     * Casting tipe data kolom secara otomatis.
     * Kolom 'tanggal' akan otomatis dikonversi menjadi objek Carbon (date).
     */
    protected $casts = [
        "tanggal" => "date",
    ];

    /**
     * Relasi ke Employee: setiap absensi dimiliki oleh satu pegawai.
     * Ini adalah sisi "inverse" dari relasi One-to-Many.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
