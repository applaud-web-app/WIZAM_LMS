<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    use HasFactory;
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime', // Cast to Carbon
        // Add other attributes here if needed
    ];
    
    protected $fillable = [
        'quiz_id',
        'correct_answers',
        'subcategory_id',
        'user_id',
        'uuid',
        'questions',
        'answers',
        'exam_duration',
        'point',
        'negative_marking',
        'pass_percentage',
        'student_percentage',
        'total_question',
        'correct_answer',
        'incorrect_answer',
        'status',
        'start_time',
        'end_time'
    ];
}
