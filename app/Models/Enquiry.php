<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'study_mode',
        'course_id', // Adjust according to your actual foreign key name
        'hear_by',
        'message',
        'accept_condition',
        'contact_me'
    ];
}
