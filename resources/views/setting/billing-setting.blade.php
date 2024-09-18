@extends('layouts.master')

@section('title', 'Billing Settings')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Billing And Tax Settings</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="index.html">
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
      <div class="col-span-12 md:col-span-6">
         <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
            <div class="p-[25px]">
               <h5 class="text-[18px] text-dark dark:text-title-dark font-semibold mb-[15px]">Billing Information</h5>
               <form action="#">
                  <div class="mb-[15px]">
                      <label for="vendorName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Vendor Name <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="vendorName" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Vendor Name" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="address" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Address <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="address" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Address" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="city" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          City <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="city" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your City" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="state" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          State <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="state" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your State" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="country" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Country <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="country" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Country" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="zip" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Zip <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="zip" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Zip" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="phoneNumber" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Phone Number <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="phoneNumber" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Phone Number" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="vatNumber" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          VAT Number <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="vatNumber" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your VAT Number" required>
                  </div>
                  
                  <div class="mb-[15px]">
                    
                     <div class="mb-[0.125rem] block min-h-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" id="enableInvoicing">
                         <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="enableInvoicing">
                             Enable Invoicing
                         </label>
                     </div>
                 </div>
                  
                  <div class="mb-[15px]">
                      <label for="invoicePrefix" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Invoice Prefix
                      </label>
                      <input type="text" id="invoicePrefix" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Invoice Prefix">
                  </div>

                  <div class="mb-[15px]">
                     <button type="submit" class=" bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                 </div>

               </form>
            </div>
         </div>
      </div>

      <!-- Tax Information Card -->
      <div class="col-span-12 md:col-span-6">
         <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
            <div class="p-[25px]">
               <h5 class="text-[18px] text-dark dark:text-title-dark font-semibold mb-[15px]">Tax Information</h5>
               <form action="#">
                  <div class="mb-[15px]">
                    
                     <div class="mb-[0.125rem] block min-h-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" id="enableTax">
                         <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="enableTax">
                             Enable Tax
                         </label>
                     </div>
                 </div>
                 
                  
                  <div class="mb-[15px]">
                      <label for="taxName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Name <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="taxName" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Tax Name" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="taxAmountType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Amount Type <span class="text-red-500">*</span>
                      </label>
                      <select id="taxAmountType" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                          <option value="percentage">Percentage</option>
                          <option value="fixed">Fixed Amount</option>
                      </select>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="taxAmount" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Amount <span class="text-red-500">*</span>
                      </label>
                      <input type="number" id="taxAmount" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Tax Amount" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="taxType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Tax Type <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="taxType" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Tax Type" required>
                  </div>
                  
                  <div class="mb-[15px]">
                    
                     <div class="mb-[0.125rem] block min-h-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" id="enableAdditionalTax">
                         <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="enableAdditionalTax">
                             Enable Additional Tax
                         </label>
                     </div>
                 </div>
                 
                  <div class="mb-[15px]">
                      <label for="additionalTaxName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Name <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="additionalTaxName" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Additional Tax Name" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="additionalTaxAmountType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Amount Type <span class="text-red-500">*</span>
                      </label>
                      <select id="additionalTaxAmountType" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                          <option value="percentage">Percentage</option>
                          <option value="fixed">Fixed Amount</option>
                      </select>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="additionalTaxAmount" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Amount <span class="text-red-500">*</span>
                      </label>
                      <input type="number" id="additionalTaxAmount" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Additional Tax Amount" required>
                  </div>
                  
                  <div class="mb-[15px]">
                      <label for="additionalTaxType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                          Additional Tax Type <span class="text-red-500">*</span>
                      </label>
                      <input type="text" id="additionalTaxType" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Additional Tax Type" required>
                  </div>
                  <div class="mb-[15px]">
                     <button type="submit" class=" bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                 </div>
               </form>
            </div>
         </div>
      </div>
   </div>

</section>

@endsection
