<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeSetQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'practice_set_id',
        'question_id'
    ];
    
    public function questions()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
