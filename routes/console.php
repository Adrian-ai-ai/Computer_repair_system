<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automated reports
<<<<<<< HEAD
Schedule::command('reports:send-scheduled daily')
    ->dailyAt('09:00')
    ->mondays() // Only on weekdays, adjust as needed
    ->tuesdays()
    ->wednesdays()
    ->thursdays()
    ->fridays();

Schedule::command('reports:send-scheduled weekly')
    ->weeklyOn(1, '09:00'); // Every Monday at 9 AM
=======
// Schedule::command('reports:send-scheduled daily')
//     ->dailyAt('09:00')
//     ->mondays() // Only on weekdays, adjust as needed
//     ->tuesdays()
//     ->wednesdays()
//     ->thursdays()
//     ->fridays();

// Schedule::command('reports:send-scheduled weekly')
//     ->weeklyOn(1, '09:00'); // Every Monday at 9 AM
>>>>>>> 814c0e3a080a93b0a4c40958610f5493345a9fd8
