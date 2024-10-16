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
        'stripe_payment_id',
        'amount',
        'currency',
        'status',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
