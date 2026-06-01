<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Jadwalin ngirim email tiap hari jam 20:00 (8 malam) waktu server
Schedule::command('app:send-daily-summary')->dailyAt('20:00');