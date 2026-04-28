<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwalkan pengecekan absen Alpa otomatis setiap hari pada jam 23:59
Schedule::command('attendance:mark-alpa')->dailyAt('23:59');

