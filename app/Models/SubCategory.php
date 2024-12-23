<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'sections',
        'type',
        'status'
    ];

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}
