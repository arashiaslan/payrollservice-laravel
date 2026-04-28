<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attendance;

class MarkAbsencesAsAlpa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-alpa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis mencatat status Alpa untuk pegawai yang belum absen hari ini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hariIni = today();

        // Cari pegawai yang belum memiliki absensi pada hari ini
        $pegawaiBelumAbsen = Employee::whereDoesntHave('attendances', function ($query) use ($hariIni) {
            $query->where('tanggal', $hariIni);
        })->get();

        $jumlahAlpa = 0;

        foreach ($pegawaiBelumAbsen as $pegawai) {
            Attendance::create([
                'employee_id' => $pegawai->id,
                'tanggal' => $hariIni,
                'status' => 'Alpa',
            ]);

            $jumlahAlpa++;
        }

        $this->info("Berhasil menandai {$jumlahAlpa} pegawai sebagai Alpa untuk hari ini.");
    }
}
