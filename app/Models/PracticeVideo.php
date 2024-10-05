<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;
use App\Models\Video;

class PracticeVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'skill_id',
        'subcategory_id',
        'video_id'
    ];

    public function skill()
    {
        return $this->hasOne(Skill::class, 'id', 'skill_id')->where('status', 1);
    }
    
    public function video()
    {
        return $this->hasOne(Video::class, 'id', 'video_id')->where('status', 1);
    }

    public function category()
    {
        return $this->hasOne(SubCategory::class, 'id', 'subcategory_id')->where('status', 1);
    }
    
}
