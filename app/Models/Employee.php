<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (mass assignment).
     */
    protected $fillable = [
        "user_id", // Referensi ke tabel users
        "nik", // Nomor Induk Karyawan
        "jabatan", // Jabatan / posisi pegawai
        "gaji_pokok", // Gaji pokok dalam rupiah
    ];

    /**
     * Relasi ke User: satu Employee dimiliki oleh satu User.
     * Ini adalah sisi "inverse" dari relasi One-to-One.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Attendance: satu Employee bisa punya banyak absensi.
     * Relasi One-to-Many.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi ke Payroll: satu Employee bisa punya banyak slip gaji.
     * Relasi One-to-Many.
     */
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * BUSINESS LOGIC: Hitung jumlah absensi 'Alpa' pada bulan & tahun tertentu.
     * Dipakai saat proses generate payroll.
     *
     * @param string $bulan  Format: '01' s/d '12'
     * @param string $tahun  Format: '2025', '2026', dst
     * @return int Jumlah hari Alpa
     */
    public function countAlpa(string $bulan, string $tahun): int
    {
        return $this->attendances()
            // Filter hanya absensi dengan status 'Alpa'
            ->where("status", "Alpa")
            // Filter bulan: mengambil bagian bulan dari kolom tanggal
            ->whereMonth("tanggal", $bulan)
            // Filter tahun: mengambil bagian tahun dari kolom tanggal
            ->whereYear("tanggal", $tahun)
            ->count();
    }

    /**
     * BUSINESS LOGIC: Kalkulasi dan simpan (atau update) data payroll.
     * Mengikuti aturan kalkulasi dari context.md:
     *   - total_potongan = jumlah_alpa * 100.000
     *   - gaji_bersih    = gaji_pokok + total_tunjangan - total_potongan
     *
     * Fitur Idempotency: jika payroll sudah ada, lakukan UPDATE bukan INSERT.
     *
     * @param string $bulan          Format: '01' s/d '12'
     * @param string $tahun          Format: '2025', '2026', dst
     * @param int    $total_tunjangan Total tunjangan (default 0)
     * @return Payroll               Instance payroll yang baru dibuat / diupdate
     */
    public function generatePayroll(
        string $bulan,
        string $tahun,
        int $total_tunjangan = 0,
    ): Payroll {
        // Hitung jumlah hari Alpa pada periode ini
        $jumlah_alpa = $this->countAlpa($bulan, $tahun);

        // Potongan: setiap 1 hari Alpa = Rp 100.000
        $total_potongan = $jumlah_alpa * 100000;

        // Gaji bersih = gaji pokok + tunjangan - potongan
        $gaji_bersih = $this->gaji_pokok + $total_tunjangan - $total_potongan;

        // updateOrCreate: UPDATE jika sudah ada, INSERT jika belum (Idempotency)
        return $this->payrolls()->updateOrCreate(
            // Kondisi pencarian: payroll untuk bulan & tahun ini
            ["bulan" => $bulan, "tahun" => $tahun],
            // Data yang akan disimpan / diperbarui
            [
                "total_tunjangan" => $total_tunjangan,
                "total_potongan" => $total_potongan,
                "gaji_bersih" => $gaji_bersih,
            ],
        );
    }
}
