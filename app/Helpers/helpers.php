<?php

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;  
use App\Models\User;  


if(!function_exists('userHasPermission')){
    function userHasPermission($permission)
    {
        if (Auth::check()) {
            // Assuming the User model has a `hasPermission` method
            return Auth::user()->hasPermissionTo($permission);
        }

        // If the user is not authenticated, return false
        return false;
    }
}

if (!function_exists('getUserRoles')) {
    function getUserRoles()
    {
        if (Auth::check()) {
            // Assuming the User model has a `roles` relationship
            return Auth::user()->roles;
        }

        // If the user is not authenticated, return null
        return null;
    }
}

if (!function_exists('encrypturl')) {
    function encrypturl($url,$parms)
    {
        $encodeUrl = $url."?eq=".urlencode(Crypt::encrypt($parms));
        return $encodeUrl;
    }
}

if (!function_exists('decrypturl')) {
    function decrypturl($parms)
    {
        $decryptUrl = Crypt::decrypt($parms);
        parse_str($decryptUrl, $parsedParams);
        return $parsedParams;
    }
}