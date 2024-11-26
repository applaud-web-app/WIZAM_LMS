@extends('layouts.master')

@section('title', 'Billing Settings')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-start items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Billing And Tax Settings</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="{{route('admin-dashboard')}}">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Middle (Conditional) -->

                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]">
                        <span class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 transition duration-300 capitalize">Setting</span>
                     </li>

                     <!-- Child (Current Page) -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Billing Settings</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>

   <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
      <!-- Billing Information Card -->
      <div class="col-span-12 md:col-span-12">
         <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
            <div class="p-[25px]">
               <h5 class="text-[18px] text-dark dark:text-title-dark font-semibold mb-[15px]">Billing Information</h5>
               <form action="{{route('save-billing')}}" method="POST" autocomplete="off">
                    @csrf
                  <div class="mb-[15px]">
                      <label for="vendorName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Vendor Name <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="vendorName" name="vendor_name" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Vendor Name" value="@isset($billingTaxSetting->vendor_name){{$billingTaxSetting->vendor_name}}@endisset" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="address" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Address 
                      </label>
                      <input type="text" id="address" name="address" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="@isset($billingTaxSetting->address){{$billingTaxSetting->address}}@endisset" placeholder="Your Address">
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="country" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Country 
                      </label>
                        <select id="country" name="country"  class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                            @isset($country)
                                <option >Select Country</option>
                                @foreach ($country as $item)
                                    <option value="{{$item->id}}" @isset($billingTaxSetting->country_id){{$billingTaxSetting->country_id == $item->id ? 'selected' : ''}}@endisset>{{$item->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                  </div>
                    
                    <div class="mb-[15px]">
                      <label for="state" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          State
                      </label>
                       <select id="state" name="state" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                           <option >Select State</option>
                            @if($state)
                                @foreach ($state as $item)
                                    <option value="{{$item->id}}" @isset($billingTaxSetting->state_id){{$billingTaxSetting->state_id == $item->id ? 'selected' : ''}}@endisset>{{$item->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                  
                  <div class="mb-[15px]">
                      <label for="city" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          City 
                        </label>
                       <select id="city" name="city" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                           <option >Select City</option>
                             @isset($city)
                                @foreach ($city as $item)
                                    <option value="{{$item->id}}" @isset($billingTaxSetting->city_id){{$billingTaxSetting->city_id == $item->id ? 'selected' : ''}}@endisset>{{$item->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                  
                  <div class="mb-[15px]">
                      <label for="zip" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Zip 
                      </label>
                      <input type="text" id="zip" name="zip" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="@isset($billingTaxSetting->zip){{$billingTaxSetting->zip}}@endisset" placeholder="Your Zip">
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="phoneNumber" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Phone Number 
                      </label>
                      <input type="text" id="phoneNumber" name="phone_number" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Phone Number" value="@isset($billingTaxSetting->phone_number){{$billingTaxSetting->phone_number}}@endisset">
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="vatNumber" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          VAT Number 
                      </label>
                      <input type="text" id="vatNumber" name="vat_number" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="@isset($billingTaxSetting->vat_number){{$billingTaxSetting->vat_number}}@endisset" placeholder="Your VAT Number">
                  </div>
                  
                  <div class="mb-[15px]">
                     <div class="mb-[0.125rem] block min-h-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" value="1" type="checkbox" id="enableInvoicing" name="enable_invoicing" @isset($billingTaxSetting->enable_invoicing){{$billingTaxSetting->enable_invoicing == 1 ? "checked" : ""}}@endisset>
                         <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="enableInvoicing">
                             Enable Invoicing
                         </label>
                     </div>
                 </div>
                  
                  <div class="mb-[15px]">
                      <label for="invoicePrefix" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Invoice Prefix <span class="text-danger">*</span>
                      </label>
                      <input type="text" required id="invoicePrefix" name="invoice_prefix" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Invoice Prefix" value="@isset($billingTaxSetting->invoice_prefix){{$billingTaxSetting->invoice_prefix}}@endisset">
                  </div>

                  <div class="mb-[15px]">
                     <button type="submit" class=" bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                 </div>

               </form>
            </div>
         </div>
      </div>

      <!-- Tax Information Card -->
      {{-- <div class="col-span-12 md:col-span-6">
         <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
            <div class="p-[25px]">
               <h5 class="text-[18px] text-dark dark:text-title-dark font-semibold mb-[15px]">Tax Information</h5>
               <form action="{{route('save-tax')}}" method="POST" autocomplete="off">
                    @csrf
                  <div class="mb-[15px]">
                     <div class="mb-[0.125rem] block min-h-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" id="enableTax" value="1" name="enable_tax" @isset($billingTaxSetting->enable_tax){{$billingTaxSetting->enable_tax == 1 ? "checked" : ""}}@endisset>
                         <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="enableTax">
                             Enable Tax
                         </label>
                     </div>
                 </div>
                 
                  
                  <div class="mb-[15px]">
                      <label for="taxName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Name <span class="text-red-500">*</span>
                      </label>
                      <input type="text" value="@isset($billingTaxSetting->tax_name){{$billingTaxSetting->tax_name}}@endisset" id="taxName" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" name="tax_name" placeholder="Your Tax Name" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="taxAmountType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Amount Type <span class="text-red-500">*</span>
                      </label>
                      <select id="taxAmountType" name="tax_amount_type" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" required>
                          <option value="percentage" @isset($billingTaxSetting->tax_amount_type){{$billingTaxSetting->tax_amount_type == "percentage" ? 'selected' : ''}}@endisset>Percentage</option>
                          <option value="fixed" @isset($billingTaxSetting->tax_amount_type){{$billingTaxSetting->tax_amount_type == "fixed" ? 'selected' : ''}}@endisset>Fixed Amount</option>
                      </select>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="taxAmount" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Amount 
                      </label>
                      <input type="number" id="taxAmount" name="tax_amount" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="@isset($billingTaxSetting->tax_amount){{$billingTaxSetting->tax_amount}}@endisset"  placeholder="Your Tax Amount" >
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="taxType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Type 
                      </label>
                      <input type="text" id="taxType" name="tax_type" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="@isset($billingTaxSetting->tax_type){{$billingTaxSetting->tax_type}}@endisset" placeholder="Your Tax Type" >
                  </div>
                  
                  <div class="mb-[15px]">
                    
                     <div class="mb-[0.125rem] block min-h-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" id="enableAdditionalTax" value="1" name="enable_additional_tax" @isset($billingTaxSetting->enable_additional_tax){{$billingTaxSetting->enable_additional_tax == 1 ? "checked" : ""}}@endisset>
                         <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="enableAdditionalTax">
                             Enable Additional Tax
                         </label>
                     </div>
                 </div>
                 
                  <div class="mb-[15px]">
                      <label for="additionalTaxName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Name
                      </label>
                      <input type="text" id="additionalTaxName" name="additional_tax_name" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Additional Tax Name" value="@isset($billingTaxSetting->additional_tax_name){{$billingTaxSetting->additional_tax_name}}@endisset" >
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="additionalTaxAmountType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Amount Type 
                      </label>
                      <select id="additionalTaxAmountType" name="additional_tax_amount_type" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" required>
                          <option value="percentage" @isset($billingTaxSetting->additional_tax_amount_type){{$billingTaxSetting->additional_tax_amount_type == "percentage" ? 'selected' : ''}}@endisset>Percentage</option>
                          <option value="fixed" @isset($billingTaxSetting->additional_tax_amount_type){{$billingTaxSetting->additional_tax_amount_type == "fixed" ? 'selected' : ''}}@endisset>Fixed Amount</option>
                      </select>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="additionalTaxAmount"  class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Amount 
                      </label>
                      <input type="number" id="additionalTaxAmount" name="additional_tax_amount" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Additional Tax Amount" value="@isset($billingTaxSetting->additional_tax_amount){{$billingTaxSetting->additional_tax_amount}}@endisset">
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="additionalTaxType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Type 
                      </label>
                      <input type="text" id="additionalTaxType" name="additional_tax_type" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Additional Tax Type" value="@isset($billingTaxSetting->additional_tax_type){{$billingTaxSetting->additional_tax_type}}@endisset">
                  </div>
                  <div class="mb-[15px]">
                     <button type="submit" class=" bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                 </div>
               </form>
            </div>
         </div>
      </div> --}}
   </div>

</section>

@endsection
@push('scripts')
<script>
    
$(document).ready(function () {
    // Fetch states based on the selected country
    $('#country').on('change', function () {
        const countryId = $(this).val();
        if (countryId) {
            $.ajax({
                url: '{{ route("get-states") }}', // Define your route for fetching states
                type: 'GET',
                data: { country_id: countryId },
                success: function (response) {
                    $('#state').empty().append('<option selected disabled>Select State</option>');
                    if (response.states.length > 0) {
                        $.each(response.states, function (key, state) {
                            $('#state').append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                    }
                },
                error: function () {
                    alert('Failed to fetch states. Please try again.');
                }
            });
        }
    });

    // Fetch cities based on the selected state
    $('#state').on('change', function () {
        const stateId = $(this).val();
        if (stateId) {
            $.ajax({
                url: '{{ route("get-cities") }}', // Define your route for fetching cities
                type: 'GET',
                data: { state_id: stateId },
                success: function (response) {
                    $('#city').empty().append('<option selected disabled>Select City</option>');
                    if (response.cities.length > 0) {
                        $.each(response.cities, function (key, city) {
                            $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                        });
                    }
                },
                error: function () {
                    alert('Failed to fetch cities. Please try again.');
                }
            });
        }
    });
});

</script>
@endpush