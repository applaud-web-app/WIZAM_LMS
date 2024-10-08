<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeSetResult extends Model
{
    use HasFactory;
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime', 
    ];
    
    protected $fillable = [
        'practice_sets_id',
        'correct_answers',
        'subcategory_id',
        'user_id',
        'uuid',
        'questions',
        'answers',
        'exam_duration',
        'point',
        'total_question',
        'correct_answer',
        'incorrect_answer',
        'status',
        'start_time',
        'end_time'
    ];
}
