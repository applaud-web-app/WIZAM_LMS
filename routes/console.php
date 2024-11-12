<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// EXAM
Schedule::command('scheduleStatus:cron')->everyMinute()->appendOutputTo('commond1.txt');
Schedule::command('updateExamResultStatus:cron')->everyMinute()->appendOutputTo('commond2.txt');

// QUIZ
Schedule::command('quizscheduleStatus:cron')->everyMinute()->appendOutputTo('commond3.txt');
Schedule::command('updateQuizResultStatus:cron')->everyMinute()->appendOutputTo('commond4.txt');

// PRACTICE SET
Schedule::command('updatepracticeResultStatus:cron')->everyMinute()->appendOutputTo('commond5.txt');
