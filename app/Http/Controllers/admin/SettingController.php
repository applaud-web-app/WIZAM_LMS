<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\BillingSetting;


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
        $country = Country::get();
        $state = null;
        $city = null;
        $billingTaxSetting = BillingSetting::first();
        if(isset($billingTaxSetting->state_id)){
            $state = State::where('country_id', $billingTaxSetting->country_id)->get();
            $city = City::where('state_id', $billingTaxSetting->state_id)->get();
        }
        return view('setting.billing-setting',compact('country','city','state','billingTaxSetting'));
    }

    public function getStates(Request $request)
    {
        $countryId = $request->input('country_id');
        $states = State::where('country_id', $countryId)->get();
        
        return response()->json(['states' => $states]);
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('state_id');
        $cities = City::where('state_id', $stateId)->get();

        return response()->json(['cities' => $cities]);
    }

    public function saveBillingData(Request $request){
        // Validate the form data
        $validatedData = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|integer',
            'state' => 'nullable|integer',
            'city' => 'nullable|integer',
            'zip' => 'nullable|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:50',
            'enable_invoicing' => 'sometimes|boolean',
            'invoice_prefix' => 'required|string|max:50',
        ]);

        $billing = BillingSetting::first();
        if($billing){
            $billing->vendor_name = $validatedData['vendor_name'];
            $billing->address = $validatedData['address'] ?? null;
            $billing->country_id = $validatedData['country'] ?? null;
            $billing->state_id = $validatedData['state'] ?? null;
            $billing->city_id = $validatedData['city'] ?? null;
            $billing->zip = $validatedData['zip'] ?? null;
            $billing->phone_number = $validatedData['phone_number'] ?? null;
            $billing->vat_number = $validatedData['vat_number'] ?? null;
            $billing->enable_invoicing = $request->has('enable_invoicing') ? 1 : 0;
            $billing->invoice_prefix = $validatedData['invoice_prefix'];
            $billing->save();
            return redirect()->back()->with('success', 'Billing information saved successfully!');
        }

        // Save the validated data to the Billing model
        $billing = new BillingSetting();
        $billing->vendor_name = $validatedData['vendor_name'];
        $billing->address = $validatedData['address'] ?? null;
        $billing->country_id = $validatedData['country'] ?? null;
        $billing->state_id = $validatedData['state'] ?? null;
        $billing->city_id = $validatedData['city'] ?? null;
        $billing->zip = $validatedData['zip'] ?? null;
        $billing->phone_number = $validatedData['phone_number'] ?? null;
        $billing->vat_number = $validatedData['vat_number'] ?? null;
        $billing->enable_invoicing = $request->has('enable_invoicing') ? 1 : 0;
        $billing->invoice_prefix = $validatedData['invoice_prefix'];

        $billing->save();

        return redirect()->back()->with('success', 'Billing information saved successfully!');
    }
    
    public function saveTaxData(Request $request){

    }

    // MAINTANACE SETTING
    public function maintenanceSetting(){
        $generalSetting = GeneralSetting::first();
        return view('setting.maintenance',compact('generalSetting'));
    }

    public function saveMaintenanceSetting(Request $request){
        try {
            $request->validate([
                'maintenance_mode'=>'required|in:0,1'
            ]);
            $generalSetting = GeneralSetting::first();
            $generalSetting->maintenance_mode = $request->maintenance_mode;
            $generalSetting->save();
            return redirect()->back()->with('success','Maintenance Mode Updated Successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th->getMessage());
        }
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
