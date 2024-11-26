@extends('layouts.master')

@section('title', 'Add Learning Lessons')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    
    
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">

            <!-- Breadcrumb Section -->
            <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-start items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                <!-- Title -->
                <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Add Learning Lessons</h4>
                <!-- Breadcrumb Navigation -->
                <div class="flex flex-wrap justify-center">
                    <nav>
                        <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                            <!-- Parent Link -->
                            <li class="inline-flex items-center mb-2">
                                <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-warning group" href="{{route('admin-dashboard')}}">
                                    <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                            </li>
                            <!-- Current Page -->
                            <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                                <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Add Lessons to Learning</span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <!-- Stepper Section with Card -->
            <div class="mb-[30px]">
                <!-- Card Container -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <!-- Step 1 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    1
                                </div>
                                <div class="text-primary mt-2">Choose SKill</div>
                            </div>
                        </div>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-primary"></div>
                        <!-- Step 2 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    2
                                </div>
                                <div class="text-gray-400 mt-2">Add/Remove Lesson</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Card -->
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        <form action="{{route('save-configure-lessons')}}" method="post" autocomplete="off">
            @csrf
            <div class="p-[25px]">

                <!-- Select Subcategory and Skills -->
                <div class="mb-[20px] grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Subcategory -->
                    <div>
                        <label for="subcategory" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Subcategory <span class="text-danger">*</span></label>
                        <select id="subcategory" name="subcategory" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                            <option selected disabled>Select Subcategory</option>
                            @isset($subcategory)
                                @foreach ($subcategory as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <!-- Skills -->
                    <div>
                        <label for="skill" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Skill <span class="text-danger">*</span></label>
                        <select id="skill" name="skill" required  class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                            <option selected disabled>Select Skill</option>
                            @isset($skill)
                                @foreach ($skill as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>

                <!-- Lesson List Section -->
                {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-[20px]">
                    <!-- Lesson Card 1 -->
                    <div class="border rounded-lg p-6 bg-yellow-50 dark:bg-box-dark-up hover:shadow-sm transition-shadow duration-300 ease-in-out">
                        <label class="inline-flex items-center mb-2">
                            <input class="relative ltr:float-left rtl:float-right me-[6px]  h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-warning checked:bg-warning checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-warning dark:checked:bg-warning after:top-[2px]" type="checkbox" value="" id="checkboxDefault" autocompleted="">
                            <span class="text-[16px] text-warning dark:text-title-dark">Lesson 1: Introduction to Web Development</span>
                        </label>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Watch Time: <span class="">10 Minutes</span></p>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Difficulty Level: <span class="">Beginner</span></p>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Lesson ID: <span class="">lesson_001</span></p>
                    </div>

                    <!-- Lesson Card 2 -->
                    <div class="border rounded-lg p-6 bg-yellow-50 dark:bg-box-dark-up hover:shadow-sm transition-shadow duration-300 ease-in-out">
                        <label class="inline-flex items-center mb-2">
                        <input class="relative ltr:float-left rtl:float-right me-[6px]  h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-warning checked:bg-warning checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-warning dark:checked:bg-warning after:top-[2px]" type="checkbox" value="" id="checkboxDefault" autocompleted="">
                            <span class="text-[16px] text-warning dark:text-title-dark">Lesson 2: Intermediate CSS</span>
                        </label>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Watch Time: <span class="">15 Minutes</span></p>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Difficulty Level: <span class="">Intermediate</span></p>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Lesson ID: <span class="">lesson_002</span></p>
                    </div>

                    <!-- Lesson Card 3 -->
                    <div class="border rounded-lg p-6 bg-yellow-50 dark:bg-box-dark-up hover:shadow-sm transition-shadow duration-300 ease-in-out">
                        <label class="inline-flex items-center mb-2">
                        <input class="relative ltr:float-left rtl:float-right me-[6px]  h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-warning checked:bg-warning checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-warning dark:checked:bg-warning after:top-[2px]" type="checkbox" value="" id="checkboxDefault" autocompleted="">
                            <span class="text-[16px] text-warning dark:text-title-dark">Lesson 3: JavaScript Advanced Concepts</span>
                        </label>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Watch Time: <span class="">20 Minutes</span></p>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Difficulty Level: <span class="">Advanced</span></p>
                        <p class="border-b py-2 text-sm text-body dark:text-subtitle-dark flex justify-between">Lesson ID: <span class="">lesson_003</span></p>
                    </div>

                    <!-- Add more lesson cards as needed -->
                </div> --}}

                <!-- Save Button -->
                <div class="flex justify-start mt-[20px]">
                    <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                        <i class="uil uil-save mr-2"></i> <!-- Save Icon (Unicons) -->
                        Proceed
                    </button>
                </div>

            </div>
        </form>
    </div>

</section>

@endsection
