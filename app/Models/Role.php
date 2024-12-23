<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function hasPermission($name)
    {
        return $this->role->permissions()->where('name',$name)->exists();
    }

}
