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
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Dashboard</h4>
                    <!-- Breadcrumb Navigation -->
                    <div class="flex flex-wrap justify-center">
                        <nav>
                            <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                                <!-- Parent Link -->
                                <li class="inline-flex items-center">
                                    <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group"
                                        href="index.html">
                                        <i
                                            class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                                </li>
                                <!-- Middle (Conditional) -->

                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">home
                                        page</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>
        <div class="grid grid-cols-12 gap-[25px]">
            <div class="col-span-12 xl:col-span-3 lg:col-span-3 sm:col-span-6">
                <div
                    class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                    <div class="flex justify-between">
                        <div
                            class="bg-primary/10 flex h-[58px] items-center justify-center rounded-2xl text-primary w-[58px] order-2">
                            <div class="flex items-center text-primary text-[30px]">
                                <i class="uil uil-suitcase"></i>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
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
            <div class="col-span-12 xl:col-span-3 lg:col-span-3 sm:col-span-6">
                <div
                    class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                    <div class="flex justify-between">
                        <div
                            class="bg-warning/10 flex h-[58px] items-center justify-center rounded-2xl text-warning w-[58px] order-2">
                            <div class="flex items-center text-warning text-[30px]">
                                <i class="uil uil-users-alt"></i>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories" data-number="1211">

                                    <span class="countNumber">1211</span>

                                    <span>+</span>

                                </span>
                            </h4>
                            <span class="font-normal text-body dark:text-subtitle-dark text-15">Total
                                Question</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-3 lg:col-span-3 sm:col-span-6">
                <div
                    class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                    <div class="flex justify-between">
                        <div
                            class="bg-secondary/10 flex h-[58px] items-center justify-center rounded-2xl text-secondary w-[58px] order-2">
                            <div class="flex items-center text-secondary text-[30px]">
                                <i class="uil uil-usd-circle"></i>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories" data-number="854">



                                    <span class="countNumber">854</span>


                                </span>
                            </h4>
                            <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Quizzes</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-3 lg:col-span-3 sm:col-span-6">
                <div
                    class="p-[25px] bg-white dark:bg-box-dark rounded-10 relative text-[15px] text-body dark:text-subtitle-dark leading-6">
                    <div class="flex justify-between">
                        <div
                            class="bg-warning/10 flex h-[58px] items-center justify-center rounded-2xl text-warning w-[58px] order-2">
                            <div class="flex items-center text-warning text-[30px]">
                                <i class="uil uil-users-alt"></i>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories" data-number="1211">


                                    <span class="countNumber">1211</span>

                                    <span></span>

                                </span>
                            </h4>
                            <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Practice
                                Sets</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-[25px] mt-5">
            <div class="col-span-12 2xl:col-span-12 bg-primary p-5 rounded">
               {{-- <h4 class="mb-0 text-xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-white white:text-title-white">Quick Links</h4> --}}
               <div class="col-span-12 2xl:col-span-12 bg-primary p-5 rounded">
                  <ul class="mt-4 flex flex-wrap justify-center gap-4">
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        New Quiz Schedule
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Create New Quiz
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Create Practice Set
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        View Quizzes
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        View Practice Sets
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Create New Question
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Import Questions
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Create New Comprehension
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Create New Skill
                     </li>
                     <li class="bg-white text-dark dark:text-title-dark rounded-lg shadow-md p-4 transition-transform transform hover:scale-105 mb-2">
                        Create New Topic
                     </li>
                  </ul>
               </div>
            </div>
        </div>
    </section>
@endsection