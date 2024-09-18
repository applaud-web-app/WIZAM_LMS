<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'solution',
        'video_enable',
        'video_type',
        'video_source',
        'hint',
        'attachment_type',
        'attachment_source'
    ];
}
