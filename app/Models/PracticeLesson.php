<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeLesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'skill_id',
        'subcategory_id',
        'lesson_id'
    ];

    public function skill()
    {
        return $this->hasOne(Skill::class, 'id', 'skill_id')->where('status', 1);
    }
    
    public function lesson()
    {
        return $this->hasOne(Lesson::class, 'id', 'lesson_id')->where('status', 1);
    }

    public function category()
    {
        return $this->hasOne(SubCategory::class, 'id', 'subcategory_id')->where('status', 1);
    }
}
