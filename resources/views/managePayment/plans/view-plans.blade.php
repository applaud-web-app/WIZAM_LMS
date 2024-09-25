@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Plans</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="index.html">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Child (Current Page) -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Plans</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>

   <div class="grid grid-cols-12 gap-[25px]">
      <div class="col-span-12 2xl:col-span-3 sm:col-span-6 lg:col-span-4">

         <!-- BEGIN: free price-->
         <div class="bg-white dark:bg-box-dark p-7 shadow-pricing dark:shadow-none rounded-10"><span class="dark:text-title-dark bg-dark/10 capitalize dark:bg-box-dark-up dark:border-white/10 font-medium h-8 inline-block mb-8 px-6 py-1.5 rounded-2xl text-13 text-dark">Free Forever</span>

            <h1 class="relative bottom-1.5 mb-0 text-dark dark:text-title-dark text-4xl font-semibold capitalize flex items-center">

               free

            </h1>


            <span class="font-medium text-body dark:text-subtitle-dark text-13">For Individuals</span>
            <div class="mt-6 mb-4 min-h-[210px]">

               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]">
                  <span class="anticon text-success text-[14px] anticon-check">
                     <i class="uil uil-check"></i></span>
                  <span class="text-15">100MB File
                     Space</span>
               </div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]">
                  <span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span>
                  <span class="text-15">2 Active
                     Projects</span>
               </div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]">
                  <span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span>
                  <span class="text-15">Limited
                     Boards</span>
               </div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]">
                  <span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span>
                  <span class="text-15">Basic Project
                     Management</span>
               </div>

            </div>
            <button class=" bg-dark border-dark capitalize  dark:text-title-dark duration-200 font-semibold h-11 hover:bg-dark-hbr px-7 rounded-md text-sm text-white">
               <span>current plan</span>
            </button>
         </div>
         <!-- END: free price- -->

      </div>
      <div class="col-span-12 2xl:col-span-3 sm:col-span-6 lg:col-span-4">

         <!-- BEGIN: free price-->
         <div class="bg-white dark:bg-box-dark p-7 shadow-pricing dark:shadow-none rounded-10"><span class="dark:text-title-dark bg-primary/10 capitalize dark:bg-box-dark-up dark:border-white/10 font-medium h-8 inline-block mb-8 px-6 py-1.5 rounded-2xl text-13 text-primary">Business</span>

            <h1 class="relative bottom-1.5 mb-0 text-dark dark:text-title-dark text-4xl font-semibold capitalize flex items-center">

               <sup class="relative text-base font-semibold text-gray-400 -top-3">$</sup>
               19
               <sub class="relative bottom-0 ms-2.5 text-light dark:text-subtitle-dark text-13 font-normal capitalize">per
                  month</sub>

            </h1>


            <span class="font-medium text-body dark:text-subtitle-dark text-13">For 2 Users</span>
            <div class="mt-6 mb-4 min-h-[210px]">

               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">50GB File
                     Space</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">90 Projects</span>
               </div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Limited
                     Boards</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Basic Project
                     Management</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Custom Post
                     Types</span></div>

            </div>
            <button class=" bg-primary border-primary capitalize  dark:text-title-dark duration-200 font-semibold h-11 hover:bg-primary-hbr px-7 rounded-md text-sm text-white">
               <span>get started</span>
            </button>
         </div>
         <!-- END: free price- -->

      </div>
      <div class="col-span-12 2xl:col-span-3 sm:col-span-6 lg:col-span-4">

         <!-- BEGIN: free price-->
         <div class="bg-white dark:bg-box-dark p-7 shadow-pricing dark:shadow-none rounded-10"><span class="dark:text-title-dark bg-secondary/10 capitalize dark:bg-box-dark-up dark:border-white/10 font-medium h-8 inline-block mb-8 px-6 py-1.5 rounded-2xl text-13 text-secondary">Basic Plan</span>

            <h1 class="relative bottom-1.5 mb-0 text-dark dark:text-title-dark text-4xl font-semibold capitalize flex items-center">

               <sup class="relative text-base font-semibold text-gray-400 -top-3">$</sup>
               39
               <sub class="relative bottom-0 ms-2.5 text-light dark:text-subtitle-dark text-13 font-normal capitalize">per
                  month</sub>

            </h1>


            <span class="font-medium text-body dark:text-subtitle-dark text-13">For 10 Users</span>
            <div class="mt-6 mb-4 min-h-[210px]">

               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">300GB File Space
                  </span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">300 Active
                     Projects</span>
               </div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Limited Boards
                     Boards</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Basic Project
                     Management</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Custom Post
                     Types</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Subtasks
                     Types</span></div>

            </div>
            <button class=" bg-secondary border-secondary capitalize  dark:text-title-dark duration-200 font-semibold h-11 hover:bg-secondary-hbr px-7 rounded-md text-sm text-white">
               <span>get started</span>
            </button>
         </div>
         <!-- END: free price- -->

      </div>
      <div class="col-span-12 2xl:col-span-3 sm:col-span-6 lg:col-span-4">

         <!-- BEGIN: free price-->
         <div class="bg-white dark:bg-box-dark p-7 shadow-pricing dark:shadow-none rounded-10"><span class="dark:text-title-dark bg-success/10 capitalize dark:bg-box-dark-up dark:border-white/10 font-medium h-8 inline-block mb-8 px-6 py-1.5 rounded-2xl text-13 text-success">Enterprise</span>

            <h1 class="relative bottom-1.5 mb-0 text-dark dark:text-title-dark text-4xl font-semibold capitalize flex items-center">

               <sup class="relative text-base font-semibold text-gray-400 -top-3">$</sup>
               79
               <sub class="relative bottom-0 ms-2.5 text-light dark:text-subtitle-dark text-13 font-normal capitalize">per
                  month</sub>

            </h1>


            <span class="font-medium text-body dark:text-subtitle-dark text-13">For 50 Users</span>
            <div class="mt-6 mb-4 min-h-[210px]">

               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Unlimited File Space
                  </span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Unlimited Projects</span>
               </div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Limited Boards
                     Boards</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Basic Project
                     Management</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Custom Post
                     Types</span></div>
               <div class="flex items-center mb-3 text-body dark:text-subtitle-dark gap-[15px]"><span class="anticon text-success text-[14px] anticon-check"><i class="uil uil-check"></i></span><span class="text-15">Subtasks
                     Types</span></div>

            </div>
            <button class=" bg-success border-success capitalize  dark:text-title-dark duration-200 font-semibold h-11 hover:bg-success-hbr px-7 rounded-md text-sm text-white">
               <span>get started</span>
            </button>
         </div>
         <!-- END: free price- -->

      </div>

   </div>


</section>


@endsection
