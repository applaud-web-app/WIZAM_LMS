<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'subscription_id',
        'payment_id',
        'amount',
        'currency',
        'status',
        'payment_date',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function subscription(){
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }
}
