<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends Model
{
    use HasFactory;
    protected $table = "subscription_items";

    protected $fillable = [
        'subscription_id',
        'item_type',
        'item_id',
        'assigned_at',
        'expires_at',
        'status',
    ];
}