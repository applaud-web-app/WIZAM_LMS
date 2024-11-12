@extends('layouts.master')

@section('title', 'Maintenance')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Maintenance Settings</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="{{route('admin-dashboard')}}">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Maintenance</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>

   <!-- Maintenance Actions -->
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <div class="p-[25px]">
         <p class="text-sm mb-4 text-body dark:text-subtitle-dark">Manage your application's maintenance settings here. These actions may temporarily affect the performance of your application, so proceed with caution.</p>

         <!-- Clear Cache Section -->
         <div class="mb-8">
            <h5 class="text-[18px] text-dark dark:text-title-dark font-semibold mb-2">Clear Cache</h5>
            <p class="text-sm mb-4 text-body dark:text-subtitle-dark">Use this action to clear all cached data from your application, including configuration, routes, and views. Clearing cache can help resolve issues when changes are not reflected immediately but may temporarily slow down your application as it regenerates the cache.</p>
            <button type="submit" class="w-full px-[30px] py-[10px] rounded bg-primary text-white capitalize hover:bg-primary-dark focus:ring-primary focus:outline-none">
               Clear Cache
            </button>
         </div>

         <!-- Maintenance Mode Section -->
         <div class="mb-8">
            <h5 class="text-[18px] text-dark dark:text-title-dark font-semibold mb-2">Maintenance Mode</h5>
            <p class="text-sm mb-4 text-body dark:text-subtitle-dark">Enabling maintenance mode will take your site offline and display a maintenance message to users. You can disable maintenance mode once your updates are complete to bring the site back online.</p>
            <form action="{{route('save-maintenance-setting')}}" method="POST">
               @csrf
               <div class="flex flex-col mb-4">
                  <label for="maintenance_mode" class="text-sm text-body dark:text-subtitle-dark mb-2">Select Maintenance Mode</label>
                  <select name="maintenance_mode" id="maintenance_mode" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2">
                     <option value="1" {{$generalSetting->maintenance_mode == "1" ? 'selected' : ''}}>Enable Maintenance Mode</option>
                     <option value="0" {{$generalSetting->maintenance_mode == "0" ? 'selected' : ''}}>Disable Maintenance Mode</option>
                  </select>
               </div>
               <button type="submit" class="w-full px-[30px] py-[10px] rounded bg-primary text-white capitalize hover:bg-primary-dark focus:ring-primary focus:outline-none">
                  Apply Maintenance Mode
               </button>
            </form>
         </div>
      </div>
   </div>
</section>
@endsection
