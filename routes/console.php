<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('scheduleStatus:cron')->everyMinute()->appendOutputTo('commond1.txt');
Schedule::command('updateExamResultStatus:cron')->everyMinute()->appendOutputTo('commond2.txt');
