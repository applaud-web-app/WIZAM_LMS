<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizze extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subcategory_id',
        'quiz_type_id',
        'description',
        'duration_mode',
        'duration',
        'point_mode',
        'point',
        'negative_marking',
        'negative_marking_type',
        'negative_marks',
        'pass_percentage',
        'shuffle_questions',
        'restrict_attempts',
        'total_attempts',
        'disable_finish_button',
        'question_view',
        'hide_solutions',
        'leaderboard',
        'is_public',
        'is_free',
        'status'
    ];

    public function type()
    {
        // return $this->hasOe(QuizType::class, 'quiz_type_id', 'id');
        return $this->hasOne(QuizType::class, 'id', 'quiz_type_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

     // Define the relationship with QuizQuestion model
     public function quizQuestions()
     {
         return $this->hasMany(QuizQuestion::class, 'quizzes_id', 'id');
     }


     public function questions()
     {
         return $this->belongsToMany(Question::class, 'quiz_questions', 'quizzes_id', 'question_id');
     }
 
     public function results()
     {
         return $this->hasMany(QuizResult::class, 'quiz_id');
     }
}
