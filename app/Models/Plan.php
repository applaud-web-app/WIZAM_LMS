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
        'discount_percentage',
        'price',
        'discount',
        'description',
        'sort_order',
        'feature_access',
        'features',
        'popular',
        'status',
        'stripe_product_id',
        'stripe_price_id'
    ];

    public function category(){
        return $this->hasOne(SubCategory::class, 'id', 'category_id');
    }
}
