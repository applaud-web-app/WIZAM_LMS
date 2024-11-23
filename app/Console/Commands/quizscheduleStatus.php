<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizSchedule;
use Carbon\Carbon;

class quizscheduleStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quizscheduleStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $examSchedules = QuizSchedule::where('status', 1)->get();
        foreach ($examSchedules as $schedule) {
            $currentTime = Carbon::now();
    
            switch ($schedule->schedule_type) {
                case 'fixed':
                    $startDateTime = Carbon::parse($schedule->start_date . ' ' . $schedule->start_time);
                    $gracePeriod = (int) $schedule->grace_period; // Cast to integer
                    $expireTime = $startDateTime->copy()->addMinutes($gracePeriod);
    
                    if ($currentTime->greaterThan($expireTime)) {
                        // Expire the schedule if current time is beyond the expire time
                        $schedule->status = 3;
                        $schedule->save();
                    }
                    break;
    
                case 'flexible':
                    $startDateTime = Carbon::parse($schedule->start_date . ' ' . $schedule->start_time);
                    $endDateTime = Carbon::parse($schedule->end_date . ' ' . $schedule->end_time);
    
                    if ($currentTime->greaterThan($endDateTime)) {
                        // Expire the schedule if current time is beyond the end date and time
                        $schedule->status = 3;
                        $schedule->save();
                    }
                    break;
    
                case 'attempts':
                    $startDateTime = Carbon::parse($schedule->start_date . ' ' . $schedule->start_time);
    
                    if ($currentTime->greaterThan($startDateTime)) {
                        // Expire the schedule immediately after the start time for 'attempts' type
                        $schedule->status = 3;
                        $schedule->save();
                    }
                    break;
            }
        }
    }
}
