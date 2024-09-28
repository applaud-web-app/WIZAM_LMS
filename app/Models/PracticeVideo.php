<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'skill_id',
        'subcategory_id',
        'video_id'
    ];
}