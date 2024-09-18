<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sections;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section_id',
        'description',
        'status'
    ];

    public function section(){
        return $this->hasOne(Sections::class, 'id', 'section_id');
    }
}
