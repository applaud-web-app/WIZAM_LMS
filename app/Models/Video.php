<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'type',
        'source',
        'description',
        'skill_id',
        'thumbnail',
        'topic_id',
        'tags',
        'level',
        'watch_time',
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
