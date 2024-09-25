<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileManager extends Model
{
    use HasFactory;
    protected $fillable = [
        'node_name',
        'parent_node',
        'type',
        'source',
    ];
}
