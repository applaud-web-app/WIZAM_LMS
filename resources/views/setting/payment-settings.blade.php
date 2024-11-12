@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <section class=" mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Payment Settings</h4>
                    <!-- Breadcrumb Navigation -->
                    <div class="flex flex-wrap justify-center">
                        <nav>
                            <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                                <!-- Parent Link -->
                                <li class="inline-flex items-center">
                                    <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group"
                                        href="{{route('admin-dashboard')}}">
                                        <i
                                            class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                                </li>
                                <!-- Middle (Conditional) -->

                                <li
                                    class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 transition duration-300 capitalize">Setting</span>
                                </li>

                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Payment
                                        Settings</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{route('update-payment-setting')}}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                           @csrf
                            <div class="mb-[15px]">
                                <label for="siteName"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Default Payment Processor <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <div class="flex flex-col flex-1 ">
                                        <select name="default_payment" id="default_payment" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                            <option disabled selected>Select Payment Method</option>
                                            @isset($paymentMethods)
                                                @foreach ($paymentMethods as $item)
                                                    <option value="{{$item->type}}" @isset($generalSetting->default_payment){{$generalSetting->default_payment == $item->type ? 'selected' : ''}}@endisset>{{ucfirst($item->type)}}</option>
                                                @endforeach
                                            @endisset
                                         </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="tagLine"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Currency <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <div class="flex flex-col flex-1 ">
                                        <select name="currency" id="currency" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                            <option disabled selected>Select Currency</option>
                                            @isset($currency)
                                                @foreach ($currency as $item)
                                                    <option value="{{$item->code}}" @isset($generalSetting->currency){{$generalSetting->currency == $item->code ? 'selected' : ''}}@endisset>{{$item->code}} - {{$item->currency}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="tagLine"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Currency Symbol <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="tagLine" name="currency_symbol" name="tag_line" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Currency Symbol" value="@isset($generalSetting->currency_symbol){{$generalSetting->currency_symbol}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitefavicon"
                                    class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Currency Symbol Position <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <select name="symbol_position" id="symbol_position" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                        <option value="left" @isset($generalSetting->symbol_position){{$generalSetting->symbol_position == "left" ? 'selected' : ''}}@endisset>Left</option>
                                        <option value="right" @isset($generalSetting->symbol_position){{$generalSetting->symbol_position == "right" ? 'selected' : ''}}@endisset>Right</option>
                                     </select>
                                </div>
                            </div>
                            <!-- You can add a submit button if needed -->
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class=" mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <h1 class="mb-4 text-xl"><b>PayPal Details</b></h1>
                        <form action="{{route('paypal-detail')}}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                           @csrf
                            @php $paypal = [];  @endphp
                            @if (isset($paypalSetting))
                                @php $paypal = json_decode($paypalSetting->details,true);  @endphp
                            @endif
                            <div class="mb-[15px]">
                                <label for="client_id"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Client Id <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="client_id" name="client_id" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="PayPal Client Id" value="@isset($paypal['client_id']){{$paypal['client_id']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="secret_key"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Secret <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="secret_key" name="secret_key" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="PayPal Secret" value="@isset($paypal['secret_key']){{$paypal['secret_key']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitefavicon"
                                    class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Enable PayPal <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <select name="status" id="status" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                        <option value="1" @isset($paypalSetting->status){{$paypalSetting->status == "1" ? 'selected' : ''}}@endisset>Enable</option>
                                        <option value="0" @isset($paypalSetting->status){{$paypalSetting->status == "0" ? 'selected' : ''}}@endisset>Disable</option>
                                     </select>
                                </div>
                            </div>
                            <!-- You can add a submit button if needed -->
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class=" mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <h1 class="mb-4 text-xl"><b>Stripe Details</b></h1>
                        <form action="{{route('stripe-detail')}}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                           @csrf
                           @php $stripe = [];  @endphp
                           @if (isset($stripeSetting))
                               @php $stripe = json_decode($stripeSetting->details,true);  @endphp
                           @endif
                            <div class="mb-[15px]">
                                <label for="api_key"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Api Key <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="api_key" name="api_key" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Stripe Api Key" value="@isset($stripe['api_key']){{$stripe['api_key']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="secret_key"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Secret Key <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="secret_key" name="secret_key" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Stripe Secret Key" value="@isset($stripe['secret_key']){{$stripe['secret_key']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitefavicon"
                                    class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Enable Stripe <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <select name="status" id="status" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                        <option value="1" @isset($stripeSetting->status){{$stripeSetting->status == "1" ? 'selected' : ''}}@endisset>Enable</option>
                                        <option value="0" @isset($stripeSetting->status){{$stripeSetting->status == "0" ? 'selected' : ''}}@endisset>Disable</option>
                                     </select>
                                </div>
                            </div>
                            <!-- You can add a submit button if needed -->
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class=" mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <h1 class="mb-4 text-xl"><b>Razorpay Details</b></h1>
                        <form action="{{route('razorpay-detail')}}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                           @csrf
                            @php $razorpay = [];  @endphp
                            @if (isset($razorpaySetting))
                                @php $razorpay = json_decode($razorpaySetting->details,true);  @endphp
                            @endif
                            <div class="mb-[15px]">
                                <label for="api_key"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Api Key <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="api_key" name="api_key" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Razorpay Api Key" value="@isset($razorpay['api_key']){{$razorpay['api_key']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="secret_key"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Secret Key <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="secret_key" name="secret_key" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Razorpay  Secret Key" value="@isset($razorpay['secret_key']){{$razorpay['secret_key']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitefavicon"
                                    class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Enable Razorpay  <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <select name="status" id="status" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                        <option value="1" @isset($razorpaySetting->status){{$razorpaySetting->status == "1" ? 'selected' : ''}}@endisset>Enable</option>
                                        <option value="0" @isset($razorpaySetting->status){{$razorpaySetting->status == "0" ? 'selected' : ''}}@endisset>Disable</option>
                                     </select>
                                </div>
                            </div>
                            <!-- You can add a submit button if needed -->
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class=" mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <h1 class="mb-4 text-xl"><b>Bank Details</b></h1>
                        <form action="{{route('bank-detail')}}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                           @csrf
                            @php $bank = [];  @endphp
                            @if (isset($bankSetting))
                                @php $bank = json_decode($bankSetting->details,true);  @endphp
                            @endif
                            <div class="mb-[15px]">
                                <label for="bank_name"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Bank Name <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="bank_name" name="bank_name" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Bank Name" value="@isset($bank['bank_name']){{$bank['bank_name']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="account_owner"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Account Owner <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="account_owner" name="account_owner" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Account Owner" value="@isset($bank['account_owner']){{$bank['account_owner']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="account_number"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Account Number <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="account_number" name="account_number" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Account Number" value="@isset($bank['account_number']){{$bank['account_number']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="iban"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">IBAN <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="iban" name="iban" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="IBAN" value="@isset($bank['iban']){{$bank['iban']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="routing_number"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Routing Number <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="routing_number" name="routing_number" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Routing Number" value="@isset($bank['routing_number']){{$bank['routing_number']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="swift"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">BIC/Swift <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="swift" name="swift" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="BIC/Swift" value="@isset($bank['swift']){{$bank['swift']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="other_detail"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Other Details <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="other_detail" name="other_detail" required
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Other Details" value="@isset($bank['other_detail']){{$bank['other_detail']}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitefavicon"
                                    class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Enable Bank Transfers  <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <select name="status" id="status" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2" required>
                                        <option value="1" @isset($bankSetting->status){{$bankSetting->status == "1" ? 'selected' : ''}}@endisset>Enable</option>
                                        <option value="0" @isset($bankSetting->status){{$bankSetting->status == "0" ? 'selected' : ''}}@endisset>Disable</option>
                                     </select>
                                </div>
                            </div>
                            <!-- You can add a submit button if needed -->
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class=" mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
@endsection
@push('scripts')
   <script>
      // jQuery Validation for the Add Section form
      $("#addSetting").validate({
         rules: {
            site_name: {
                  required: true
            },
            tag_line: {
                  required: true
            },
            seo_description: {
                  required: true,
                  maxlength: 1000
            },
         },
         messages: {
            site_name: {
                  required: "Please enter a site name"
            },
            tag_line: {
                  required: "Please enter a tag line"
            },
            seo_description: {
                  required: "Please provide an SEO description",
                  maxlength: "Description cannot exceed 1000 characters"
            }
         },
         submitHandler: function(form) {
            $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
            form.submit();
         }
      });

   </script>
@endpush