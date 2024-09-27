<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'price_type',
        'duration',
        'price',
        'discount',
        'description',
        'sort_order',
        'feature_access',
        'features',
        'popular',
        'status'
    ];

    public function category(){
        return $this->hasOne(SubCategory::class, 'id', 'category_id');
    }
}
