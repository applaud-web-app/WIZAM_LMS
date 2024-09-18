<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Skill;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'skill_id',
        'description',
        'status'
    ];

    public function skill(){
        return $this->hasOne(Skill::class, 'id', 'skill_id');
    }
}
