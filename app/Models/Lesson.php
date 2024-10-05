<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;
use App\Models\Topic;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'description',
        'skill_id',
        'topic_id',
        'tags',
        'level',
        'read_time',
        'is_free',
        'status'
    ];

    public function skill(){
        return $this->hasOne(Skill::class,'id','skill_id');
    }

    public function topic(){
        return $this->hasOne(Topic::class,  'id','topic_id');
    }

}
