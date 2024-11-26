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
use App\Models\Lesson;
use App\Models\Exam;
use App\Models\PracticeSet;
use App\Models\Quizze;
use App\Models\Video;
use App\Models\PracticeLesson;
use App\Models\PracticeVideo;
use App\Models\SubscriptionItem;

class PaymentController extends Controller

{
   public function viewPlans(Request $request){
      
      if (!Auth()->user()->can('plan')) { 
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }

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
               ->addColumn('type', function($row) {
                  return ucfirst($row->price_type);
               })
               ->addColumn('status', function($row) {
                  // Determine the status color and text based on `is_active`
                  $statusColor = $row->status == 1 ? 'success' : 'danger';
                  $statusText = $row->status == 1 ? 'Active' : 'Inactive';
                  // Create the status badge HTML
                  return $status = "<span class='bg-{$statusColor}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{$statusColor} text-xs'>{$statusText}</span>";
               })
               ->rawColumns(['status','category','action','type'])
               ->make(true);
      }
      return view('managePayment.plans.view-plans');
   }

   public function createPlan(){
      if (!Auth()->user()->can('plan')) { 
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }

      $subCategory = SubCategory::whereIn('status',[1,0])->get();
      return view('managePayment.plans.create-plans',compact('subCategory'));
   }

   public function getFeatureData(Request $request)
   {
      $categoryId = $request->input('category_id');
      $data = [];

      $data['practiceSets'] = PracticeSet::where('subCategory_id', $categoryId)
         ->where('status', 1)
         ->distinct()
         ->get();

      $data['quizzes'] = Quizze::where('subCategory_id', $categoryId)
         ->where('status', 1)
         ->where('is_public', 1)
         ->distinct()
         ->get();

      $data['exams'] = Exam::where('subCategory_id', $categoryId)
         ->where('status', 1)
         ->where('is_public', 1)
         ->distinct()
         ->get();

      $data['lessons'] = PracticeLesson::with(['lesson' => function ($query) {
         $query->distinct();
      }])->where('subcategory_id', $categoryId)
         ->distinct()
         ->get();

      $data['videos'] = PracticeVideo::with(['video' => function ($query) {
         $query->distinct();
      }])->where('subcategory_id', $categoryId)
         ->distinct()
         ->get();

      return response()->json($data);
   }

   public function savePlan(Request $request)
   {

      if (!Auth()->user()->can('plan')) {
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }
   
      // Validate the inputs
      $validatedData = $request->validate([
         'plan_name' => 'required|string|min:3',
         'description' => 'nullable|string',
         'price_type' => 'required|in:fixed,monthly',
         'duration' => 'nullable|integer|min:1',
         'price' => 'required|numeric|min:0',
         'discount' => 'required|boolean',
         'discount_percentage' => 'nullable|numeric|min:0|max:100',
         'category' => 'required|integer',
         'order' => 'required|integer|min:1',
         'popular' => 'required|boolean',
         'status' => 'required|boolean',
         'exams' => 'array',
         'exams.*' => 'integer',
         'quizzes' => 'array',
         'quizzes.*' => 'integer',
         'practice_sets' => 'array',
         'practice_sets.*' => 'integer',
         'lessons' => 'array',
         'lessons.*' => 'integer',
         'videos' => 'array',
         'videos.*' => 'integer',
      ]);
   
      // Remove duplicates from feature arrays
      $exams = array_unique($request->input('exams', []));
      $quizzes = array_unique($request->input('quizzes', []));
      $practice_sets = array_unique($request->input('practice_sets', []));
      $lessons = array_unique($request->input('lessons', []));
      $videos = array_unique($request->input('videos', []));
   
      if (empty($exams) && empty($quizzes) && empty($practice_sets) && empty($lessons) && empty($videos)) {
         return redirect()->back()->withErrors([
            'features' => 'At least one feature (exams, quizzes, practice sets, lessons, or videos) must be selected.',
         ])->withInput();
      }

      // Set the Stripe API key
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

      // Fetch the currency setting (assuming a single currency for now)
      $generalSetting = GeneralSetting::select('currency')->first();
      $currency = $generalSetting ? $generalSetting->currency : 'usd'; // Default to 'usd' if not set

      // Create a new product in Stripe
      try {
         $product = Product::create([
            'name' => $validatedData['plan_name'],
            'description' => $validatedData['description'] ?? $validatedData['plan_name'],
            'type' => 'service', // Change to 'good' if applicable
         ]);

         // Calculate the price considering any discounts
         $basePrice = $validatedData['price'] * 100; // Convert to cents

         // Apply percentage discount if applicable
         if ($validatedData['discount'] == 1 &&!empty($validatedData['discount_percentage']) && $validatedData['discount_percentage'] > 0) {
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
            if (!empty($validatedData['duration']) && $validatedData['duration'] >= 1) {
               $stripePriceData['recurring'] = [
                  'interval'       => 'month', // Monthly recurrence
                  'interval_count' => 1,       // Charge every month
               ];
            } else {
               // Duration must be specified for monthly plans
               return redirect()->back()->withErrors([
                  'duration' => 'Duration must be specified and at least 1 month for monthly plans.',
               ])->withInput();
            }
         }
   
         // Create a price in Stripe
         $price = Price::create($stripePriceData);

         // CREATE A PLAN IN DATABASE
         $data = new Plan();
         $data->category_id = $validatedData['category'] ?? null;
         $data->name = $validatedData['plan_name'] ?? null;
         $data->price_type = $validatedData['price_type'] ?? null; // monthly or fixed
         $data->duration = $validatedData['duration'] ?? null; // Duration in months
         $data->price = $validatedData['price'] ?? null;
         $data->discount = $validatedData['discount'] ?? 0;
         $data->discount_percentage = $validatedData['discount_percentage'] ?? null;
         $data->description = $validatedData['description'] ?? null;
         $data->sort_order = $validatedData['order'] ?? null;
         $data->exams = json_encode($exams,true) ?? null;
         $data->quizzes = json_encode($quizzes,true) ?? null;
         $data->practices = json_encode($practice_sets,true) ?? null;
         $data->lessons = json_encode($lessons,true) ?? null;
         $data->videos = json_encode($videos,true) ?? null;
         $data->popular = $validatedData['popular'] ?? null;
         $data->status = $validatedData['status'] ?? null;
         $data->stripe_product_id = $product->id;
         $data->stripe_price_id = $price->id;
         $data->save();
         // Return a success response
         return redirect()->route('view-plans')->with('success', 'Plan Created Successfully');

      } catch (\Stripe\Exception\ApiErrorException $e) {

         // Handle any errors from the Stripe API
         return redirect()->back()->with('error', 'Failed to create plan in Stripe: ' . $e->getMessage());
      } catch (\Exception $e) {

         // Handle any other exceptions
         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
      }

      // Return a success response
      return redirect()->back()->with('success', 'Something Went Wrong');
   }

   // public function subscribeToPlan(Request $request, $planId)
   // {
   //    $plan = Plan::findOrFail($planId);
   //    $user = Auth::user();

   //    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

   //    try {
   //       // Create or retrieve the Stripe customer
   //       if (!$user->stripe_customer_id) {
   //             $customer = \Stripe\Customer::create([
   //                'email' => $user->email,
   //                'name' => $user->name,
   //             ]);
   //             $user->stripe_customer_id = $customer->id;
   //             $user->save();
   //       }

   //       if ($plan->price_type == 'monthly') {
   //             // Create a subscription with a limited duration
   //             $subscription = \Stripe\Subscription::create([
   //                'customer' => $user->stripe_customer_id,
   //                'items' => [
   //                   ['price' => $plan->stripe_price_id],
   //                ],
   //                'cancel_at' => strtotime("+{$plan->duration} months"),
   //             ]);

   //             // Save subscription details in your database
   //             // ...

   //             return redirect()->route('dashboard')->with('success', 'Subscription started successfully.');
   //       } elseif ($plan->price_type == 'fixed') {
   //             // Handle fixed plan purchase (one-time payment)
   //             $paymentIntent = \Stripe\PaymentIntent::create([
   //                'amount' => $plan->price * 100,
   //                'currency' => $plan->currency,
   //                'customer' => $user->stripe_customer_id,
   //                'description' => $plan->name,
   //             ]);

   //             // Save payment details in your database
   //             // ...

   //             return redirect()->route('dashboard')->with('success', 'Plan purchased successfully.');
   //       } else {
   //             return redirect()->back()->with('error', 'Invalid plan type.');
   //       }
   //    } catch (\Stripe\Exception\ApiErrorException $e) {
   //       return redirect()->back()->with('error', 'Stripe error: ' . $e->getMessage());
   //    } catch (\Exception $e) {
   //       return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
   //    }
   // }


   public function editPlan(Request $request){
      
      if (!Auth()->user()->can('plan')) { 
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }
      
      $request->validate([
         'eq'=>'required'
      ]);

      $data = decrypturl($request->eq);
      $plan_id = $data['id'];
      $plan = Plan::where('id',$plan_id)->first();
      if($plan){
         $subCategory = SubCategory::whereIn('status',[1,0])->get();

         $practices = PracticeSet::where('subCategory_id', $plan->category_id)
         ->where('status', 1)
         ->distinct()
         ->get();

         $quizzes = Quizze::where('subCategory_id', $plan->category_id)
            ->where('status', 1)
            ->where('is_public', 1)
            ->distinct()
            ->get();

         $exams = Exam::where('subCategory_id', $plan->category_id)
            ->where('status', 1)
            ->where('is_public', 1)
            ->distinct()
            ->get();

         $lessons = PracticeLesson::with(['lesson' => function ($query) {
            $query->distinct();
         }])->where('subcategory_id', $plan->category_id)
            ->distinct()
            ->get();

         $videos = PracticeVideo::with(['video' => function ($query) {
            $query->distinct();
         }])->where('subcategory_id', $plan->category_id)
            ->distinct()
            ->get();

         return view('managePayment.plans.edit-plans',compact('subCategory','plan','practices','quizzes','exams','lessons','videos'));
      }
      return redirect()->back()->with('error','Invalid Plan');
   }

   public function updatePlan(Request $request)
   {
      if (!Auth()->user()->can('plan')) { 
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }
      
      // Set the Stripe API key
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

      // Validate the incoming request data
      $validatedData = $request->validate([
         'plan_name' => 'required|string|min:3',
         'description' => 'nullable|string',
         'price_type' => 'required|in:fixed,monthly',
         'duration' => 'nullable|integer|min:1',
         'price' => 'required|numeric|min:0',
         'discount' => 'required|boolean',
         'discount_percentage' => 'nullable|numeric|min:0|max:100',
         'category' => 'required|integer',
         'order' => 'required|integer|min:1',
         'popular' => 'required|boolean',
         'status' => 'required|boolean',
         'exams' => 'array',
         'exams.*' => 'integer',
         'quizzes' => 'array',
         'quizzes.*' => 'integer',
         'practice_sets' => 'array',
         'practice_sets.*' => 'integer',
         'lessons' => 'array',
         'lessons.*' => 'integer',
         'videos' => 'array',
         'videos.*' => 'integer',
         'eq' => 'required'
      ]);

      // Ensure features is always an array
      $exams = array_unique($request->input('exams', []));
      $quizzes = array_unique($request->input('quizzes', []));
      $practice_sets = array_unique($request->input('practice_sets', []));
      $lessons = array_unique($request->input('lessons', []));
      $videos = array_unique($request->input('videos', []));
   
      if (empty($exams) && empty($quizzes) && empty($practice_sets) && empty($lessons) && empty($videos)) {
         return redirect()->back()->withErrors([
            'features' => 'At least one feature (exams, quizzes, practice sets, lessons, or videos) must be selected.',
         ])->withInput();
      }

      // Set the Stripe API key
      \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
      
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
                              $validatedData['price_type'] != $data->price_type;
                              // $validatedData['duration'] != $data->duration

               // If price or duration changed, update the price in Stripe
               if ($priceChanged) {
                  // Recalculate the price considering any discounts
                  $basePrice = $validatedData['price'] * 100; // Convert to cents

                  // Apply percentage discount if applicable
                  if ($validatedData['discount'] == 1 &&!empty($validatedData['discount_percentage']) && $validatedData['discount_percentage'] > 0) {
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
                     if (!empty($validatedData['duration']) && $validatedData['duration'] >= 1) {
                        $stripePriceData['recurring'] = [
                           'interval'       => 'month', // Monthly recurrence
                           'interval_count' => 1,       // Charge every month
                        ];
                     } else {
                        // Duration must be specified for monthly plans
                        return redirect()->back()->withErrors([
                           'duration' => 'Duration must be specified and at least 1 month for monthly plans.',
                        ])->withInput();
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
               $data->category_id = $validatedData['category'] ?? null;
               $data->name = $validatedData['plan_name'] ?? null;
               $data->price_type = $validatedData['price_type'] ?? null; // monthly or fixed
               $data->duration = $validatedData['duration'] ?? null; // Duration in months
               $data->price = $validatedData['price'] ?? null;
               $data->discount = $validatedData['discount'] ?? 0;
               $data->discount_percentage = $validatedData['discount_percentage'] ?? null;
               $data->description = $validatedData['description'] ?? null;
               $data->sort_order = $validatedData['order'] ?? null;
               $data->exams = json_encode($exams,true) ?? null;
               $data->quizzes = json_encode($quizzes,true) ?? null;
               $data->practices = json_encode($practice_sets,true) ?? null;
               $data->lessons = json_encode($lessons,true) ?? null;
               $data->videos = json_encode($videos,true) ?? null;
               $data->popular = $validatedData['popular'] ?? null;
               $data->status = $validatedData['status'] ?? null;
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
      if (!Auth()->user()->can('plan')) { 
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }
      
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

   public function payment(Request $request){
      
      if (!Auth()->user()->can('payment')) { // Assuming 'file-manager' is the required permission
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }

      if ($request->ajax()) {
         $sections = Payment::with('user');

         return DataTables::of($sections)
             ->addIndexColumn()
             ->addColumn('pay_date', function($row) {
                 return date('d/m/Y', strtotime($row->created_at));
             })
             ->addColumn('status', function($row) {
               // Create the status badge HTML
               return $status = "<span class='bg-primary/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-primary text-xs'>{$row->status}</span>";
             })
             ->addColumn('user', function($row) {
               if ($row->user) {
                  return $row->user->email;
               }
               return "---";
             })
             ->addColumn('type', function($row) {
               if ($row->subscription_id == "one-time") {
                  return "One Time";
               }
               return "Subscription";
             })
             ->addColumn('price', function($row) {
               return $row->amount." ".ucfirst($row->currency);
             })
             ->addColumn('payment_id', function($row) {
               return $row->stripe_payment_id;
             })
             ->rawColumns(['status','pay_date','user','price','type','payment_id'])
             ->make(true);
     }
     return view('managePayment.payment.view-payment');
   }

   public function subscription(Request $request){
      if (!Auth()->user()->can('subscription')) { // Assuming 'file-manager' is the required permission
         return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
      }

      if ($request->ajax()) {
         $sections = Subscription::with('user','plans');

         return DataTables::of($sections)
            ->addIndexColumn()
            ->addColumn('purchase_date', function($row) {
               return date('d/m/Y', strtotime($row->created_at));
            })
            ->addColumn('end_date', function($row) {
               return date('d/m/Y', strtotime($row->ends_at));
            })
            ->addColumn('status', function($row) {
              // Create the status badge HTML
              $status = $row->stripe_status == "incomplete" ? "Active" : $row->stripe_status;
              return $status = "<span class='bg-primary/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-primary text-xs'>{$status}</span>";
            })
            ->addColumn('user', function($row) {
               if ($row->user) {
                  return $row->user->email;
               }
               return "---";
            })
            ->addColumn('plan', function($row) {
               if ($row->plans) {
                  return $row->plans->name;
               }
               return "---";
            })
            ->addColumn('price', function($row) {
               if ($row->plans) {
                  return $row->plans->price;
               }
               return "---";
            })
            ->addColumn('subscription_id', function($row) {
               return $row->stripe_id;
            })
            ->rawColumns(['status','purchase_date','user','plan','price','subscription_id','end_date'])
            ->make(true);
   }
     return view('managePayment.subscription.view-subscription');
   }
   
   // WEBHOOK
   // public function handleWebhook(Request $request)
   // {
   //     $event = null;
   
   //     // Logging for debugging
   //     Log::info('Stripe Environment Variables', [
   //         'webhook_secret' => env('STRIPE_SIGNATURE_WEBHOOK'),
   //     ]);
   //     Log::info('Stripe Webhook Raw Payload', ['payload' => $request->getContent()]);
   //     Log::info('Stripe Webhook Headers', ['headers' => $request->headers->all()]);
   
   //     try {
   //         // Validate the webhook signature
   //         $event = \Stripe\Webhook::constructEvent(
   //             $request->getContent(),
   //             $request->header('Stripe-Signature'),
   //             env('STRIPE_SIGNATURE_WEBHOOK')
   //         );
   //     } catch (\UnexpectedValueException $e) {
   //         Log::error('Stripe Webhook Error: Invalid payload', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //         ]);
   //         return response()->json(['error' => 'Invalid payload'], 400);
   //     } catch (\Stripe\Exception\SignatureVerificationException $e) {
   //         Log::error('Stripe Webhook Error: Invalid signature', [
   //             'error' => $e->getMessage(),
   //             'payload' => $request->getContent()
   //         ]);
   //         return response()->json(['error' => 'Invalid signature'], 400);
   //     }
   
   //     // Handle specific event types
   //     switch ($event->type) {
   //         case 'payment_intent.succeeded':
   //             $this->storeOneTimePaymentDetails($event->data->object);
   //             break;
   //         case 'invoice.payment_succeeded':
   //             $this->storeSubscriptionPaymentDetails($event->data->object);
   //             break;
   //         case 'customer.subscription.created':
   //             $this->storeSubscriptionDetails($event->data->object);
   //             break;
   //         // Add other relevant cases as needed
   //         default:
   //             Log::info('Unhandled event type: ' . $event->type);
   //     }
   
   //     return response()->json(['status' => 'success']);
   // }
   
   // protected function storeOneTimePaymentDetails($paymentIntent)
   // {
   //    try {
   //       // Log when the method is called
   //       Log::info('Handling one-time payment for Payment Intent: ' . $paymentIntent->id);

   //       // Check for valid payment intent object and customer ID
   //       if (!isset($paymentIntent->customer)) {
   //             Log::warning('Payment Intent does not contain a customer ID.');
   //             return;
   //       }

   //       $customerId = $paymentIntent->customer;

   //       // Fetch user based on Stripe customer ID
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if (!$user) {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //             return;
   //       }

   //       // Check for duplicate payment
   //       $existingPayment = Payment::where('stripe_payment_id', $paymentIntent->id)->exists();

   //       if ($existingPayment) {
   //             Log::info('Duplicate Payment: ' . $paymentIntent->id);
   //             return;
   //       }

   //       // Create the payment entry with subscription_id set to "one-time"
   //       Payment::create([
   //          'user_id' => $user->id,
   //          'stripe_payment_id' => $paymentIntent->id,
   //          'amount' => $paymentIntent->amount_received / 100, // Convert from cents
   //          'currency' => $paymentIntent->currency,
   //          'status' => $paymentIntent->status,
   //          'subscription_id' => null, // Set null for one-time payments
   //          'plan_id' => $paymentIntent->metadata->plan_id ?? null, // Assume plan_id is in metadata if applicable
   //       ]);

   //       Log::info('One-time payment stored successfully for Payment Intent: ' . $paymentIntent->id);
         
   //    } catch (\Exception $e) {
   //       Log::error('Stripe Webhook Error: Failed to store one-time payment details', [
   //             'error' => $e->getMessage(),
   //             'payment_intent' => $paymentIntent
   //       ]);
   //    }
   // }

   // protected function updateSubscriptionRecord($user, $invoice)
   // {
   //    try {
   //       // Mark the existing subscription as expired
   //       $activeSubscription = Subscription::where('user_id', $user->id)
   //             ->where('stripe_status', 'active')
   //             ->orWhere('stripe_status', 'trialing')
   //             ->first();

   //       if ($activeSubscription) {
   //             $activeSubscription->update([
   //                'stripe_status' => 'expired',
   //                'ends_at' => now(), // Mark the current time as the end time
   //             ]);

   //             Log::info('Marked existing subscription as expired: ' . $activeSubscription->stripe_id);
   //       }

   //       // Create a new subscription record
   //       $newSubscription = Subscription::create([
   //             'user_id' => $user->id,
   //             'stripe_id' => $invoice->subscription,
   //             'stripe_status' => $invoice->status,
   //             'stripe_price' => $invoice->lines->data[0]->price->id ?? null,
   //             'quantity' => $invoice->lines->data[0]->quantity ?? 1,
   //             'trial_ends_at' => $invoice->lines->data[0]->plan->trial_period_days
   //                ? now()->addDays($invoice->lines->data[0]->plan->trial_period_days)
   //                : null,
   //             'ends_at' => \Carbon\Carbon::createFromTimestamp($invoice->lines->data[0]->period->end ?? time()),
   //       ]);

   //       Log::info('Created new subscription record: ' . $newSubscription->stripe_id);
   //    } catch (\Exception $e) {
   //       Log::error('Error updating subscription records', [
   //             'error' => $e->getMessage(),
   //             'invoice' => $invoice,
   //       ]);
   //    }
   // }

   // protected function storeSubscriptionPaymentDetails($invoice)
   // {
   //    try {
         
   //       Log::info('Processing invoice.payment_succeeded event', [
   //          'invoice_id' => $invoice->id,
   //          'customer_id' => $invoice->customer,
   //          'subscription_id' => $invoice->subscription,
   //       ]);

   //       // Retrieve customer ID from the invoice
   //       $customerId = $invoice->customer;

   //       // Find the user based on the Stripe customer ID
   //       $user = User::where('stripe_customer_id', $customerId)->first();

   //       if (!$user) {
   //             Log::warning('User not found for Stripe customer ID: ' . $customerId);
   //             return;
   //       }

   //       // Check for duplicate payment entries
   //       $existingPayment = Payment::where('stripe_payment_id', $invoice->id)->exists();

   //       if ($existingPayment) {
   //             Log::info('Duplicate payment detected. Payment already exists for invoice: ' . $invoice->id);
   //             return;
   //       }

   //       // Validate invoice amounts
   //       if ($invoice->amount_paid > 0) {
   //             // Insert the payment into the database
   //             Payment::create([
   //                'user_id' => $user->id,
   //                'stripe_payment_id' => $invoice->id,
   //                'amount' => $invoice->amount_paid / 100, // Convert cents to dollars
   //                'currency' => $invoice->currency,
   //                'status' => $invoice->status,
   //                'subscription_id' => $invoice->subscription, // Attach subscription ID
   //                'description' => $invoice->lines->data[0]->description ?? 'Subscription Payment',
   //                'plan_id' => $invoice->lines->data[0]->price->id ?? null, // Fetch price ID if available
   //             ]);

   //             Log::info('Payment stored successfully for invoice: ' . $invoice->id);

   //             // Handle subscription record
   //             $this->updateSubscriptionRecord($user, $invoice);
   //       } else {
   //             Log::warning('Invoice amount_paid is zero for invoice: ' . $invoice->id);
   //       }
   //    } catch (\Exception $e) {
   //       Log::error('Error storing subscription payment details', [
   //             'error' => $e->getMessage(),
   //             'invoice' => $invoice,
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
   //                   'stripe_status' => $subscription->status == "incomplete" ? "complete" : $paymentIntent->status,
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

   public function handleStripeWebhook(Request $request)
   {
      $payload = $request->getContent();
      $sigHeader = $request->header('Stripe-Signature');
      $endpointSecret = env('STRIPE_SIGNATURE_WEBHOOK'); // Set your Stripe webhook secret in .env

      try {
         // Verify the webhook signature
         $event = \Stripe\Webhook::constructEvent(
               $payload,
               $sigHeader,
               $endpointSecret
         );

         // Handle specific events
         switch ($event->type) {
               case 'checkout.session.completed':
                  $session = $event->data->object;

                  // Handle completed checkout sessions
                  $this->handleCheckoutSessionCompleted($session);
                  break;

               case 'subscription.created':
                  $subscription = $event->data->object;

                  // Set cancel_at for the subscription
                  $this->handleSubscriptionCreated($subscription);
                  break;

               case 'invoice.payment_succeeded':
                  $invoice = $event->data->object;

                  // Handle successful subscription payments
                  $this->handleInvoicePaymentSucceeded($invoice);
                  break;

               case 'invoice.payment_failed':
                  $invoice = $event->data->object;

                  // Handle failed subscription payments (optional)
                  $this->handlePaymentFailed($invoice);
                  break;

               case 'payment_intent.succeeded':
                  $paymentIntent = $event->data->object;
                  // Handle successful payment intent
                  $this->handlePaymentIntentSucceeded($paymentIntent);
                  break;

               default:
                  // Log unhandled event types for debugging
                  \Log::info("Unhandled Stripe event type: {$event->type}");
         }

         return response()->json(['status' => 'success'], 200);
      } catch (\Exception $e) {
         \Log::error('Stripe webhook error: ' . $e->getMessage());
         \Log::error('Webhook request payload: ' . $payload);  // Log the payload for further debugging
         return response()->json(['error' => $e->getMessage()], 400);
      }
   }


   private function handlePaymentIntentSucceeded($paymentIntent)
   {
      // Extract metadata from the payment intent
      $metadata = $paymentIntent->metadata;
      $subscriptionId = $metadata->subscription_id;

      // Find the subscription based on the ID from metadata
      $subscription = Subscription::where('id', $subscriptionId)->first();

      if ($subscription) {
         // Record the payment
         Payment::create([
               'subscription_id' => $subscription->id,
               'payment_id' => $paymentIntent->id,
               'amount' => $paymentIntent->amount_received / 100,  // Convert from cents
               'currency' => $paymentIntent->currency,
               'status' => 'successful',
               'payment_date' => now(),
         ]);

         // Now that payment is successful, update the subscription status
         $subscription->update([
               'status' => 'active',  // Subscription is now active after payment
               'stripe_subscription_id' => $paymentIntent->id,  // You can store the payment intent ID here if you wish
         ]);

         \Log::info("Payment Intent {$paymentIntent->id} succeeded for subscription {$subscription->id}");
      } else {
         \Log::warning("Subscription not found for payment intent: {$paymentIntent->id}");
      }
   }


   private function handleSubscriptionCreated($subscription)
   {
      if (isset($subscription->metadata->duration)) {
         $cancelDate = now()->addMonths($subscription->metadata->duration)->timestamp;

         // Update the subscription to set cancel_at
         \Stripe\Subscription::update($subscription->id, [
               'cancel_at' => $cancelDate,
         ]);

         \Log::info("Set cancel_at for subscription {$subscription->id} to {$cancelDate}");
      } else {
         \Log::warning("Subscription metadata does not contain duration: {$subscription->id}");
      }
   }


   private function handleCheckoutSessionCompleted($session)
   {
      $metadata = $session->metadata;

      // Extract metadata
      $userId = $metadata->user_id;
      $planId = $metadata->plan_id;
      $priceType = $metadata->price_type;
      $duration = $metadata->duration;

      // Fetch user and plan
      $user = User::findOrFail($userId);
      $plan = Plan::findOrFail($planId);

      // Calculate subscription dates
      $startDate = now();
      $endDate = $priceType === 'fixed' ? $startDate->addMonths($duration) : $startDate->addMonths($duration);

      // Create subscription
      $subscription = Subscription::create([
         'user_id' => $user->id,
         'plan_id' => $plan->id,
         'type' => $priceType,
         'stripe_subscription_id' => $session->subscription, // Use the actual subscription ID here
         'start_date' => $startDate,
         'end_date' => $endDate,
         'status' => 'active',
      ]);

      // Save payment details (using session payment_intent)
      Payment::create([
         'subscription_id' => $subscription->id,
         'payment_id' => $session->payment_intent,
         'amount' => $session->amount_total / 100, // Convert from cents
         'currency' => $session->currency,
         'status' => 'successful',
         'payment_date' => now(),
      ]);

      // Assign subscription items
      $this->assignSubscriptionItems($subscription->id, $plan, $duration);

      \Log::info("Subscription created for user {$user->id} with plan {$plan->id}");
   }


   private function assignSubscriptionItems($subscriptionId, $plan, $duration)
   {
      $types = ['exams', 'quizzes', 'practice_sets', 'videos', 'lessons'];

      foreach ($types as $type) {
         $itemIds = json_decode($plan->$type); // Decode the JSON array

         if ($itemIds && is_array($itemIds)) {
               foreach ($itemIds as $itemId) {
                  SubscriptionItem::create([
                     'subscription_id' => $subscriptionId,
                     'item_type' => rtrim($type, 's'), // Convert plural to singular
                     'item_id' => $itemId,
                     'assigned_at' => now(),
                     'expires_at' => now()->addMonths($duration), // Match the subscription duration
                     'status' => 'active',
                  ]);
               }
         }
      }
   }

   private function handleInvoicePaymentSucceeded($invoice)
   {
      $subscriptionId = $invoice->subscription;

      // Find the subscription
      $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

      if ($subscription) {
         // Save the payment
         Payment::create([
               'subscription_id' => $subscription->id,
               'payment_id' => $invoice->payment_intent,
               'amount' => $invoice->amount_paid / 100, // Convert from cents
               'currency' => $invoice->currency,
               'status' => 'successful',
               'payment_date' => now(),
         ]);
      }
   }

   private function handlePaymentFailed($invoice)
   {
      $subscriptionId = $invoice->subscription;

      // Log payment failure
      \Log::error("Payment failed for subscription: $subscriptionId");

      // Find the subscription and mark it as 'payment_failed' (optional)
      $subscription = Subscription::where('stripe_subscription_id', $subscriptionId)->first();

      if ($subscription) {
         $subscription->update(['status' => 'payment_failed']);
      }
   }



}
