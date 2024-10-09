<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'title',
        'slug',
        'img_url',
        'point',
        'duration_type',
        'subcategory_id',
        'exam_type_id',
        'description',
        'download_report',
        'favourite',
        'duration_mode',
        'point_mode',
        'negative_marking',
        'pass_percentage',
        'cutoff',
        'shuffle_questions',
        'restrict_attempts',
        'total_attempts',
        'disable_navigation',
        'disable_finish_button',
        'question_view',
        'hide_solutions',
        'leaderboard',
        'price',
        'exam_duration',
        'is_public',
        'is_free',
        'status',
    ];

    public function type()
    {
        return $this->hasOne(ExamType::class, 'id', 'exam_type_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions');
    }

    // Define the relationship with QuizQuestion model
    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id', 'id');
    }

}
