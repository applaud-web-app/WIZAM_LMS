<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'options',
        'answer',
        'type',
        'tags',
        'level',
        'watch_time',
        'default_marks',
        'skill_id',
        'topic_id',
        'status'
    ];

    public function skill(){
        return $this->hasOne(Skill::class, 'id', 'skill_id');
    }

    public function topic(){
        return $this->hasOne(Topic::class, 'id', 'topic_id');
    }

}
