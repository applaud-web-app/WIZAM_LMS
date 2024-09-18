<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'category_id',
        'short_description',
        'content',
        'image',
    ];

    public function category(){
        return $this->hasOne(BlogCategory::class, 'id', 'category_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
