<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizType extends Model
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

    public function quizzes() {
        return $this->hasMany(Quizze::class);
    }
}
