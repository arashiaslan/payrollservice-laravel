<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        "employee_id", // Referensi ke tabel employees
        "bulan", // Bulan penggajian, format: '01' s/d '12'
        "tahun", // Tahun penggajian, format: '2025', '2026', dst
        "total_tunjangan", // Total tunjangan yang diterima pegawai
        "total_potongan", // Total potongan dari absensi Alpa
        "gaji_bersih", // Hasil akhir: gaji_pokok + tunjangan - potongan
    ];

    /**
     * Relasi ke Employee: setiap slip gaji dimiliki oleh satu pegawai.
     * Ini adalah sisi "inverse" dari relasi One-to-Many.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Helper: format angka rupiah ke format mata uang Indonesia.
     * Contoh: 5000000 -> "Rp 5.000.000"
     *
     * @param int $amount Jumlah dalam satuan rupiah
     * @return string     Teks rupiah yang sudah diformat
     */
    public static function formatRupiah(int $amount): string
    {
        // number_format: tambahkan titik setiap 3 angka, tanpa desimal
        return "Rp " . number_format($amount, 0, ",", ".");
    }

    /**
     * Helper: dapatkan nama bulan dalam Bahasa Indonesia.
     * Dipakai untuk ditampilkan di UI / slip gaji.
     *
     * @param string $bulan Angka bulan, format: '01' s/d '12'
     * @return string       Nama bulan dalam Bahasa Indonesia
     */
    public static function getNamaBulan(string $bulan): string
    {
        $namaBulan = [
            "01" => "Januari",
            "02" => "Februari",
            "03" => "Maret",
            "04" => "April",
            "05" => "Mei",
            "06" => "Juni",
            "07" => "Juli",
            "08" => "Agustus",
            "09" => "September",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember",
        ];

        // Kembalikan nama bulan, atau tanda tanya jika tidak valid
        return $namaBulan[$bulan] ?? "?";
    }
}
