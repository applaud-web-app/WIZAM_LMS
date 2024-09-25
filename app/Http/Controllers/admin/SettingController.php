<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class SettingController extends Controller
{
    public function homeSetting(){
        return view('setting.homepage-setting');
    }

    public function generalSettings(){
        $generalSetting = GeneralSetting::first();
        return view('setting.general-setting',compact('generalSetting'));
    }

    public function updateGeneralSetting(Request $request){
        // dd($request->all());
        // Validate the form input
        $request->validate([
            'site_name' => 'required|string|max:255',
            'tag_line' => 'required|string|max:255',
            'seo_description' => 'required|string|max:1000',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if there is an existing GeneralSetting
        $generalSetting = GeneralSetting::first();

        // If no record exists, create a new instance
        if (!$generalSetting) {
            $generalSetting = new GeneralSetting();
        }

        // Update the settings values
        $generalSetting->site_name = $request->input('site_name');
        $generalSetting->tag_line = $request->input('tag_line');
        $generalSetting->description = $request->input('seo_description');

        if ($request->hasFile('site_logo')) {
            $image = $request->file('site_logo');
            // Generate a unique filename with the current timestamp
            $imageName = 'logo_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/blogs' directory
            $image->move(public_path('setting'), $imageName);
            // Store the image path in the database (relative to the public directory)
            $imagePath = $imageName;
            $generalSetting->site_logo = $imagePath;
        }

        if ($request->hasFile('site_favicon')) {
            $image = $request->file('site_favicon');
            // Generate a unique filename with the current timestamp
            $imageName = 'favicon_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/blogs' directory
            $image->move(public_path('setting'), $imageName);
            // Store the image path in the database (relative to the public directory)
            $imagePath = $imageName;
            $generalSetting->favicon = $imagePath;
        }

        // Save the record (Insert if new, Update if existing)
        $generalSetting->save();

        // Redirect with success message
        return redirect()->route('general-settings')->with('success', 'Settings updated successfully.');

    }

    public function emailSettings(){
        $generalSetting = GeneralSetting::first();
        return view('setting.email-setting',compact('generalSetting'));
    }

    public function updateEmailSettings(Request $request){
        // Define validation rules
        $request->validate([
            'host_name' => 'required|max:255',
            'port' => 'required|digits_between:1,65535',
            'userName' => 'required|max:255',
            'password' => 'required|min:6',
            'encryption' => 'required|max:50',
            'from_mail' => 'required|email',
            'from_name' => 'required|max:255',
        ]);

        // Retrieve and update settings
        $settings = GeneralSetting::first(); // Adjust if you have a different way of retrieving settings

        $settings->host_name = $request->input('host_name');
        $settings->port = $request->input('port');
        $settings->username = $request->input('userName');
        $settings->password = $request->input('password');
        $settings->encryption = $request->input('encryption');
        $settings->from_mail = $request->input('from_mail');
        $settings->from_name = $request->input('from_name');

        // Save the updated settings
        $settings->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Email settings updated successfully.');
    }

    // PAYMENT SETTING
    public function paymentSettings(Request $request){
        // 
    }

    // BILLING & TAX SETTING
    public function billingTaxSetting(Request $request){
        // 
    }

    // MAINTANACE SETTING
    public function maintenanceSetting(Request $request){
        // 
    }

    // TERM & CONDITION
    public function termCondition(Request $request){
        // 
    }

    // PRIVACY POLICY
    public function privacyPolicy(Request $request){
        // 
    }

}
