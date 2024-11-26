<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = "subscriptions";

    protected $fillable = [
        'user_id',
        'plan_id',
        'type',
        'stripe_subscription_id',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'status',
        'start_date',
        'end_date',
    ];

    
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function plans()
    {
        return $this->hasOne(Plan::class, 'stripe_price_id', 'stripe_price');
    }

}
