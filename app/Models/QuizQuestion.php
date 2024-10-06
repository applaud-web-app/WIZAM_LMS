<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'quizzes_id'
    ];

    public function topic(){
        return $this->hasOne(Question::class,  'id','question_id');
    }

    // Define the relationship to the `Question` model
    public function questions()
    {
        return $this->belongsTo(Question::class, 'question_id','id');
    }
}
