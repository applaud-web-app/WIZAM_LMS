@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    
        <section class=" mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">


           <div class="grid grid-cols-12 gap-5">
              <div class="col-span-12">

                 <!-- Breadcrumb Section -->
                 <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Dashboard</h4>
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

                             <!-- Child (Current Page) -->
                             <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                                <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">home page</span>
                             </li>
                          </ol>
                       </nav>
                    </div>
                 </div>

              </div>
           </div>
           <div class="grid grid-cols-12 gap-[25px]">
              <div class="col-span-12 2xl:col-span-12">
                 <div class="grid grid-cols-12 gap-[25px]">
                    <div class="col-span-12 xl:col-span-4 lg:col-span-3 sm:col-span-6">
                       <div class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                          <div class="flex justify-between">
                             <div class="bg-primary/10 flex h-[58px] items-center justify-center rounded-2xl text-primary w-[58px] order-2">
                                <div class="flex items-center text-primary text-[30px]">
                                   <i class="uil uil-suitcase"></i>
                                </div>
                             </div>
                             <div>
                                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                   <span class="flex items-center countCategories" data-number="100">


                                      <span class="countNumber">100</span>

                                      <span>+</span>

                                   </span>
                                </h4>
                                <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Users</span>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="col-span-12 xl:col-span-4 lg:col-span-3 sm:col-span-6">
                       <div class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                          <div class="flex justify-between">
                             <div class="bg-info/10 flex h-[58px] items-center justify-center rounded-2xl text-info w-[58px] order-2">
                                <div class="flex items-center text-info text-[30px]">
                                   <i class="uil uil-shopping-cart-alt"></i>
                                </div>
                             </div>
                             <div>
                                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                   <span class="flex items-center countCategories" data-number="125">


                                       <span>$</span>
                                      <span class="countNumber">125</span>


                                   </span>
                                </h4>
                                <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Revenue</span>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="col-span-12 xl:col-span-4 lg:col-span-3 sm:col-span-6">
                       <div class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                          <div class="flex justify-between">
                             <div class="bg-warning/10 flex h-[58px] items-center justify-center rounded-2xl text-warning w-[58px] order-2">
                                <div class="flex items-center text-warning text-[30px]">
                                   <i class="uil uil-users-alt"></i>
                                </div>
                             </div>
                             <div>
                                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                   <span class="flex items-center countCategories" data-number="1211">

                                      <span class="countNumber">1211</span>

                                      <span>+</span>

                                   </span>
                                </h4>
                                <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Question</span>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="col-span-12 xl:col-span-4 lg:col-span-3 sm:col-span-6">
                     <div class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                        <div class="flex justify-between">
                           <div class="bg-secondary/10 flex h-[58px] items-center justify-center rounded-2xl text-secondary w-[58px] order-2">
                              <div class="flex items-center text-secondary text-[30px]">
                                 <i class="uil uil-usd-circle"></i>
                              </div>
                           </div>
                           <div>
                              <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                 <span class="flex items-center countCategories" data-number="854">

                                   

                                    <span class="countNumber">854</span>


                                 </span>
                              </h4>
                              <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Quizzes</span>
                           </div>
                        </div>
                     </div>
                     </div>
                     <div class="col-span-12 xl:col-span-4 lg:col-span-3 sm:col-span-6">
                        <div class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                           <div class="flex justify-between">
                              <div class="bg-warning/10 flex h-[58px] items-center justify-center rounded-2xl text-warning w-[58px] order-2">
                                 <div class="flex items-center text-warning text-[30px]">
                                    <i class="uil uil-users-alt"></i>
                                 </div>
                              </div>
                              <div>
                                 <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                    <span class="flex items-center countCategories" data-number="1211">


                                       <span class="countNumber">1211</span>

                                       <span>+</span>

                                    </span>
                                 </h4>
                                 <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Practice Sets</span>
                              </div>
                           </div>
                        </div>
                     </div>
                 </div>
              </div>
              {{-- <div class="col-span-12 2xl:col-span-4">

                 <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                       <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                          sales Growth
                       </h2>
                       <ul class="py-[16px] max-ssm:py-[10px] flex items-center max-xs:flex-wrap max-xs:justify-center" role="tablist" data-te-nav-ref>
                          <li role="presentation">
                             <a data-te-toggle="pill" data-te-target="#today" data-te-nav-active role="tab" aria-controls="today" aria-selected="true" href="#today" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                today
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#week" data-te-toggle="pill" data-te-target="#week" role="tab" aria-controls="week" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Week
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#month" data-te-toggle="pill" data-te-target="#month" role="tab" aria-controls="month" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Month
                             </a>
                          </li>
                       </ul>
                    </div>
                    <div class="p-[25px] pt-0">
                       <div class="flex items-center justify-center max-ssm:flex-col flex-wrap max-ssm:gap-y-[15px]">
                          <div class="relative flex items-center mx-3"><span class="inline-block text-dark dark:text-title-dark me-1 ms-2.5 text-[18px] font-semibold">$8,550</span><span class="flex items-center text-sm font-medium text-success">
                                <i class="uil uil-arrow-up text-[18px]"></i> 25%
                             </span>
                          </div>
                          <div class="relative flex items-center mx-3"><span class="inline-block text-dark dark:text-title-dark me-1 ms-2.5 text-[18px] font-semibold">$5,550</span><span class="flex items-center text-sm font-medium text-danger">
                                <i class="uil uil-arrow-down text-[18px]"></i> 15%
                             </span>
                          </div>
                       </div>
                       <div id="today" role="tabpanel" data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                          <div dir="ltr" class="salesGrowthToday"></div>
                       </div>
                       <div id="week" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                          <div dir="ltr" class="salesGrowthWeek"></div>
                       </div>
                       <div id="month" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                          <div dir="ltr" class="salesGrowthMonth"></div>
                       </div>
                    </div>

                 </div>

              </div>
              <div class="col-span-12 2xl:col-span-8">

                 <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative xl:h-full">
                    <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px] border-b border-regular dark:border-white/10 max-ssm:gap-[5px]">
                       <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                          Sales By Location
                       </h2>
                       <div class="ssm:py-[16px] flex items-center ssm:gap-[15px] gap-[5px] max-xs:flex-wrap max-xs:justify-center max-ssm:flex-col">
                          <ul class="flex items-center" role="tablist" data-te-nav-ref>
                             <li role="presentation">
                                <a data-te-toggle="pill" data-te-target="#location-today" data-te-nav-active role="tab" aria-controls="location-today" aria-selected="true" href="#location-today" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                   today
                                </a>
                             </li>
                             <li role="presentation">
                                <a href="#location-week" data-te-toggle="pill" data-te-target="#location-week" role="tab" aria-controls="location-week" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                   Week
                                </a>
                             </li>
                             <li role="presentation">
                                <a href="#location-month" data-te-toggle="pill" data-te-target="#location-month" role="tab" aria-controls="location-month" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                   Month
                                </a>
                             </li>
                             <li role="presentation">
                                <a href="#location-year" data-te-toggle="pill" data-te-target="#location-year" role="tab" aria-controls="location-year" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                   year
                                </a>
                             </li>
                          </ul>
                          <div class="relative" data-te-dropdown-ref>
                             <button class="text-[18px] text-light dark:text-subtitle-dark" type="button" id="salesByLocation" data-te-dropdown-toggle-ref aria-expanded="false">
                                <i class="uil uil-ellipsis-h"></i>
                             </button>
                             <ul class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down  [&[data-te-dropdown-show]]:block" aria-labelledby="salesByLocation" data-te-dropdown-menu-ref>
                                <li>
                                   <a class="block w-full px-4 py-2 text-sm font-normal capitalize bg-transparent whitespace-nowrap text-neutral-700 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-subtitle-dark dark:hover:bg-box-dark-up gap-[6px]" href="#" data-te-dropdown-item-ref>
                                      <i class="uil uil-print"></i> printer </a>
                                </li>
                                <li>
                                   <a class="block w-full px-4 py-2 text-sm font-normal capitalize bg-transparent whitespace-nowrap text-neutral-700 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-subtitle-dark dark:hover:bg-box-dark-up" href="#" data-te-dropdown-item-ref>
                                      <i class="uil uil-book-alt"></i> pdf </a>
                                </li>
                                <li>
                                   <a class="block w-full px-4 py-2 text-sm font-normal capitalize bg-transparent whitespace-nowrap text-neutral-700 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-subtitle-dark dark:hover:bg-box-dark-up" href="#" data-te-dropdown-item-ref>
                                      <i class="uil uil-file-info-alt"></i> google sheet </a>
                                </li>
                                <li>
                                   <a class="block w-full px-4 py-2 text-sm font-normal capitalize bg-transparent whitespace-nowrap text-neutral-700 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-subtitle-dark dark:hover:bg-box-dark-up" href="#" data-te-dropdown-item-ref>
                                      <i class="uil uil-multiply"></i> Excel (XLSX) </a>
                                </li>
                                <li>
                                   <a class="block w-full px-4 py-2 text-sm font-normal capitalize bg-transparent whitespace-nowrap text-neutral-700 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-subtitle-dark dark:hover:bg-box-dark-up" href="#" data-te-dropdown-item-ref>
                                      <i class="uil uil-file"></i> CSV </a>
                                </li>
                             </ul>
                          </div>
                       </div>
                    </div>
                    <div class="px-[25px] pt-[25px] pb-[57px]">
                       <div class="grid grid-cols-12 gap-y-[25px]">
                          <div class="col-span-12 md:col-span-6">
                             <div class="max-h-[280px] scrollbar relative overflow-hidden border-1 border-solid border-regular dark:border-white/10 rounded-[5px] hover:overflow-y-auto transition duration-300 ease-linear">
                                <div id="location-today" role="tabpanel" data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                                   <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                      <thead>
                                         <tr>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-bl-[0] rounded-s-[4px] sticky top-0">
                                               Top Region</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden sticky top-0">
                                               Order</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-br-[0] rounded-e-[4px] sticky top-0">
                                               Revenue</th>
                                         </tr>
                                      </thead>
                                      <tbody>
                                         <tr>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               90</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $536</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $573</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Japan</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               30</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $573</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               50</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               90</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $536</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $573</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                      </tbody>
                                   </table>
                                </div>
                                <div id="location-week" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                                   <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                      <thead>
                                         <tr>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-bl-[0] rounded-s-[4px]">
                                               Top Region</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden">
                                               Order</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-br-[0] rounded-e-[4px]">
                                               Revenue</th>
                                         </tr>
                                      </thead>
                                      <tbody>
                                         <tr>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               100</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $546</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $583</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $467</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Japan</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               40</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $583</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               60</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               100</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $546</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $583</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $467</td>
                                         </tr>
                                      </tbody>
                                   </table>
                                </div>
                                <div id="location-month" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                                   <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                      <thead>
                                         <tr>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-bl-[0] rounded-s-[4px]">
                                               Top Region</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden">
                                               Order</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-br-[0] rounded-e-[4px]">
                                               Revenue</th>
                                         </tr>
                                      </thead>
                                      <tbody>
                                         <tr>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               90</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $536</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $573</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Japan</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               30</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $573</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               50</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               90</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $536</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $573</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               70</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                      </tbody>
                                   </table>
                                </div>
                                <div id="location-year" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                                   <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                      <thead>
                                         <tr>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-bl-[0] rounded-s-[4px]">
                                               Top Region</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden">
                                               Order</th>
                                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-12 font-medium border-none before:hidden rounded-br-[0] rounded-e-[4px]">
                                               Revenue</th>
                                         </tr>
                                      </thead>
                                      <tbody>
                                         <tr>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               100</td>
                                            <td class="px-4 pb-2.5 pt-[20px] text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $546</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $583</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $467</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Japan</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               40</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $583</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               60</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $457</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United states</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               100</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $546</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               United kingdom</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $583</td>
                                         </tr>
                                         <tr>
                                            <td class="px-4 py-2.5 text-start text-dark dark:text-title-dark dark:group-hover:bg-box-dark-up text-15 font-medium border-none before:hidden whitespace-nowrap">
                                               Canada</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               80</td>
                                            <td class="px-4 py-2.5 text-start text-body dark:text-white/[60] dark:group-hover:bg-box-dark-up border-none before:hidden">
                                               $467</td>
                                         </tr>
                                      </tbody>
                                   </table>
                                </div>
                             </div>
                          </div>
                          <div class="col-span-12 md:col-span-6">
                             <div id="svgMap" class="h-full"></div>
                          </div>
                       </div>
                    </div>

                 </div>

              </div> --}}
              <div class="col-span-12 2xl:col-span-6">

                 <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
                    <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                       <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                          New Users
                       </h2>
                       <ul class="py-[16px] max-ssm:py-[10px] flex items-center max-xs:flex-wrap max-xs:justify-center" role="tablist" data-te-nav-ref>
                          <li role="presentation">
                             <a data-te-toggle="pill" data-te-target="#tsp-today" data-te-nav-active role="tab" aria-controls="tsp-today" aria-selected="true" href="#tsp-today" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Monthly
                             </a>
                          </li>
                          {{-- <li role="presentation">
                             <a href="#tsp-week" data-te-toggle="pill" data-te-target="#tsp-week" role="tab" aria-controls="tsp-week" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Week
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#tsp-month" data-te-toggle="pill" data-te-target="#tsp-month" role="tab" aria-controls="tsp-month" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Month
                             </a>
                          </li> --}}
                       </ul>
                    </div>
                    <div class="p-[25px] pt-0">
                       <div class="max-h-[320px] scrollbar overflow-y-auto">
                          <div id="tsp-today" role="tabpanel" data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                             <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                <thead>
                                   <tr class="whitespace-nowrap">
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                         USER NAME</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         ROLE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         COUNTRY</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                         STATUS</th>
                                   </tr>
                                </thead>
                                <tbody>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/1.png')}}" alt="Samsung Galaxy S8 256GB"></div>
                                            <span class="font-medium capitalize text-dark dark:text-title-dark text-15">Samsung
                                               Galaxy S8 256GB</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $280</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         126</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $38,536</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/2.png')}}" alt="Half Sleeve Shirt"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Half
                                               Sleeve Shirt</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $39</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         180</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $20,573</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/3.png')}}" alt="Marco Shoes"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Marco
                                               Shoes</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $89</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         581</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $170,758</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/5.png')}}" alt="Marco Shoes"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Marco
                                               Shoes</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $32</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         451</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $258,105</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/4.png')}}" alt="15&quot; MackBook Pro"></div>
                                            <span class="font-medium capitalize text-dark dark:text-title-dark text-15">15"
                                               MackBook Pro</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $950</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         745</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $745,154</td>
                                   </tr>
                                </tbody>
                             </table>
                          </div>
                          {{-- <div id="tsp-week" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                             <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                <thead>
                                   <tr class="whitespace-nowrap">
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                         PRODUCTS NAME</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         PRICE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         SOLD</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                         REVENUE</th>
                                   </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-box-dark">
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/1.png')}}" alt="Samsung Note 10 256GB"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Samsung
                                               Note
                                               10 256GB</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $450</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         300</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $43060</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/2.png')}}" alt="Full Sleeve Shirt"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Full
                                               Sleeve
                                               Shirt</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $49</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         300</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $3178</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/3.png')}}" alt="Nike Shoes"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Nike
                                               Shoes</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $89</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         581</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $170,758</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/5.png')}}" alt="Bata Shoes"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Bata
                                               Shoes</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $32</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         451</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $258,105</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/4.png')}}" alt="15&quot; MackBook Pro"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">15"
                                               MackBook
                                               Pro</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $950</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         745</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $745,154</td>
                                   </tr>
                                </tbody>
                             </table>
                          </div>
                          <div id="tsp-month" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                             <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                <thead>
                                   <tr class="whitespace-nowrap">
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                         PRODUCTS NAME</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         PRICE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         SOLD</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                         REVENUE</th>
                                   </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-box-dark">
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/1.png')}}" alt="Samsung Galaxy Premio 256GB">
                                            </div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Samsung
                                               Galaxy Premio 256GB</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $670</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         126</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $40,988</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/2.png')}}" alt="Half Sleeve Shirt"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Half
                                               Sleeve Shirt</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $39</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         180</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $20,573</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/3.png')}}" alt="Marco Shoes"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Marco
                                               Shoes</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $89</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         581</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $170,758</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/5.png')}}" alt="Marco Shoes"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Marco
                                               Shoes</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $32</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         451</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $258,105</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/electronics/4.png')}}" alt="15&quot; MackBook Pro"></div>
                                            <span class="font-medium capitalize text-dark dark:text-title-dark text-15">15"
                                               MackBook Pro</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         $950</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         745</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         $745,154</td>
                                   </tr>
                                </tbody>
                             </table>
                          </div> --}}
                       </div>
                    </div>

                 </div>

              </div>
              <div class="col-span-12 2xl:col-span-6">

                 <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
                    <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                       <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                          Recently Added Quizzes
                       </h2>
                       <ul class="py-[16px] max-ssm:py-[10px] flex items-center max-xs:flex-wrap max-xs:justify-center" role="tablist" data-te-nav-ref>
                          <li role="presentation">
                             <a data-te-toggle="pill" data-te-target="#bs-today" data-te-nav-active role="tab" aria-controls="bs-today" aria-selected="true" href="#bs-today" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Monthly
                             </a>
                          </li>
                          {{-- <li role="presentation">
                             <a href="#bs-week" data-te-toggle="pill" data-te-target="#bs-week" role="tab" aria-controls="bs-week" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Week
                             </a>
                          </li>
                          <li role="presentation">
                             <a href="#bs-month" data-te-toggle="pill" data-te-target="#bs-month" role="tab" aria-controls="bs-month" aria-selected="false" class="inline-flex items-center h-8 px-3 font-medium capitalize rounded-md text-13 dark:hover:text-white dark:text-subtitle-dark hover:text-primary text-light data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary data-[te-nav-active]:bg-primary/10 dark:data-[te-nav-active]:text-title-dark dark:data-[te-nav-active]:bg-box-dark-up">
                                Month
                             </a>
                          </li> --}}
                       </ul>
                    </div>
                    <div class="p-[25px] pt-0">
                       <div class="max-h-[320px] scrollbar overflow-y-auto">
                          <div id="bs-today" role="tabpanel" data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                             <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                <thead>
                                   <tr class="whitespace-nowrap">
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                         TITLE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         TYPE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         Visibility</th>
                                   </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-box-dark">
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/chrome.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         5.5%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/firefox.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         1.5%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/internet-explorer.png')}}" alt="Google Chrome"></div>
                                            <span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         5.5%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/opera.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/safari.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                </tbody>
                             </table>
                          </div>
                          {{-- <div id="bs-week" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                             <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                <thead>
                                   <tr class="whitespace-nowrap">
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                         BROWSERS</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         SESSION</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         BOUNCE RATE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         CTE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                         GOAL CONV. RATE</th>
                                   </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-box-dark">
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/chrome.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         4.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/firefox.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         8.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/internet-explorer.png')}}" alt="Google Chrome"></div>
                                            <span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         7.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/opera.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         0.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/safari.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         1.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         12025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                </tbody>
                             </table>
                          </div>
                          <div id="bs-month" role="tabpanel" class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                             <table class="min-w-full text-sm font-light text-left whitespace-nowrap">
                                <thead>
                                   <tr class="whitespace-nowrap">
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                         BROWSERS</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         SESSION</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         BOUNCE RATE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                         CTE</th>
                                      <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                         GOAL CONV. RATE</th>
                                   </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-box-dark">

                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/chrome.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         4.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         13025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/firefox.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         7.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         13025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/internet-explorer.png')}}" alt="Google Chrome"></div>
                                            <span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         2.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         14025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/opera.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         4.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         16025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                   <tr class="group">
                                      <td class="ps-0 pe-4 py-2.5 text-start last:text-end text-dark dark:text-title-dark group-hover:bg-transparent text-15 font-medium border-none before:hidden rounded-s-[4px]">
                                         <div class="flex items-center">
                                            <div class="me-2.5 w-[34px] h-[34px]"><img class="min-w-[34px] h-[34px] rounded-4" src="{{asset('assets/images/browser/safari.png')}}" alt="Google Chrome"></div><span class="font-medium capitalize text-dark dark:text-title-dark text-15">Google
                                               Chrome</span>
                                         </div>
                                      </td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         92,345</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         5.5%</td>
                                      <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                         11025</td>
                                      <td class="ps-4 pe-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                         90%</td>
                                   </tr>
                                </tbody>
                             </table>
                          </div> --}}
                       </div>
                    </div>

                 </div>


              </div>
           </div>

        </section>

       

    
@endsection
