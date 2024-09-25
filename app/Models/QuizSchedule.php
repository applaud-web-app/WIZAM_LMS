<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'quizzes_id',
        'schedule_type',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'grace_period',
        'user_groups',
        'status'
    ];
}
