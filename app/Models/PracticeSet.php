<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\SubCategory;

class PracticeSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subCategory_id',
        'skill_id',
        'description',
        'allow_reward',
        'reward_popup',
        'point_mode',
        'points',
        'is_free',
        'status'
    ];

    public function practiceQuestions() {
        return $this->hasMany(PracticeSetQuestion::class, 'practice_set_id', 'id');
    }
    
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subCategory_id', 'id');
    }
    
    public function skill(){
        return $this->hasOne(Skill::class, 'id', 'skill_id');
    }
}
