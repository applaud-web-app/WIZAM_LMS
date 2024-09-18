<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }


     // Method for handling general settings view
     public function generalSetting()
     {
         // Logic to show general settings view
         return view('setting.general-setting');
     }
 
     // Method for handling email settings view
     public function emailSetting()
     {
         // Logic to show email settings view
         return view('setting.email-setting');
     }
 
     // Method for handling billing settings view
     public function billingSetting()
     {
         // Logic to show billing settings view
         return view('setting.billing-setting');
     }
 
     // Method for handling homepage settings view
     public function homePageSetting()
     {
         // Logic to show homepage settings view
         return view('setting.homepage-setting');
     }
 
     // Method for handling maintenance view
     public function maintenance()
     {
         // Logic to show maintenance view
         return view('setting.maintenance');
     }

     public function allUsers()
     {
         // Logic to show maintenance view
         return view('all-users');
     }

     public function addUser()
     {
         // Logic to show maintenance view
         return view('add-user');
     }
     public function userGroup()
     {
         // Logic to show maintenance view
         return view('user-group');
     }
     public function importUsers()
     {
         // Logic to show maintenance view
         return view('import-users');
     }
   

}
