<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
    ];
}
