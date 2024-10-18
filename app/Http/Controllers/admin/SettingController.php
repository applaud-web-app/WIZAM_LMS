<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\BillingSetting;
use App\Models\PaymentMode;
use App\Models\User;
use App\Models\HomeCms;
use App\Models\Question;
use App\Models\Quizze;
use App\Models\PracticeSet;
use App\Models\Enquiry;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use Hash;

class SettingController extends Controller
{

    public function dashboard(){
        $user = User::whereIn('status',[1,0])->count();
        $question = Question::whereIn('status',[1,0])->count();
        $quiz = Quizze::whereIn('status',[1,0])->count();
        $praticeSet = PracticeSet::whereIn('status',[1,0])->count();
        return view('dashboard',compact('user','question','quiz','praticeSet'));
    }

    public function generalSettings(){
        $generalSetting = GeneralSetting::first();
        return view('setting.general-setting',compact('generalSetting'));
    }

    public function updateGeneralSetting(Request $request){
        // Validate the form input
        $request->validate([
            'site_name' => 'required|string|max:255',
            'tag_line' => 'required|string|max:255',
            'copyright'=> 'required|string|max:255',
            'seo_description' => 'required|string|max:1000',
            'site_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'light_site_logo'=> 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $generalSetting->copyright = $request->input('copyright');
        $generalSetting->description = $request->input('seo_description');

        // if ($request->hasFile('site_logo')) {
        //     $image = $request->file('site_logo');
        //     // Generate a unique filename with the current timestamp
        //     $imageName = 'logo_' . time() . '.' . $image->getClientOriginalExtension();
        //     // Move the image to the 'public/blogs' directory
        //     $image->move(public_path('setting'), $imageName);
        //     // Store the image path in the database (relative to the public directory)
        //     $imagePath = $imageName;
        //     $generalSetting->site_logo = $imagePath;
        // }

        // if ($request->hasFile('light_site_logo')) {
        //     $image = $request->file('light_site_logo');
        //     // Generate a unique filename with the current timestamp
        //     $imageName = 'logo_' . time() . '.' . $image->getClientOriginalExtension();
        //     // Move the image to the 'public/blogs' directory
        //     $image->move(public_path('setting'), $imageName);
        //     // Store the image path in the database (relative to the public directory)
        //     $imagePath = $imageName;
        //     $generalSetting->light_site_logo = $imagePath;
        // }

        // if ($request->hasFile('site_favicon')) {
        //     $image = $request->file('site_favicon');
        //     // Generate a unique filename with the current timestamp
        //     $imageName = 'favicon_' . time() . '.' . $image->getClientOriginalExtension();
        //     // Move the image to the 'public/blogs' directory
        //     $image->move(public_path('setting'), $imageName);
        //     // Store the image path in the database (relative to the public directory)
        //     $imagePath = $imageName;
        //     $generalSetting->favicon = $imagePath;
        // }

        if ($request->hasFile('site_logo')) {
            $image = $request->file('site_logo');
            // Generate a unique filename with the current timestamp
            $imageName = 'logo_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/setting' directory
            $image->move(public_path('setting'), $imageName);
            // Store the complete URL in the database
            $imagePath = env('APP_URL') . '/setting/' . $imageName;
            $generalSetting->site_logo = $imagePath;
        }
        
        if ($request->hasFile('light_site_logo')) {
            $image = $request->file('light_site_logo');
            // Generate a unique filename with the current timestamp
            $imageName = 'light_logo_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/setting' directory
            $image->move(public_path('setting'), $imageName);
            // Store the complete URL in the database
            $imagePath = env('APP_URL') . '/setting/' . $imageName;
            $generalSetting->light_site_logo = $imagePath;
        }
        
        if ($request->hasFile('site_favicon')) {
            $image = $request->file('site_favicon');
            // Generate a unique filename with the current timestamp
            $imageName = 'favicon_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/setting' directory
            $image->move(public_path('setting'), $imageName);
            // Store the complete URL in the database
            $imagePath = env('APP_URL') . '/setting/' . $imageName;
            $generalSetting->favicon = $imagePath;
        }
        

        // Save the record (Insert if new, Update if existing)
        $generalSetting->save();

        // Redirect with success message
        return redirect()->route('general-settings')->with('success', 'Settings updated successfully.');

    }

    public function updateContactInfo(Request $request){
        // Validate the form input
        $request->validate([
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email'=> 'required|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
        ]);

        try {
            // Check if there is an existing GeneralSetting
            $generalSetting = GeneralSetting::first();

            // If no record exists, prompt the user to create one first
            if (!$generalSetting) {
                return redirect()->route('general-settings')->with('error', 'Please add General Setting Data first.');
            }


            // Update the settings values
            $generalSetting->address = $request->input('address');
            $generalSetting->number = $request->input('phone_number');
            $generalSetting->email = $request->input('email');
            $generalSetting->facebook = $request->input('facebook');
            $generalSetting->instagram = $request->input('instagram');
            $generalSetting->linkedin = $request->input('linkedin');
            $generalSetting->youtube = $request->input('youtube');
            $generalSetting->twitter = $request->input('twitter');

            // Save the record (Insert if new, Update if existing)
            $generalSetting->save();

            // Redirect with success message
            return redirect()->route('general-settings')->with('success', 'Contact information updated successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions that may occur
            return redirect()->route('general-settings')->with('error', 'An error occurred while updating contact information. Please try again.');
        }
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
     // Retrieve all payment modes at once
        $paymentMethods = PaymentMode::all();

        // Access settings by type, using the collection instance
        $paypalSetting = $paymentMethods->firstWhere('type', 'paypal');
        $stripeSetting = $paymentMethods->firstWhere('type', 'stripe');
        $razorpaySetting = $paymentMethods->firstWhere('type', 'razorpay');
        $bankSetting = $paymentMethods->firstWhere('type', 'bank');
        $generalSetting = GeneralSetting::first();

        $currency = Country::select('currency','code')->where('code','!=',null)->get();
        return view('setting.payment-settings',compact('generalSetting','currency','paymentMethods','paypalSetting','stripeSetting','razorpaySetting','bankSetting'));
    }

    public function updatePaymentSetting(Request $request){
        $request->validate([
            'default_payment'=>'required',
            'currency'=>'required',
            'currency_symbol'=>'required',
            'symbol_position'=>'required',
        ]);

        $generalSetting = GeneralSetting::first();
        if($generalSetting){
            $generalSetting->default_payment = $request->default_payment;
            $generalSetting->currency = $request->currency;
            $generalSetting->currency_symbol = $request->currency_symbol;
            $generalSetting->symbol_position = $request->symbol_position;
            $generalSetting->save();
            return redirect()->back()->with('success', 'Payment Setting Updated Successfully.');
        }
        return redirect()->back()->with('error','Please Add General Setting First');
    }


    // PAYMENT DETAILS
    public function paypalDetail(Request $request){
        // Validate the incoming request data
        $request->validate([
            'client_id' => 'required|string|max:255',
            'secret_key' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Prepare the details to be stored
        $details = [
            'client_id' => $request->client_id,
            'secret_key' => $request->secret_key,
        ];

        // Create or update the PayPal detail
        $paypalDetail = PaymentMode::updateOrCreate(
            ['type' => 'paypal'], // Assuming 'type' is the field to check for uniqueness
            [
                'details' => json_encode($details), // Store the details as a JSON string
                'status' => $request->status,
            ]
        );

        // Redirect or return a response
        return redirect()->route('payment-settings')->with('success', 'PayPal details saved successfully!');
    }

    public function stripeDetail(Request $request){
        // Validate the incoming request data for Stripe
        $request->validate([
            'api_key' => 'required|string|max:255',
            'secret_key' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Prepare the details to be stored
        $details = [
            'api_key' => $request->api_key,
            'secret_key' => $request->secret_key,
        ];

        // Create or update the Stripe details
        PaymentMode::updateOrCreate(
            ['type' => 'stripe'], // Type is 'stripe'
            [
                'details' => json_encode($details), // Store the details as a JSON string
                'status' => $request->status,
            ]
        );

        // Redirect or return a response
        return redirect()->route('payment-settings')->with('success', 'Stripe details saved successfully!');
    }

    public function razorpayDetail(Request $request){
        // Validate the incoming request data for Razorpay
        $request->validate([
            'api_key' => 'required|string|max:255',
            'secret_key' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Prepare the details to be stored
        $details = [
            'api_key' => $request->api_key,
            'secret_key' => $request->secret_key,
        ];

        // Create or update the Razorpay details
        PaymentMode::updateOrCreate(
            ['type' => 'razorpay'], // Type is 'razorpay'
            [
                'details' => json_encode($details), // Store the details as a JSON string
                'status' => $request->status,
            ]
        );

        // Redirect or return a response
        return redirect()->route('payment-settings')->with('success', 'Razorpay details saved successfully!');
    }

    public function bankDetail(Request $request){
        // Validate the incoming request data
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_owner' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'required|string|max:255',
            'routing_number' => 'required|string|max:255',
            'swift' => 'required|string|max:255',
            'other_detail' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Prepare details as an array
        $details = [
            'bank_name' => $request->bank_name,
            'account_owner' => $request->account_owner,
            'account_number' => $request->account_number,
            'iban' => $request->iban,
            'routing_number' => $request->routing_number,
            'swift' => $request->swift,
            'other_detail' => $request->other_detail,
        ];

        // Create or update the bank detail
        $bankDetail = PaymentMode::updateOrCreate(
            ['type' => 'bank'], // Assuming 'type' is the field to check for uniqueness
            [
                'details' => json_encode($details), // Store the details as a JSON string
                'status' => $request->status,
            ]
        );

        // Redirect or return a response
        return redirect()->route('payment-settings')->with('success', 'Bank details saved successfully!');
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
    
    public function saveTaxData(Request $request)
    {
        // Validate the form data
        $request->validate([
            'tax_name' => 'required|string|max:255',
            'tax_amount_type' => 'required|in:percentage,fixed',
            'tax_amount' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|string|max:255',
            'additional_tax_name' => 'nullable|string|max:255',
            'additional_tax_amount_type' => 'required|in:percentage,fixed',
            'additional_tax_amount' => 'nullable|numeric|min:0',
            'additional_tax_type' => 'nullable|string|max:255',
        ]);

        // Create or update the billing settings
        $billingSetting = BillingSetting::updateOrCreate(
            ['id' => 1], // Assuming you're updating an existing record with ID 1 or creating a new record
            [
                'enable_tax' => $request->has('enable_tax') ? true : false,
                'tax_name' => $request->input('tax_name'),
                'tax_amount_type' => $request->input('tax_amount_type'),
                'tax_amount' => $request->input('tax_amount'),
                'tax_type' => $request->input('tax_type'),
                'enable_additional_tax' => $request->has('enable_additional_tax') ? true : false,
                'additional_tax_name' => $request->input('additional_tax_name'),
                'additional_tax_amount_type' => $request->input('additional_tax_amount_type'),
                'additional_tax_amount' => $request->input('additional_tax_amount'),
                'additional_tax_type' => $request->input('additional_tax_type'),
            ]
        );

        // Return success message
        return redirect()->back()->with('success', 'Tax settings saved successfully!');
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

    
    public function profile(){
        $userID = Auth::id();
        $user = User::find($userID);
        $country = Country::get();
        return view('setting.profile',compact('user','country'));
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        $user = Auth::user();
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->dob = $request->dob;
        $user->country = $request->country;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
    
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function homeSetting(){
        $banners = HomeCms::where('type', 'banner')->get();
        $exam = HomeCms::where('type', 'exam')->first();
        $help = HomeCms::where('type', 'help')->first();
        $whyus = HomeCms::where('type', 'whyus')->first();
        $faq = HomeCms::where('type', 'faq')->first();
        $resource = HomeCms::where('type', 'resource')->first();
        $getStarted = HomeCms::where('type', 'started')->first();
        $youtube = HomeCms::where('type', 'youtube')->first();
        return view('setting.homepage-setting',compact('banners','exam','help','whyus','faq','resource','getStarted','youtube'));
    }

    public function updateGetstarted(Request $request){
        // Validate the request input
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'button_text' => 'required',
            'button_link' => 'required',
        ]);

        // Find the existing started entry or create a new one
        $started = HomeCms::where('type', 'started')->first();

        // If no existing started is found, create a new one
        if (!$started) {
            $started = new HomeCms();
            $started->type = 'started'; // Set the type if necessary
        }

        // Update the started record
        $started->title = $request->input('title');
        $started->description = $request->input('description');

        // Update button text and link, convert to JSON
        $started->button_text = $request->input('button_text');
        $started->button_link = $request->input('button_link');

        // Save the started record
        $started->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Get started saved successfully!');
    }

    public function updateResource(Request $request){
        // Validate the request input
        $request->validate([
            'title' => 'required|max:300',
            'button_text' => 'required',
            'button_link' => 'required',
        ]);

        // Find the existing resource entry or create a new one
        $resource = HomeCms::where('type', 'resource')->first();

        // If no existing resource is found, create a new one
        if (!$resource) {
            $resource = new HomeCms();
            $resource->type = 'resource'; // Set the type if necessary
        }

        // Update the resource record
        $resource->title = $request->input('title');

        // Update button text and link, convert to JSON
        $resource->button_text = $request->input('button_text');
        $resource->button_link = $request->input('button_link');

        // Save the resource record
        $resource->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Resource saved successfully!');
    }


    public function updateFaq(Request $request){
        // Validate the request input
        $request->validate([
            'title' => 'required|max:300',
            'button_text' => 'required',
            'button_link' => 'required',
        ]);

        // Find the existing faq entry or create a new one
        $faq = HomeCms::where('type', 'faq')->first();

        // If no existing faq is found, create a new one
        if (!$faq) {
            $faq = new HomeCms();
            $faq->type = 'faq'; // Set the type if necessary
        }

        // Update the faq record
        $faq->title = $request->input('title');

        // Update button text and link, convert to JSON
        $faq->button_text = $request->input('button_text');
        $faq->button_link = $request->input('button_link');

        // Save the faq record
        $faq->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'FAQ saved successfully!');
    }

    public function updateWhyus(Request $request) {
        $request->validate([
            'title' => 'required|max:300',
            'points' => 'required|array',
        ]);
    
        // Find the existing whyus entry or create a new one
        $whyus = HomeCms::where('type', 'whyus')->first();
    
        // If no existing whyus is found, create a new one
        if (!$whyus) {
            $whyus = new HomeCms();
            $whyus->type = 'whyus'; // Set the type if necessary
        }
    
        // Update the whyus record
        $whyus->title = $request->input('title');
        $whyus->extra = json_encode($request->input('points'));
        $whyus->status = $request->input('status');
        $whyus->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Why Us saved successfully!');
    }

    // public function updateHelp(Request $request){
    //     // Validate the request input
    //     $request->validate([
    //         'title' => 'required|max:255',
    //         'card_title' => 'required|array',
    //         'card_description' => 'required|array',
    //     ]);

    //     // Find the existing help entry
    //     $help = HomeCms::where('type', 'help')->first();

    //     // If no existing help is found, create a new one
    //     if (!$help) {
    //         $help = new HomeCms();
    //         $help->type = 'help'; // Set the type if necessary
    //     }

    //     // Update the help record
    //     $help->title = $request->input('title');

    //     // Prepare data for card titles and descriptions
    //     $cards = [];
    //     foreach ($request->input('card_title') as $key => $title) {
    //         $cards[] = [
    //             'title' => $title,
    //             'description' => $request->input('card_description')[$key],
    //         ];
    //     }

    //     // Store the data in JSON format
    //     $help->extra = json_encode($cards);
    //     $help->save();

    //     // Redirect back with a success message
    //     return redirect()->back()->with('success', 'Help saved successfully!');
    // }

    public function updateHelp(Request $request)
    {
        // Validate the request input
        $request->validate([
            'title' => 'required|max:255',
            'card_title' => 'required|array',
            'card_description' => 'required|array',
            'card_status' => 'required|array',
            'card_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image files
        ]);

        // Find the existing help entry
        $help = HomeCms::where('type', 'help')->first();

        // If no existing help is found, create a new one
        if (!$help) {
            $help = new HomeCms();
            $help->type = 'help'; // Set the type if necessary
        }

        // Update the help record
        $help->title = $request->input('title');

        // Prepare data for card titles, descriptions, and images
        $cards = [];
        foreach ($request->input('card_title') as $key => $title) {
            $card = [
                'title' => $title,
                'description' => $request->input('card_description')[$key],
                'status' => $request->input('card_status')[$key],
            ];
            
            // Handle image upload
            if ($request->hasFile("card_image.$key")) {

                $image = $request->file("card_image.$key");
    
                // Generate a unique filename with the current timestamp
                $imageName = 'helping_' . time() . '_' . $key . '.' . $image->getClientOriginalExtension();
    
                // Move the image to the 'public/banners' directory
                $image->move(public_path('banners'), $imageName);
    
                // Store the image path in the database (relative to the public directory)
                $imagePath = $imageName;
    
                // Save the complete URL in the database
                $completeImageUrl = env('APP_URL') . '/banners/' . $imagePath; // Create URL based on public path
                
                // Save the image URL to the banner
                $card['image'] = $completeImageUrl;
            } else {
                // Keep the existing image if no new one was uploaded
                $existingData = json_decode($help->extra ?? null);
                if (isset($existingData[$key]->image)) {
                    $card['image'] = $existingData[$key]->image;
                }
            }

            $cards[] = $card;
        }

        // Store the data in JSON format
        $help->extra = json_encode($cards);
        $help->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Help saved successfully!');
    }


    public function updateExam(Request $request)
    {
        // Validate the request input
        $request->validate([
            'title' => 'required|max:300',
            'button_text' => 'required',
            'button_link' => 'required',
        ]);
    
        // Find the existing exam entry or create a new one
        $exam = HomeCms::where('type', 'exam')->first();
    
        // If no existing exam is found, create a new one
        if (!$exam) {
            $exam = new HomeCms();
            $exam->type = 'exam'; // Set the type if necessary
        }
    
        // Update the exam record
        $exam->title = $request->input('title');
    
        // Update button text and link, convert to JSON
        $exam->button_text = $request->input('button_text');
        $exam->button_link = $request->input('button_link');
    
        // Save the exam record
        $exam->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Exam saved successfully!');
    }

    public function updateBanner(Request $request)
    {
        // Get the existing banners from the database
        $existingBanners = HomeCms::where('type', 'banner')->pluck('id')->toArray();

        // Loop through the incoming titles to save or update each banner
        foreach ($request->title as $index => $title) {
            // Get the ID for the current slider (if it exists)
            $bannerId = $request->banner_id[$index] ?? null;

            // Save or update the banner
            $banner = HomeCms::updateOrCreate(
                ['id' => $bannerId], // Use existing ID to update
                [
                    'title' => $title,
                    'description' => $request->short_description[$index],
                    'button_text' => $request->button_text[$index],
                    'button_link' => $request->button_link[$index],
                    'type' => 'banner',
                    'status' => 1, // Default status, you can adjust as needed
                ]
            );

            // Handle image upload
            if ($request->hasFile("banner_img.$index")) {

                $image = $request->file("banner_img.$index");
    
                // Generate a unique filename with the current timestamp
                $imageName = 'banner_' . time() . '_' . $index . '.' . $image->getClientOriginalExtension();
    
                // Move the image to the 'public/banners' directory
                $image->move(public_path('banners'), $imageName);
    
                // Store the image path in the database (relative to the public directory)
                $imagePath = $imageName;
    
                // Save the complete URL in the database
                $completeImageUrl = env('APP_URL') . '/banners/' . $imagePath; // Create URL based on public path
                
                // Save the image URL to the banner
                $banner->image = $completeImageUrl;
                $banner->save();
            }
        }

        // Now, check which banners to delete
        $submittedBannerIds = array_filter($request->banner_id); // Get only non-null IDs from the request
        $bannersToDelete = array_diff($existingBanners, $submittedBannerIds); // Identify IDs that are not in the request

        // Delete banners that were not submitted in the request
        if (!empty($bannersToDelete)) {
            HomeCms::whereIn('id', $bannersToDelete)->delete();
        }

        return redirect()->back()->with('success', 'Banners updated successfully!');
    }

    // ABOUT PAGE
    public function aboutSetting(){
        $mission = HomeCms::where('type', 'mission')->first();
        $vision = HomeCms::where('type', 'vision')->first();
        $values = HomeCms::where('type', 'values')->first();
        $strategy = HomeCms::where('type', 'strategy')->first();
        $operate = HomeCms::where('type', 'operate')->first();
        $bestData = HomeCms::where('type', 'bestData')->first();
        return view('setting.aboutpage-setting',compact('mission','vision','values','strategy','operate','bestData'));
    }

    public function updateYoutubeVideo(Request $request){
        $request->validate([
            'youtube_link' => 'required|max:300'
        ]);

        // Find the existing mission entry or create a new one
        $youtube = HomeCms::where('type', 'youtube')->first() ?: new HomeCms(['type' => 'youtube']);
        
        // Update fields
        $youtube->title = "Youtube";
        $youtube->description = $request->input('youtube_link');
        $youtube->save();

        return redirect()->back()->with('success', 'Youtube Url saved successfully!');
    }

    // Update Mission Section
    public function updateMission(Request $request)
    {
        $request->validate([
            'title' => 'required|max:300',
            'image' => 'nullable|image|max:2048', // Maximum image size 2MB
            'description' => 'required|string|max:1000',
        ]);

        // Find the existing mission entry or create a new one
        $mission = HomeCms::where('type', 'mission')->first() ?: new HomeCms(['type' => 'mission']);

        // Update fields
        $mission->title = $request->input('title');
        $mission->description = $request->input('description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename with the current timestamp
            $imageName = 'image_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/setting' directory
            $image->move(public_path('setting'), $imageName);
            // Store the complete URL in the database
            $imagePath = env('APP_URL') . '/setting/' . $imageName;
            $mission->image = $imagePath;
        }

        $mission->save();

        return redirect()->back()->with('success', 'Mission saved successfully!');
    }

    // Update Vision Section
    public function updateVision(Request $request)
    {
        $request->validate([
            'title' => 'required|max:300',
            'description' => 'required|string|max:1000',
        ]);

        $vision = HomeCms::where('type', 'vision')->first() ?: new HomeCms(['type' => 'vision']);
        $vision->title = $request->input('title');
        $vision->description = $request->input('description');
        $vision->save();

        return redirect()->back()->with('success', 'Vision saved successfully!');
    }

    // Update Values Section
    public function updateValues(Request $request)
    {
        $request->validate([
            'title' => 'required|max:300',
            'image' => 'nullable|image|max:2048', // Maximum image size 2MB
            'description' => 'required|string|max:1000',
        ]);

        $values = HomeCms::where('type', 'values')->first() ?: new HomeCms(['type' => 'values']);
        $values->title = $request->input('title');
        $values->description = $request->input('description');

       if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename with the current timestamp
            $imageName = 'image_' . time() . '.' . $image->getClientOriginalExtension();
            // Move the image to the 'public/setting' directory
            $image->move(public_path('setting'), $imageName);
            // Store the complete URL in the database
            $imagePath = env('APP_URL') . '/setting/' . $imageName;
            $values->image = $imagePath;
        }

        $values->save();

        return redirect()->back()->with('success', 'Values saved successfully!');
    }

    // Update Strategy Section
    public function updateStrategy(Request $request)
    {
        $request->validate([
            'title' => 'required|max:300',
            'description' => 'required|string|max:1000',
        ]);

        $strategy = HomeCms::where('type', 'strategy')->first() ?: new HomeCms(['type' => 'strategy']);
        $strategy->title = $request->input('title');
        $strategy->description = $request->input('description');
        $strategy->save();

        return redirect()->back()->with('success', 'Strategy saved successfully!');
    }

    // Update How We Operate Section
    public function updateOperate(Request $request)
    {
        $request->validate([
            'title' => 'required|max:300',
            'description' => 'required|string|max:2000',
        ]);

        $howWeOperate = HomeCms::where('type', 'operate')->first() ?: new HomeCms(['type' => 'operate']);
        $howWeOperate->title = $request->input('title');
        $howWeOperate->description = $request->input('description');
        $howWeOperate->save();

        return redirect()->back()->with('success', 'How We Operate saved successfully!');
    }

    // Update Best Data Section
    public function updateBestData(Request $request)
    {
        $request->validate([
            'title' => 'required|max:300',
            'description' => 'required|string|max:1000',
        ]);

        // Find the existing best data entry or create a new one
        $bestData = HomeCms::where('type', 'bestData')->first() ?: new HomeCms(['type' => 'bestData']);

        // Update fields
        $bestData->title = $request->input('title');
        $bestData->description = $request->input('description');
        $bestData->save();

        return redirect()->back()->with('success', 'Best Data saved successfully!');
    }


    public function enquiry(Request $request){
        if ($request->ajax()) {
            $sections = Enquiry::with('course')->latest();

            return DataTables::of($sections)
                ->addIndexColumn()
                ->addColumn('action', function ($section) {
                    $parms = "id=".$section->id;
                    $deleteUrl = encrypturl(route('delete-enquiry'),$parms);
                    return '<button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
                })
                ->addColumn('created_at', function($row) {
                    return date('d/m/Y', strtotime($row->created_at));
                })
                ->addColumn('course', function($row) {
                    if(isset($row->course)){
                        return $row->course->name;
                    }
                    return "----";
                })
                ->rawColumns(['course','created_at','action'])
                ->make(true);
        }
        return view('setting.enquiry');
    }


    public function deleteEnquiry(Request $request){
        $request->validate([
            'eq'=>'required'
        ]);

        $data = decrypturl($request->eq);
        $skillId = $data['id'];
        $user = Enquiry::where('id',$skillId)->first();
        if($user){
            $user->delete();
            return redirect()->back()->with('success','Enquiry Removed Successfully');
        }
        return redirect()->back()->with('error','Something Went Wrong');
    }


}
