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
}
