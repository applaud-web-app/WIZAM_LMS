<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'color',
        'img_url',
        'description',
        'status'
    ];

    public function exams() {
        return $this->hasMany(Exam::class);
    }
}
