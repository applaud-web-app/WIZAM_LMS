<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'section_id',
        'display_name',
        'section_order',
        'status'
    ];

    public function section(){
        return $this->hasOne(Sections::class,'id','section_id');
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class, 'section_id', 'id');
    }
    


}
