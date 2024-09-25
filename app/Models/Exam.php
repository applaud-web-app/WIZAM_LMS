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

}
