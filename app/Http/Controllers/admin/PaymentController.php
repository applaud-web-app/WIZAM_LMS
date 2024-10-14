<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Plan;
use Stripe\Product;
use Stripe\Price;
use App\Models\GeneralSetting;
use Laravel\Cashier\Cashier;
use Stripe\Webhook;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PaymentController extends Controller
{
   public function viewPlans(Request $request){
         if ($request->ajax()) {
            $sections = Plan::with('category')->whereIn('status',[0,1]);

            return DataTables::of($sections)
               ->addIndexColumn()
               ->addColumn('action', function ($section) {
                  $parms = "id=".$section->id;
                  $editUrl = encrypturl(route('edit-plan'),$parms);
                  $deleteUrl = encrypturl(route('delete-plan'),$parms);

                  return '<a href="'.$editUrl.'" type="button" class="cursor-pointer edit-task-title uil uil-edit-alt hover:text-info" ></a>
                        <button type="button" data-url="'.$deleteUrl.'" class="deleteItem cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"  data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light"></button>';
               })
               ->addColumn('category', function($row) {
                  if (isset($row->category)) {
                     return $row->category->name;
                  }
                  return "----";
               })
               ->addColumn('status', function($row) {
                  // Determine the status color and text based on `is_active`
                  $statusColor = $row->status == 1 ? 'success' : 'danger';
                  $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                  // Create the status badge HTML
                  return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
               })
               ->rawColumns(['status','category','action'])
               ->make(true);
      }
      return view('managePayment.plans.view-plans');
   }

   public function createPlan(){
      $subCategory = SubCategory::whereIn('status',[1,0])->get();
      return view('managePayment.plans.create-plans',compact('subCategory'));
   }

   // public function savePlan(Request $request){

   //    // Set the Stripe API key
   //    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

   //    // Validate incoming request data
   //    $validatedData = $request->validate([
   //       'category' => 'required|string',
   //       'plan_name' => 'required|string',
   //       'price_type' => 'required|string',
   //       'duration' => 'nullable|integer', 
   //       'price' => 'required|numeric',
   //       'discount'=>'required|numeric',
   //       'discount_percentage' => 'nullable|integer',
   //       'description' => 'nullable|string',
   //       'order' => 'nullable|integer',
   //       'feature_access' => 'required|boolean',
   //       'features.*' => 'nullable|string',
   //       'popular' => 'required|string|in:1,0',
   //       'status' => 'required|string|in:1,0',
   //    ]);

   //    // Ensure features is always an array
   //    if (!empty($validatedData['features'])) {
   //       $validatedData['features'] = json_encode(array_slice($validatedData['features'], 1));
   //    } else {
   //       $validatedData['features'] = []; // Use an empty array if no features are provided
   //    }

   //    $generalSetting = GeneralSetting::select('currency')->first(); // LIke - USD,INR,EURO ---- soom

   //    // Create a new product in Stripe
   //    try {
   //       $product = Product::create([
   //          'name' => $validatedData['plan_name'],
   //          'description' => $validatedData['description'] ?? '',
   //          'type' => 'service', // Change to 'good' if applicable
   //       ]);

   //       // Calculate the price considering any discounts
   //       $basePrice = $validatedData['price'] * 100; // Convert to cents
   //       if ($validatedData['discount'] > 0) {
   //          $basePrice -= $validatedData['discount'] * 100; // Convert discount to cents
   //       }
   //       if ($validatedData['discount_percentage'] > 0) {
   //          $basePrice -= ($basePrice * ($validatedData['discount_percentage'] / 100));
   //       }

   //       // Ensure the price is not negative
   //       $finalPrice = max(0, $basePrice);

   //       // Create a price in Stripe
   //       $price = Price::create([
   //          'unit_amount' => $finalPrice,
   //          'currency' => 'usd', // Change this if you're using a different currency
   //          'recurring' => [
   //             'interval' => $validatedData['duration'] ? 'month' : 'year', // Adjust based on duration
   //          ],
   //          'product' => $product->id,
   //       ]);

   //    } catch (\Exception $e) {
   //          // Handle any errors from Stripe API
   //          return redirect()->route('view-plans')->with('error', 'Failed to create plan in Stripe: ' . $e->getMessage());
   //    }

   //    // Create a new instance of your model and fill it with the validated data
   //    $data = new Plan;
   //    $data->category_id = $validatedData['category'] ?? null;
   //    $data->name = $validatedData['plan_name'] ?? null;
   //    $data->price_type = $validatedData['price_type'] ?? null; // MONTHLY // FIXED
   //    $data->duration = $validatedData['duration'] ?? null;
   //    $data->price = $validatedData['price'] ?? null;
   //    $data->discount = $validatedData['discount'] ?? 0;
   //    $data->discount_percentage = $validatedData['discount_percentage'] ?? null;
   //    $data->description = $validatedData['description'] ?? null;
   //    $data->sort_order = $validatedData['order'] ?? null;
   //    $data->feature_access = $validatedData['feature_access'] ?? null;
   //    $data->features = $validatedData['features'];
   //    $data->popular = $validatedData['popular'] ?? null; 
   //    $data->status = $validatedData['status'] ?? null;
      
   //    // Save the Stripe IDs in your local database
   //    $data->stripe_product_id = $product->id; // Add this field in your Plan model and migration
   //    $data->stripe_price_id = $price->id; // Add this field in your Plan model and migration
      
   //    $data->save();

   //    // Return a success response
   //    return redirect()->route('view-plans')->with('success', 'Plan Created Successfully');
   // }

   // public function savePlan(Request $request)
   // {
   //    // Set the Stripe API key
   //    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

   //    // Validate incoming request data
   //    $validatedData = $request->validate([
   //       'category' => 'required|string',
   //       'plan_name' => 'required|string',
   //       'price_type' => 'required|string',
   //       'duration' => 'nullable|integer',
   //       'price' => 'required|numeric',
   //       'discount' => 'required|numeric',
   //       'discount_percentage' => 'nullable|integer',
   //       'description' => 'nullable|string',
   //       'order' => 'nullable|integer',
   //       'feature_access' => 'required|boolean',
   //       'features.*' => 'nullable|string',
   //       'popular' => 'required|string|in:1,0',
   //       'status' => 'required|string|in:1,0',
   //    ]);

   //    // Ensure features is always an array
   //    if (!empty($validatedData['features'])) {
   //       $validatedData['features'] = json_encode(array_slice($validatedData['features'], 1));
   //    } else {
   //       $validatedData['features'] = json_encode([]); // Use an empty array if no features are provided
   //    }

   //    // Fetch the currency setting (assuming a single currency for now)
   //    $generalSetting = GeneralSetting::select('currency')->first();
   //    $currency = $generalSetting ? $generalSetting->currency : 'usd'; // Default to 'usd' if not set

   //    // Create a new product in Stripe
   //    try {
   //       $product = Product::create([
   //             'name' => $validatedData['plan_name'],
   //             'description' => $validatedData['description'] ?? '',
   //             'type' => 'service', // Change to 'good' if applicable
   //       ]);

   //       // Calculate the price considering any discounts
   //       $basePrice = $validatedData['price'] * 100; // Convert to cents
   //       if ($validatedData['discount'] > 0) {
   //             $basePrice -= $validatedData['discount'] * 100; // Convert discount to cents
   //       }
   //       if ($validatedData['discount_percentage'] > 0) {
   //             $basePrice -= ($basePrice * ($validatedData['discount_percentage'] / 100));
   //       }

   //       // Ensure the price is not negative
   //       $finalPrice = max(0, $basePrice);

   //       // $validatedData['price_type'] ?? null; // [MONTHLY,FIXED] // IF PLAN IS FIXED THEN IT ONE TIME PAYMENT NOT RECCURING

   //       // Create a price in Stripe
   //       $price = Price::create([
   //          'unit_amount' => $finalPrice,
   //          'currency' => $currency, // Use the currency from the general settings
   //          'recurring' => [
   //             'interval' => $validatedData['duration'] ? 'month' : 'year', // Adjust based on duration
   //          ],
   //          'product' => $product->id,
   //       ]);
   //    } catch (\Exception $e) {
   //       // Handle any errors from the Stripe API
   //       return redirect()->route('view-plans')->with('error', 'Failed to create plan in Stripe: ' . $e->getMessage());
   //    }

   //    // Create a new instance of your model and fill it with the validated data
   //    $data = new Plan();
   //    $data->category_id = $validatedData['category'] ?? null;
   //    $data->name = $validatedData['plan_name'] ?? null;
   //    $data->price_type = $validatedData['price_type'] ?? null; // MONTHLY // FIXED
   //    $data->duration = $validatedData['duration'] ?? null;
   //    $data->price = $validatedData['price'] ?? null;
   //    $data->discount = $validatedData['discount'] ?? 0;
   //    $data->discount_percentage = $validatedData['discount_percentage'] ?? null;
   //    $data->description = $validatedData['description'] ?? null;
   //    $data->sort_order = $validatedData['order'] ?? null;
   //    $data->feature_access = $validatedData['feature_access'] ?? null;
   //    $data->features = $validatedData['features'];
   //    $data->popular = $validatedData['popular'] ?? null; 
   //    $data->status = $validatedData['status'] ?? null;

   //    // Save the Stripe IDs in your local database
   //    $data->stripe_product_id = $product->id;
   //    $data->stripe_price_id = $price->id;

   //    $data->save();

   //    // Return a success response
   //    return redirect()->route('view-plans')->with('success', 'Plan Created Successfully');
   // }

   // public function savePlan(Request $request)
   // {
   //    // Set the Stripe API key
   //    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
   
   //    // Validate incoming request data
   //    $validatedData = $request->validate([
   //       'category' => 'required|string',
   //       'plan_name' => 'required|string',
   //       'price_type' => 'required|string|in:monthly,fixed', // Ensure these values are specified
   //       'duration' => 'nullable|integer',
   //       'price' => 'required|numeric',
   //       'discount' => 'required|numeric',
   //       'discount_percentage' => 'nullable|integer',
   //       'description' => 'nullable|string',
   //       'order' => 'nullable|integer',
   //       'feature_access' => 'required|boolean',
   //       'features.*' => 'nullable|string',
   //       'popular' => 'required|string|in:1,0',
   //       'status' => 'required|string|in:1,0',
   //    ]);
   
   //    // Ensure features is always an array
   //    if (!empty($validatedData['features'])) {
   //       $validatedData['features'] = json_encode(array_slice($validatedData['features'], 1));
   //    } else {
   //       $validatedData['features'] = json_encode([]); // Use an empty array if no features are provided
   //    }
   
   //    // Fetch the currency setting (assuming a single currency for now)
   //    $generalSetting = GeneralSetting::select('currency')->first();
   //    $currency = $generalSetting ? $generalSetting->currency : 'usd'; // Default to 'usd' if not set
   
   //    // Create a new product in Stripe
   //    try {
   //       $product = Product::create([
   //          'name' => $validatedData['plan_name'],
   //          'description' => $validatedData['description'] ?? '',
   //          'type' => 'service', // Change to 'good' if applicable
   //       ]);

   //       // Calculate the price considering any discounts
   //       $basePrice = $validatedData['price'] * 100; // Convert to cents  : eg - 10000

   //       // Apply fixed discount first
   //       if ($validatedData['discount'] == 1 && $validatedData['discount_percentage'] > 0) {
   //          $percentageDiscount = ($basePrice * ($validatedData['discount_percentage'] / 100));
   //          $basePrice -= $percentageDiscount; // Subtract percentage discount
   //       }

   //       // Ensure the price is not negative
   //       $finalPrice = max(0, $basePrice);

   //       // Create a price in Stripe based on the price type
   //       $stripePriceData = [
   //          'unit_amount' => $finalPrice,
   //          'currency' => $currency, // Use the currency from the general settings
   //          'product' => $product->id,
   //       ];

   //       // Set recurring interval if the plan is monthly
   //       if ($validatedData['price_type'] == 'monthly' && $validatedData['duration']) {
   //          $duration_month = $validatedData['duration'];
   //          $stripePriceData['recurring'] = [
   //                'interval' => 'month', // monthly recurrence
   //          ];
   //       } elseif ($validatedData['price_type'] === 'fixed') {
   //          // One-time payment, no recurring setting required
   //       } else {
   //          // Handle cases where the duration is not set correctly for monthly plans
   //          throw new \Exception('Duration must be specified for monthly plans.');
   //       }

   //       // Create a price in Stripe
   //       $price = Price::create($stripePriceData);
   //    } catch (\Exception $e) {
   //       // Handle any errors from the Stripe API
   //       return redirect()->route('view-plans')->with('error', 'Failed to create plan in Stripe: ' . $e->getMessage());
   //    }
   
   //    // Create a new instance of your model and fill it with the validated data
   //    $data = new Plan();
   //    $data->category_id = $validatedData['category'] ?? null;
   //    $data->name = $validatedData['plan_name'] ?? null;
   //    $data->price_type = $validatedData['price_type'] ?? null; // monthly or fixed
   //    $data->duration = $validatedData['duration'] ?? null;
   //    $data->price = $validatedData['price'] ?? null;
   //    $data->discount = $validatedData['discount'] ?? 0;
   //    $data->discount_percentage = $validatedData['discount_percentage'] ?? null;
   //    $data->description = $validatedData['description'] ?? null;
   //    $data->sort_order = $validatedData['order'] ?? null;
   //    $data->feature_access = $validatedData['feature_access'] ?? null;
   //    $data->features = $validatedData['features'];
   //    $data->popular = $validatedData['popular'] ?? null;
   //    $data->status = $validatedData['status'] ?? null;
   
   //    // Save the Stripe IDs in your local database
   //    $data->stripe_product_id = $product->id;
   //    $data->stripe_price_id = $price->id;

   //    $data->save();

   //    // Return a success response
   //    return redirect()->route('view-plans')->with('success', 'Plan Created Successfully');
   // }

   public function savePlan(Request $request)
   {
      // Set the Stripe API key
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

      // Validate incoming request data
      $validatedData = $request->validate([
         'category' => 'required|string',
         'plan_name' => 'required|string',
         'price_type' => 'required|string|in:monthly,fixed', // Ensure valid values
         'duration' => 'nullable|integer', // This will be the count of months
         'price' => 'required|numeric',
         'discount' => 'required|numeric',
         'discount_percentage' => 'nullable|integer',
         'description' => 'nullable|string',
         'order' => 'nullable|integer',
         'feature_access' => 'required|boolean',
         'features.*' => 'nullable|string',
         'popular' => 'required|string|in:1,0',
         'status' => 'required|string|in:1,0',
      ]);

      // Ensure features are always an array
      if (!empty($validatedData['features'])) {
         $validatedData['features'] = json_encode(array_slice($validatedData['features'], 1));
      } else {
         $validatedData['features'] = json_encode([]); // Use an empty array if no features are provided
      }

      // Fetch the currency setting (assuming a single currency for now)
      $generalSetting = GeneralSetting::select('currency')->first();
      $currency = $generalSetting ? $generalSetting->currency : 'usd'; // Default to 'usd' if not set

      // Create a new product in Stripe
      try {
         $product = Product::create([
               'name' => $validatedData['plan_name'],
               'description' => $validatedData['description'] ?? '',
               'type' => 'service', // Change to 'good' if applicable
         ]);

         // Calculate the price considering any discounts
         $basePrice = $validatedData['price'] * 100; // Convert to cents

         // Apply percentage discount if applicable
         if ($validatedData['discount_percentage'] > 0) {
               $percentageDiscount = ($basePrice * ($validatedData['discount_percentage'] / 100));
               $basePrice -= $percentageDiscount; // Subtract percentage discount
         }

         // Ensure the price is not negative
         $finalPrice = max(0, $basePrice);

         // Prepare the Stripe price creation data
         $stripePriceData = [
               'unit_amount' => $finalPrice,
               'currency' => $currency, // Use the currency from the general settings
               'product' => $product->id,
         ];

         // Set recurring interval if the plan is monthly
         if ($validatedData['price_type'] == 'monthly') {
               if ($validatedData['duration']) {
                  $stripePriceData['recurring'] = [
                     'interval' => 'month', // monthly recurrence
                     'interval_count' => $validatedData['duration'], // Pass the duration as the count of months
                  ];
               } else {
                  throw new \Exception('Duration must be specified for monthly plans.');
               }
         } 
         // For 'fixed' price type, it's a one-time payment so no recurring setting is required

         // Create a price in Stripe
         $price = Price::create($stripePriceData);
      } catch (\Exception $e) {
         // Handle any errors from the Stripe API
         return redirect()->route('view-plans')->with('error', 'Failed to create plan in Stripe: ' . $e->getMessage());
      }

      // Create a new instance of your model and fill it with the validated data
      $data = new Plan();
      $data->category_id = $validatedData['category'] ?? null;
      $data->name = $validatedData['plan_name'] ?? null;
      $data->price_type = $validatedData['price_type'] ?? null; // monthly or fixed
      $data->duration = $validatedData['duration'] ?? null;
      $data->price = $validatedData['price'] ?? null;
      $data->discount = $validatedData['discount'] ?? 0;
      $data->discount_percentage = $validatedData['discount_percentage'] ?? null;
      $data->description = $validatedData['description'] ?? null;
      $data->sort_order = $validatedData['order'] ?? null;
      $data->feature_access = $validatedData['feature_access'] ?? null;
      $data->features = $validatedData['features'];
      $data->popular = $validatedData['popular'] ?? null;
      $data->status = $validatedData['status'] ?? null;

      // Save the Stripe IDs in your local database
      $data->stripe_product_id = $product->id;
      $data->stripe_price_id = $price->id;

      $data->save();

      // Return a success response
      return redirect()->route('view-plans')->with('success', 'Plan Created Successfully');
   }


   public function editPlan(Request $request){
      $request->validate([
         'eq'=>'required'
      ]);

      $data = decrypturl($request->eq);
      $plan_id = $data['id'];
      $plan = Plan::where('id',$plan_id)->first();
      if($plan){
         $subCategory = SubCategory::whereIn('status',[1,0])->get();
         return view('managePayment.plans.edit-plans',compact('subCategory','plan'));
      }
      return redirect()->back()->with('error','Invalid Plan');
   }

   // public function updatePlan(Request $request){
   //    $validatedData = $request->validate([
   //       'category' => 'required|string',
   //       'plan_name' => 'required|string',
   //       'price_type' => 'required|string',
   //       'duration' => 'nullable|integer', // Assuming duration is optional
   //       'price' => 'required|numeric',
   //       'discount'=>'required',
   //       'discount_percentage' => 'nullable|integer',
   //       'description' => 'nullable|string',
   //       'order' => 'nullable|integer',
   //       'feature_access' => 'required|boolean',
   //       // 'features' => 'array', 
   //       'features.*' => 'nullable|string',
   //       'popular' => 'required|string|in:1,0',
   //       'status' => 'required|string|in:1,0',
   //       'eq'=>'required'
   //    ]);

   //    // Ensure features is always an array
   //    if (!empty($validatedData['features'])) {
   //       $validatedData['features'] = json_encode(array_slice($validatedData['features'], 1));
   //    } else {
   //       $validatedData['features'] = []; // Use an empty array if no features are provided
   //    }

   //    // Create a new instance of your model and fill it with the validated data
   //    $data = decrypturl($request->eq);
   //    $plan_id = $data['id'];
   //    $data = Plan::where('id',$plan_id)->first();
   //    if($data){
   //       $data->category_id = $validatedData['category'];
   //       $data->name = $validatedData['plan_name'];
   //       $data->price_type = $validatedData['price_type'];  // MONTHLY // FIXED
   //       $data->duration = $validatedData['duration'];
   //       $data->price = $validatedData['price'];
   //       $data->discount = $validatedData['discount'];
   //       $data->discount_percentage = $validatedData['discount_percentage'];
   //       $data->description = $validatedData['description'];
   //       $data->sort_order = $validatedData['order'];
   //       $data->feature_access = $validatedData['feature_access'];
   //       $data->features = $validatedData['features'];
   //       $data->popular = $validatedData['popular']; 
   //       $data->status = $validatedData['status'];
   //       $data->save();

   //       // Return a success response
   //      return redirect()->route('view-plans')->with('success','Plan Updated Successfully');
   //    }
   //    return redirect()->back()->with('error','Something Went Wrong');
   // }

   public function updatePlan(Request $request)
   {
      // Set the Stripe API key
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

      // Validate the incoming request data
      $validatedData = $request->validate([
         'category' => 'required|string',
         'plan_name' => 'required|string',
         'price_type' => 'required|string|in:monthly,fixed', // Ensure valid values
         'duration' => 'nullable|integer', // Assuming duration is optional
         'price' => 'required|numeric',
         'discount' => 'required|numeric',
         'discount_percentage' => 'nullable|integer',
         'description' => 'nullable|string',
         'order' => 'nullable|integer',
         'feature_access' => 'required|boolean',
         'features.*' => 'nullable|string',
         'popular' => 'required|string|in:1,0',
         'status' => 'required|string|in:1,0',
         'eq' => 'required'
      ]);

      // Ensure features is always an array
      if (!empty($validatedData['features'])) {
         $validatedData['features'] = json_encode(array_slice($validatedData['features'], 1));
      } else {
         $validatedData['features'] = json_encode([]); // Use an empty array if no features are provided
      }

      // Decrypt the plan ID
      $data = decrypturl($request->eq);
      $plan_id = $data['id'];

      // Fetch the existing plan from the database
      $data = Plan::where('id', $plan_id)->first();

      if ($data) {
         // Fetch the Stripe product ID and price ID from the plan
         $stripeProductId = $data->stripe_product_id;
         $stripePriceId = $data->stripe_price_id;

         // Fetch the currency setting (assuming a single currency for now)
         $generalSetting = GeneralSetting::select('currency')->first();
         $currency = $generalSetting ? $generalSetting->currency : 'usd'; // Default to 'usd' if not set

         try {
               // Update the product in Stripe (name and description only)
               $product = Product::update($stripeProductId, [
                  'name' => $validatedData['plan_name'],
                  'description' => $validatedData['description'] ?? '',
               ]);

               // Check if the price, discount, discount_percentage, or duration has changed
               $priceChanged = $validatedData['price'] != $data->price ||
                              $validatedData['discount'] != $data->discount ||
                              $validatedData['discount_percentage'] != $data->discount_percentage ||
                              $validatedData['price_type'] != $data->price_type ||
                              $validatedData['duration'] != $data->duration;

               // If price or duration changed, update the price in Stripe
               if ($priceChanged) {
                  // Recalculate the price considering any discounts
                  $basePrice = $validatedData['price'] * 100; // Convert to cents

                  // Apply percentage discount if applicable
                  if ($validatedData['discount_percentage'] > 0) {
                     $percentageDiscount = ($basePrice * ($validatedData['discount_percentage'] / 100));
                     $basePrice -= $percentageDiscount; // Subtract percentage discount
                  }

                  // Ensure the price is not negative
                  $finalPrice = max(0, $basePrice);

                  // Prepare the Stripe price data for update
                  $stripePriceData = [
                     'unit_amount' => $finalPrice,
                     'currency' => $currency,
                     'product' => $product->id,
                  ];

                  // Handle recurring settings for monthly plans
                  if ($validatedData['price_type'] == 'monthly') {
                     if ($validatedData['duration']) {
                           $stripePriceData['recurring'] = [
                              'interval' => 'month',
                              'interval_count' => $validatedData['duration'], // Set the new duration in months
                           ];
                     } else {
                           throw new \Exception('Duration must be specified for monthly plans.');
                     }
                  }

                  // Deactivate the old price in Stripe
                  Price::update($stripePriceId, ['active' => false]);

                  // Create a new price in Stripe with updated details
                  $price = Price::create($stripePriceData);

                  // Save the new Stripe price ID
                  $data->stripe_price_id = $price->id;
               }

               // Update the plan in the database
               $data->category_id = $validatedData['category'];
               $data->name = $validatedData['plan_name'];
               $data->price_type = $validatedData['price_type'];  // monthly or fixed
               $data->duration = $validatedData['duration'];
               $data->price = $validatedData['price'];
               $data->discount = $validatedData['discount'];
               $data->discount_percentage = $validatedData['discount_percentage'];
               $data->description = $validatedData['description'];
               $data->sort_order = $validatedData['order'];
               $data->feature_access = $validatedData['feature_access'];
               $data->features = $validatedData['features'];
               $data->popular = $validatedData['popular'];
               $data->status = $validatedData['status'];

               // Save the updated plan in the database
               $data->save();

               // Return a success response
               return redirect()->route('view-plans')->with('success', 'Plan Updated Successfully');
         } catch (\Exception $e) {
               // Handle any Stripe API errors
               return redirect()->route('view-plans')->with('error', 'Failed to update plan in Stripe: ' . $e->getMessage());
         }
      }

      // If plan not found, return an error response
      return redirect()->back()->with('error', 'Plan not found or something went wrong.');
   }



   public function deletePlan(Request $request){
      $request->validate([
         'eq'=>'required'
      ]);

      $data = decrypturl($request->eq);
      $plan_id = $data['id'];
      $user = Plan::where('id',$plan_id)->first();
      if($user){
            $user->status = 2; // Delete
            $user->save();
            return redirect()->back()->with('success','Plan Removed Successfully');
      }
      return redirect()->back()->with('error','Something Went Wrong');
   }

   // // STRIPE WEBHOOK GATEWAY
   // public function handleWebhook(Request $request)
   // {
   //    $event = null;
   //    // Logging for debugging
   //    Log::info('Stripe Environment Variables', [
   //       'webhook_secret' => env('STRIPE_SIGNATURE_WEBHOOK'),
   //    ]);
   //    Log::info('Stripe Webhook Raw Payload', ['payload' => $request->getContent()]);
   //    Log::info('Stripe Webhook Headers', ['headers' => $request->headers->all()]);
   //    Log::info('Stripe Webhook Signature', ['stripe-signature' => $request->header('Stripe-Signature')]);
   //    try {
   //       // Validate the webhook signature
   //       $event = Webhook::constructEvent(
   //             $request->getContent(),
   //             $request->header('Stripe-Signature'),
   //             env('STRIPE_SIGNATURE_WEBHOOK')
   //       );
   //    } catch (\UnexpectedValueException $e) {
   //       Log::error('Stripe Webhook Error: Invalid payload', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid payload'], 400);
   //    } catch (\Stripe\Exception\SignatureVerificationException $e) {
   //       Log::error('Stripe Webhook Error: Invalid signature', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid signature'], 400);
   //    }
   //    // Handle specific event types
   //    try {
   //       switch ($event->type) {
   //          case 'payment_intent.succeeded':
   //             $this->storePaymentDetails($event->data->object);
   //             break;
   //          case 'invoice.payment_succeeded':
   //             $this->storeSubscriptionPaymentDetails($event->data->object);
   //             break;
   //          case 'customer.subscription.created':
   //                $this->storeSubscriptionDetails($event->data->object);
   //                break;
   //       }
   //       return response()->json(['status' => 'success']);
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: General error', [
   //             'error' => $e->getMessage(),
   //             'event_type' => $event->type
   //       ]);
   //       return response()->json(['error' => 'Failed to process event'], 500);
   //    }
   // }

   // protected function storePaymentDetails($paymentIntent)
   // {
   //    try {
   //       Payment::create([
   //          'user_id' => 11,
   //          'stripe_payment_id' => $paymentIntent->id,
   //          'amount' => $paymentIntent->amount_received / 100, // Convert from cents
   //          'currency' => $paymentIntent->currency,
   //          'status' => $paymentIntent->status,
   //       ]);
   //    } catch (\Exception $e) {
   //       // Log the error during payment storage
   //       Log::error('Stripe Webhook Error: Failed to store payment details', ['error' => $e->getMessage(), 'payment_intent' => $paymentIntent]);
   //    }
   // }

   // protected function storeSubscriptionPaymentDetails($invoice)
   // {
   //    try {
   //       $userId = $invoice->customer; // Assuming you store the Stripe customer ID in your user table
   //       Payment::create([
   //          'user_id' => 11,
   //          'stripe_payment_id' => $invoice->id,
   //          'amount' => $invoice->amount_paid / 100, // Convert from cents
   //          'currency' => $invoice->currency,
   //          'status' => $invoice->status,
   //          'subscription_id' => $invoice->subscription,
   //       ]);
   //    } catch (\Exception $e) {
   //       // Log the error during subscription payment storage
   //       Log::error('Stripe Webhook Error: Failed to store subscription payment details', ['error' => $e->getMessage(), 'invoice' => $invoice]);
   //    }
   // }

   // protected function storeSubscriptionDetails($subscription)
   // {
   //    try {
   //       Subscription::create([
   //             'user_id' => 11,
   //             'stripe_id' => $subscription->id,
   //             'stripe_status' => $subscription->status,
   //             'stripe_price' => $subscription->plan->id,
   //             'quantity' => $subscription->quantity,
   //             'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
   //             'ends_at' => $subscription->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
   //       ]);
   //    } catch (\Exception $e) {
   //       // Log the error during subscription storage
   //       Log::error('Stripe Webhook Error: Failed to store subscription details', ['error' => $e->getMessage(), 'subscription' => $subscription]);
   //    }
   // }


   // STRIPE WEBHOOK GATEWAY
   // public function handleWebhook(Request $request)
   // {
   //    $event = null;

   //    // Logging for debugging
   //    Log::info('Stripe Environment Variables', [
   //       'webhook_secret' => env('STRIPE_SIGNATURE_WEBHOOK'),
   //    ]);
   //    Log::info('Stripe Webhook Raw Payload', ['payload' => $request->getContent()]);
   //    Log::info('Stripe Webhook Headers', ['headers' => $request->headers->all()]);
   //    Log::info('Stripe Webhook Signature', ['stripe-signature' => $request->header('Stripe-Signature')]);

   //    try {
   //       // Validate the webhook signature
   //       $event = Webhook::constructEvent(
   //             $request->getContent(),
   //             $request->header('Stripe-Signature'),
   //             env('STRIPE_SIGNATURE_WEBHOOK')
   //       );
   //    } catch (\UnexpectedValueException $e) {
   //       Log::error('Stripe Webhook Error: Invalid payload', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid payload'], 400);
   //    } catch (\Stripe\Exception\SignatureVerificationException $e) {
   //       Log::error('Stripe Webhook Error: Invalid signature', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid signature'], 400);
   //    }

   //    // Handle specific event types
   //    try {
   //       switch ($event->type) {
   //             case 'payment_intent.succeeded':
   //                $this->storePaymentDetails($event->data->object);
   //                break;
   //             case 'invoice.payment_succeeded':
   //                $this->storeSubscriptionPaymentDetails($event->data->object);
   //                break;
   //             case 'customer.subscription.created':
   //                $this->storeSubscriptionDetails($event->data->object);
   //                break;
   //       }
   //       return response()->json(['status' => 'success']);
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: General error', [
   //             'error' => $e->getMessage(),
   //             'event_type' => $event->type
   //       ]);
   //       return response()->json(['error' => 'Failed to process event'], 500);
   //    }
   // }

   // protected function storePaymentDetails($paymentIntent)
   // {
   //    try {
   //       $customerId = $paymentIntent->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID

   //       if ($user) {
   //             Payment::create([
   //                'user_id' => $user->id, // Use dynamic user ID
   //                'stripe_payment_id' => $paymentIntent->id,
   //                'amount' => $paymentIntent->amount_received / 100, // Convert from cents
   //                'currency' => $paymentIntent->currency,
   //                'status' => $paymentIntent->status,
   //             ]);
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store payment details', ['error' => $e->getMessage(), 'payment_intent' => $paymentIntent]);
   //    }
   // }

   // protected function storeSubscriptionPaymentDetails($invoice)
   // {
   //    try {
   //       $customerId = $invoice->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID

   //       if ($user) {
   //             Payment::create([
   //                'user_id' => $user->id, // Use dynamic user ID
   //                'stripe_payment_id' => $invoice->id,
   //                'amount' => $invoice->amount_paid / 100, // Convert from cents
   //                'currency' => $invoice->currency,
   //                'status' => $invoice->status,
   //                'subscription_id' => $invoice->subscription,
   //             ]);
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store subscription payment details', ['error' => $e->getMessage(), 'invoice' => $invoice]);
   //    }
   // }

   // protected function storeSubscriptionDetails($subscription)
   // {
   //    try {
   //       $customerId = $subscription->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID

   //       if ($user) {
   //             Subscription::create([
   //                'user_id' => $user->id, // Use dynamic user ID
   //                'stripe_id' => $subscription->id,
   //                'stripe_status' => $subscription->status,
   //                'stripe_price' => $subscription->plan->id,
   //                'quantity' => $subscription->quantity,
   //                'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
   //                'ends_at' => $subscription->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
   //             ]);
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store subscription details', ['error' => $e->getMessage(), 'subscription' => $subscription]);
   //    }
   // }


   // public function handleWebhook(Request $request)
   // {
   //    $event = null;

   //    // Logging for debugging
   //    Log::info('Stripe Webhook Raw Payload', ['payload' => $request->getContent()]);
   //    Log::info('Stripe Webhook Signature', ['stripe-signature' => $request->header('Stripe-Signature')]);

   //    try {
   //       // Validate the webhook signature
   //       $event = Webhook::constructEvent(
   //             $request->getContent(),
   //             $request->header('Stripe-Signature'),
   //             env('STRIPE_SIGNATURE_WEBHOOK')
   //       );
   //    } catch (\UnexpectedValueException $e) {
   //       Log::error('Stripe Webhook Error: Invalid payload', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid payload'], 400);
   //    } catch (\Stripe\Exception\SignatureVerificationException $e) {
   //       Log::error('Stripe Webhook Error: Invalid signature', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid signature'], 400);
   //    }

   //    // Handle specific event types
   //    try {
   //       switch ($event->type) {
   //             case 'payment_intent.succeeded':
   //                $this->storePaymentDetails($event->data->object);
   //                break;

   //             case 'invoice.payment_succeeded':
   //                $this->storeSubscriptionPaymentDetails($event->data->object);
   //                break;

   //             case 'customer.subscription.created':
   //                $this->storeSubscriptionDetails($event->data->object);
   //                break;

   //             case 'customer.subscription.deleted':
   //                $this->handleSubscriptionCancellation($event->data->object);
   //                break;

   //             case 'invoice.payment_failed':
   //                $this->handleFailedPayment($event->data->object);
   //                break;

   //             default:
   //                Log::info("Unhandled event type: {$event->type}");
   //                break;
   //       }

   //       return response()->json(['status' => 'success']);
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: General error', [
   //             'error' => $e->getMessage(),
   //             'event_type' => $event->type
   //       ]);
   //       return response()->json(['error' => 'Failed to process event'], 500);
   //    }
   // }

   // protected function storePaymentDetails($paymentIntent)
   // {
   //    try {
   //       $customerId = $paymentIntent->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if ($user) {
   //             // Check if payment already exists to avoid duplicates
   //             $existingPayment = Payment::where('stripe_payment_id', $paymentIntent->id)->first();
   //             if (!$existingPayment) {
   //                Payment::create([
   //                   'user_id' => $user->id,
   //                   'stripe_payment_id' => $paymentIntent->id,
   //                   'amount' => $paymentIntent->amount_received / 100,
   //                   'currency' => $paymentIntent->currency,
   //                   'status' => $paymentIntent->status,
   //                ]);
   //             } else {
   //                Log::info('Duplicate payment prevented for paymentIntent ID: ' . $paymentIntent->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store payment details', ['error' => $e->getMessage(), 'payment_intent' => $paymentIntent]);
   //    }
   // }

   // protected function storeSubscriptionPaymentDetails($invoice)
   // {
   //    try {
   //       $customerId = $invoice->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if ($user) {
   //             $existingPayment = Payment::where('stripe_payment_id', $invoice->id)->first();
   //             if (!$existingPayment) {
   //                Payment::create([
   //                   'user_id' => $user->id,
   //                   'stripe_payment_id' => $invoice->id,
   //                   'amount' => $invoice->amount_paid / 100,
   //                   'currency' => $invoice->currency,
   //                   'status' => $invoice->status,
   //                   'subscription_id' => $invoice->subscription,
   //                ]);
   //             } else {
   //                Log::info('Duplicate payment prevented for invoice ID: ' . $invoice->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store subscription payment details', ['error' => $e->getMessage(), 'invoice' => $invoice]);
   //    }
   // }

   // protected function storeSubscriptionDetails($subscription)
   // {
   //    try {
   //       $customerId = $subscription->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if ($user) {
   //             $existingSubscription = Subscription::where('stripe_id', $subscription->id)->first();
   //             if (!$existingSubscription) {
   //                Subscription::create([
   //                   'user_id' => $user->id,
   //                   'stripe_id' => $subscription->id,
   //                   'stripe_status' => $subscription->status,
   //                   'stripe_price' => $subscription->plan->id,
   //                   'quantity' => $subscription->quantity,
   //                   'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
   //                   'ends_at' => $subscription->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
   //                ]);
   //             } else {
   //                Log::info('Duplicate subscription prevented for subscription ID: ' . $subscription->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store subscription details', ['error' => $e->getMessage(), 'subscription' => $subscription]);
   //    }
   // }

   // protected function handleSubscriptionCancellation($subscription)
   // {
   //    try {
   //       $customerId = $subscription->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if ($user) {
   //             $existingSubscription = Subscription::where('stripe_id', $subscription->id)->first();
   //             if ($existingSubscription) {
   //                $existingSubscription->update([
   //                   'stripe_status' => 'canceled',
   //                   'ends_at' => \Carbon\Carbon::createFromTimestamp($subscription->canceled_at),
   //                ]);
   //                Log::info('Subscription canceled for subscription ID: ' . $subscription->id);
   //             } else {
   //                Log::warning('Subscription not found for subscription ID: ' . $subscription->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to handle subscription cancellation', ['error' => $e->getMessage(), 'subscription' => $subscription]);
   //    }
   // }

   // protected function handleFailedPayment($invoice)
   // {
   //    try {
   //       $customerId = $invoice->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if ($user) {
   //             // Log the failed payment for tracking purposes
   //             Log::warning('Payment failed for user ID: ' . $user->id . ', Invoice ID: ' . $invoice->id);
   //       } else {
   //             Log::warning('User not found for failed payment, Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to handle payment failure', ['error' => $e->getMessage(), 'invoice' => $invoice]);
   //    }
   // }


   // public function handleWebhook(Request $request)
   // {
   //    $event = null;

   //    // Logging for debugging
   //    Log::info('Stripe Environment Variables', [
   //       'webhook_secret' => env('STRIPE_SIGNATURE_WEBHOOK'),
   //    ]);
   //    Log::info('Stripe Webhook Raw Payload', ['payload' => $request->getContent()]);
   //    Log::info('Stripe Webhook Headers', ['headers' => $request->headers->all()]);
   //    Log::info('Stripe Webhook Signature', ['stripe-signature' => $request->header('Stripe-Signature')]);

   //    try {
   //       // Validate the webhook signature
   //       $event = \Stripe\Webhook::constructEvent(
   //             $request->getContent(),
   //             $request->header('Stripe-Signature'),
   //             env('STRIPE_SIGNATURE_WEBHOOK')
   //       );
   //    } catch (\UnexpectedValueException $e) {
   //       Log::error('Stripe Webhook Error: Invalid payload', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid payload'], 400);
   //    } catch (\Stripe\Exception\SignatureVerificationException $e) {
   //       Log::error('Stripe Webhook Error: Invalid signature', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //       ]);
   //       return response()->json(['error' => 'Invalid signature'], 400);
   //    }

   //    // Handle specific event types
   //    try {
   //       switch ($event->type) {
   //             case 'payment_intent.succeeded': // One-time payment
   //                $this->storeOneTimePaymentDetails($event->data->object);
   //                break;
   //             case 'invoice.payment_succeeded': // Recurring subscription payment
   //                $this->storeSubscriptionPaymentDetails($event->data->object);
   //                break;
   //             case 'customer.subscription.created': // Subscription created
   //                $this->storeSubscriptionDetails($event->data->object);
   //                break;
   //       }
   //       return response()->json(['status' => 'success']);
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: General error', [
   //             'error' => $e->getMessage(),
   //             'event_type' => $event->type
   //       ]);
   //       return response()->json(['error' => 'Failed to process event'], 500);
   //    }
   // }

   // protected function storeOneTimePaymentDetails($paymentIntent)
   // {
   //    try {
   //       // Log when the method is called
   //       Log::info('Handling one-time payment for Payment Intent: ' . $paymentIntent->id);


   //       $customerId = $paymentIntent->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID

   //       if ($user) {
   //             // Check for duplicate payment
   //             if (!Payment::where('stripe_payment_id', $paymentIntent->id)->exists()) {
   //                // Create the payment entry with subscription_id set to NULL
   //                Payment::create([
   //                   'user_id' => $user->id,
   //                   'stripe_payment_id' => $paymentIntent->id,
   //                   'amount' => $paymentIntent->amount_received / 100, // Convert from cents
   //                   'currency' => $paymentIntent->currency,
   //                   'status' => $paymentIntent->status,
   //                   'subscription_id' => null, // Explicitly set subscription_id to NULL for one-time payments
   //                ]);
   //             } else {
   //                Log::info('Duplicate Payment: ' . $paymentIntent->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store one-time payment details', [
   //             'error' => $e->getMessage(),
   //             'payment_intent' => $paymentIntent
   //       ]);
   //    }
   // }

   // protected function storeSubscriptionPaymentDetails($invoice)
   // {
   //    try {
   //       $customerId = $invoice->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID

   //       if ($user) {
   //             // Check for duplicate payment
   //             if (!Payment::where('stripe_payment_id', $invoice->id)->exists()) {
   //                // Create the payment entry with subscription_id
   //                Payment::create([
   //                   'user_id' => $user->id,
   //                   'stripe_payment_id' => $invoice->id,
   //                   'amount' => $invoice->amount_paid / 100, // Convert from cents
   //                   'currency' => $invoice->currency,
   //                   'status' => $invoice->status,
   //                   'subscription_id' => $invoice->subscription, // Set subscription_id for recurring payments
   //                ]);
   //             } else {
   //                Log::info('Duplicate Payment: ' . $invoice->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store subscription payment details', [
   //             'error' => $e->getMessage(),
   //             'invoice' => $invoice
   //       ]);
   //    }
   // }

   // protected function storeSubscriptionDetails($subscription)
   // {
   //    try {
   //       $customerId = $subscription->customer;
   //       $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID

   //       if ($user) {
   //             // Prevent duplicate subscription entries
   //             if (!Subscription::where('stripe_id', $subscription->id)->exists()) {
   //                // Create the subscription if it doesn't exist
   //                Subscription::create([
   //                   'user_id' => $user->id,
   //                   'stripe_id' => $subscription->id,
   //                   'stripe_status' => $subscription->status,
   //                   'stripe_price' => $subscription->plan->id,
   //                   'quantity' => $subscription->quantity,
   //                   'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
   //                   'ends_at' => $subscription->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
   //                ]);
   //             } else {
   //                Log::info('Duplicate Subscription: ' . $subscription->id);
   //             }
   //       } else {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store subscription details', [
   //             'error' => $e->getMessage(),
   //             'subscription' => $subscription
   //       ]);
   //    }
   // }



   public function handleWebhook(Request $request)
   {
      $event = null;
      // Logging for debugging
      Log::info('Stripe Environment Variables', [
         'webhook_secret' => env('STRIPE_SIGNATURE_WEBHOOK'),
      ]);
      Log::info('Stripe Webhook Raw Payload', ['payload' => $request->getContent()]);
      Log::info('Stripe Webhook Headers', ['headers' => $request->headers->all()]);
      Log::info('Stripe Webhook Signature', ['stripe-signature' => $request->header('Stripe-Signature')]);
      try {
         // Validate the webhook signature
         $event = \Stripe\Webhook::constructEvent(
               $request->getContent(),
               $request->header('Stripe-Signature'),
               env('STRIPE_SIGNATURE_WEBHOOK')
         );
      } catch (\UnexpectedValueException $e) {
         Log::error('Stripe Webhook Error: Invalid payload', [
               'error' => $e->getMessage(),
               'payload' => $request->getContent()
         ]);
         return response()->json(['error' => 'Invalid payload'], 400);
      } catch (\Stripe\Exception\SignatureVerificationException $e) {
         Log::error('Stripe Webhook Error: Invalid signature', [
               'error' => $e->getMessage(),
               'payload' => $request->getContent()
         ]);
         return response()->json(['error' => 'Invalid signature'], 400);
      }

      Log::error('Test Event Type Check : Invalid signature', [
         'error' => $event->type
      ]);
      // Handle specific event types
      try {
         switch ($event->type) {
               case 'payment_intent.succeeded': // One-time payment
                  $this->storeOneTimePaymentDetails($event->data->object);
                  break;
               case 'invoice.payment_succeeded': // Recurring subscription payment
                  $this->storeSubscriptionPaymentDetails($event->data->object);
                  break;
               case 'customer.subscription.created': // Subscription created
                  $this->storeSubscriptionDetails($event->data->object);
                  break;
         }
         return response()->json(['status' => 'success']);
      } catch (\Exception $e) {
         Log::error('Stripe Webhook Error: General error', [
               'error' => $e->getMessage(),
               'event_type' => $event->type
         ]);
         return response()->json(['error' => 'Failed to process event'], 500);
      }
   }
   protected function storeOneTimePaymentDetails($paymentIntent)
   {
      try {
         // Log when the method is called
         Log::info('Handling one-time payment for Payment Intent: ' . $paymentIntent->id);
         // Check for valid payment intent object
         if (!isset($paymentIntent->customer)) {
               Log::warning('Payment Intent does not contain a customer ID.');
               return;
         }
         $customerId = $paymentIntent->customer;
         $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID
         if ($user) {
               // Check for duplicate payment
               if (!Payment::where('stripe_payment_id', $paymentIntent->id)->exists()) {
                  // Create the payment entry with subscription_id set to NULL
                  Payment::create([
                     'user_id' => $user->id,
                     'stripe_payment_id' => $paymentIntent->id,
                     'amount' => $paymentIntent->amount_received / 100, // Convert from cents
                     'currency' => $paymentIntent->currency,
                     'status' => $paymentIntent->status,
                     'subscription_id' => null, // Explicitly set subscription_id to NULL for one-time payments
                  ]);
                  Log::info('One-time payment stored successfully for Payment Intent: ' . $paymentIntent->id);
               } else {
                  Log::info('Duplicate Payment: ' . $paymentIntent->id);
               }
         } else {
               Log::warning('User not found for Stripe customer ID: ' . $customerId);
         }
      } catch (\Exception $e) {
         Log::error('Stripe Webhook Error: Failed to store one-time payment details', [
               'error' => $e->getMessage(),
               'payment_intent' => $paymentIntent
         ]);
      }
   }
   protected function storeSubscriptionPaymentDetails($invoice)
   {
      try {
         $customerId = $invoice->customer;
         $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID
         if ($user) {
               // Check for duplicate payment
               if (!Payment::where('stripe_payment_id', $invoice->id)->exists()) {
                  // Create the payment entry with subscription_id
                  Payment::create([
                     'user_id' => $user->id,
                     'stripe_payment_id' => $invoice->id,
                     'amount' => $invoice->amount_paid / 100, // Convert from cents
                     'currency' => $invoice->currency,
                     'status' => $invoice->status,
                     'subscription_id' => $invoice->subscription, // Set subscription_id for recurring payments
                  ]);
               } else {
                  Log::info('Duplicate Payment: ' . $invoice->id);
               }
         } else {
               Log::warning('User not found for Stripe customer ID: ' . $customerId);
         }
      } catch (\Exception $e) {
         Log::error('Stripe Webhook Error: Failed to store subscription payment details', [
               'error' => $e->getMessage(),
               'invoice' => $invoice
         ]);
      }
   }
   protected function storeSubscriptionDetails($subscription)
   {
      try {
         $customerId = $subscription->customer;
         $user = User::where('stripe_customer_id', $customerId)->first(); // Fetch user based on Stripe customer ID
         if ($user) {
               // Prevent duplicate subscription entries
               if (!Subscription::where('stripe_id', $subscription->id)->exists()) {
                  // Create the subscription if it doesn't exist
                  Subscription::create([
                     'user_id' => $user->id,
                     'stripe_id' => $subscription->id,
                     'stripe_status' => $subscription->status,
                     'stripe_price' => $subscription->plan->id,
                     'quantity' => $subscription->quantity,
                     'trial_ends_at' => $subscription->trial_end ? \Carbon\Carbon::createFromTimestamp($subscription->trial_end) : null,
                     'ends_at' => $subscription->current_period_end ? \Carbon\Carbon::createFromTimestamp($subscription->current_period_end) : null,
                  ]);
               } else {
                  Log::info('Duplicate Subscription: ' . $subscription->id);
               }
         } else {
               Log::warning('User not found for Stripe customer ID: ' . $customerId);
         }
      } catch (\Exception $e) {
         Log::error('Stripe Webhook Error: Failed to store subscription details', [
               'error' => $e->getMessage(),
               'subscription' => $subscription
         ]);
      }
   }


}
