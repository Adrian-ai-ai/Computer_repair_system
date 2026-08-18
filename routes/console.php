<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automated reports
// Schedule::command('reports:send-scheduled daily')
//     ->dailyAt('09:00')
//     ->mondays() // Only on weekdays, adjust as needed
//     ->tuesdays()
//     ->wednesdays()
//     ->thursdays()
//     ->fridays();

// Schedule::command('reports:send-scheduled weekly')
//     ->weeklyOn(1, '09:00'); // Every Monday at 9 AM
