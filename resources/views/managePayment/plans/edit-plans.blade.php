@extends('layouts.master')

@section('title', 'Edit Plan')

@section('content')

    <section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">

                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-start items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Edit Plan</h4>
                    <!-- Breadcrumb Navigation -->
                    <div class="flex flex-wrap justify-center">
                        <nav>
                            <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                                <!-- Parent Link -->
                                <li class="inline-flex items-center">
                                    <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group"
                                        href="">
                                        <i
                                            class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                                </li>
                                <!-- Current Page -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Edit
                                        Plan</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <!-- Form Section -->
        <div class="grid grid-cols-12 gap-[25px]">
            <div class="col-span-12">
                <!-- Form Card -->
                <div class="bg-white dark:bg-box-dark p-7 shadow-pricing dark:shadow-none rounded-10">
                    <form action="{{url()->full()}}" method="POST" class="grid grid-cols-12 gap-[25px]">
                        @csrf
                        <!-- Plan Name -->
                        <div class="col-span-12">
                            <label for="plan_name"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Plan Name<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="plan_name" id="plan_name"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="{{ $plan->name}}"
                                placeholder="Enter plan name" required>
                        </div>
                        <!-- Price Type (Fixed/Monthly) -->
                        <div class="col-span-12">
                            <label class="block text-sm font-medium text-body dark:text-title-dark mb-2">Price Type<span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                <!-- First Radio (Fixed) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="price_type" id="price_type_fixed" value="fixed" required {{ $plan->price_type == 'fixed' ? 'checked' : '' }}
                                        checked>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="price_type_fixed">Fixed</label>
                                </div>

                                <!-- Second Radio (Monthly) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="price_type" id="price_type_monthly" value="monthly" 
                                        required {{ $plan->price_type == 'monthly' ? 'checked' : '' }}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="price_type_monthly">Monthly</label>
                                </div>
                            </div>
                        </div>
                        <!-- Duration (hidden)-->
                        <div class="col-span-6">
                            <label for="duration"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Duration (Months)
                                <span class="text-red-500">*</span></label>
                            <input type="number" step="1" name="duration" id="duration"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter Duration" value="{{$plan->duration}}" min="1" required>
                        </div>
                        <!-- Price -->
                        <div class="col-span-6">
                            <label for="price"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Price<span
                                    class="text-red-500">*</span></label>
                            <input type="number" value="{{$plan->price}}" min="1" name="price" id="price"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter price" required>
                        </div>
                        <!-- Discount -->
                        <div class="col-span-12">
                            <label class="block text-sm font-medium text-body dark:text-title-dark mb-2">Discount <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                <!-- First Radio (Fixed) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="discount" id="discount" value="1" required {{ $plan->discount > 0  ? 'checked' : '' }}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="discount">Yes</label>
                                </div>

                                <!-- Second Radio (Monthly) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="discount" id="discount" value="0" required {{ $plan->discount == 0 ? 'checked' : '' }}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="discount">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- Discount -->
                        <div class="col-span-12 {{ $plan->discount ? '' : 'hidden' }}" id="discountContainer">
                            <label for="discount"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Discount Percentage
                                <span class="text-red-500">*</span></label>
                            <input type="number" value="1" min="1" name="discount_percentage" id="discount"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="{{$plan->discount == 0 ? 0 : $plan->discount_percentage}}"
                                placeholder="Enter Discount" required>
                        </div>

                        <!-- Category Dropdown -->
                        <div class="col-span-12">
                            <label for="category"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Category<span
                                    class="text-red-500">*</span></label>
                            <select id="category" name="category"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark">
                                <option value="" disabled selected>Select Category</option>
                                @foreach ($subCategory as $category)
                                    <option value="{{ $category->id }}" {{$plan->category_id == $category->id ? 'selected':''}}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dynamic Feature Data -->
                        <div class="col-span-12 " id="feature_access">
                            
                            <!-- Exams -->
                            <div class="col-span-12 sm:col-span-12 md:col-span-12 mb-[20px]">
                                <label for="features"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Exams
                                    <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1">
                                    <select id="exams_id" name="exams[]" data-te-select-init
                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                        data-te-select-placeholder="Select Exams" multiple>
                                        @isset($exams)
                                            @php $planExam = json_decode($plan->exams,true); @endphp
                                            @foreach ($exams as $item)
                                                <option value="{{$item->id}}" {{ in_array($item->id, $planExam) ? 'selected' : '' }}>
                                                    {{$item->title}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                             <!-- Quizzes -->
                             <div class="col-span-12 sm:col-span-12 md:col-span-12 mb-[20px]">
                                <label for="features"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Quizzes <span
                                        class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1">
                                    <select id="quizzes_id" name="quizzes[]" data-te-select-init
                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                        data-te-select-placeholder="Select Quizzes" multiple>
                                        @isset($quizzes)
                                            @php $planQuiz = json_decode($plan->quizzes,true); @endphp
                                            @foreach ($quizzes as $item)
                                                <option value="{{$item->id}}" {{ in_array($item->id, $planQuiz) ? 'selected' : '' }}>
                                                    {{$item->title}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <!-- Practice Sets -->
                            <div class="col-span-12 sm:col-span-12 md:col-span-12 mb-[20px]">
                                <label for="features"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Practice Sets
                                    <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1">
                                    <select id="practice_sets" name="practice_sets[]" data-te-select-init
                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                        data-te-select-placeholder="Select Practice Sets" multiple>
                                        @isset($practices)
                                            @php $planPractices = json_decode($plan->practices,true); @endphp
                                            @foreach ($practices as $item)
                                                <option value="{{$item->id}}" {{ in_array($item->id, $planPractices) ? 'selected' : '' }}>
                                                    {{$item->title}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <!-- Lessons -->
                            <div class="col-span-12 sm:col-span-12 md:col-span-12 mb-[20px]">
                                <label for="features"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Lessons <span
                                        class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1">
                                    <select id="lessons_id" name="lessons[]" data-te-select-init
                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                        data-te-select-placeholder="Select Lessons" multiple>
                                        @isset($lessons)
                                            @php $planLessons = json_decode($plan->lessons,true); @endphp
                                            @foreach ($lessons as $item)
                                                <option value="{{$item->id}}" {{ in_array($item->lesson_id, $planLessons) ? 'selected' : '' }}>
                                                    {{$item->lesson->title}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <!-- Videos -->
                            <div class="col-span-12 sm:col-span-12 md:col-span-12 mb-[20px]">
                                <label for="features"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Videos
                                    <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1">
                                    <select id="videos_id" name="videos[]" data-te-select-init
                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                        data-te-select-placeholder="Select Videos" multiple>
                                        @isset($videos)
                                            @php $planVideos = json_decode($plan->videos,true); @endphp
                                            @foreach ($videos as $item)
                                                <option value="{{$item->id}}" {{ in_array($item->video_id, $planVideos) ? 'selected' : '' }}>
                                                    {{$item->video->title}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>


                        </div>
                        <!-- Short Description -->
                        <div class="col-span-12">
                            <label for="description"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Short
                                Description
                                (Max
                                200 Characters)</label>
                            <textarea name="description" id="description" maxlength="200" rows="4"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" maxlength="100"
                                placeholder="Enter a short description">{{$plan->description}}</textarea>
                        </div>
                        <!-- Sort Order -->
                        <div class="col-span-12">
                            <label for="order"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Sort
                                Order<span class="text-red-500">*</span></label>
                            <input type="number" name="order" min="1" id="order"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter sort order" value="{{$plan->sort_order}}" required>
                        </div>
                        <!-- Popular - Yes Checkbox -->
                        <div class="col-span-12">
                            <label class="block text-sm font-medium text-body dark:text-title-dark mb-2">Popular</label>
                            <div class="flex flex-wrap items-center gap-[10px]">
                                <!-- First Radio (Enable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="popular" id="popular" value="1" {{ $plan->popular == 1 ? 'checked' : '' }} required>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="popular">Enable</label>
                                </div>

                                <!-- Second Radio (Disable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="popular" id="popular" value="0" {{ $plan->popular == 0 ? 'checked' : '' }} required>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="popular">Disable</label>
                                </div>
                            </div>
                        </div>
                        <!-- Status Radio Buttons (Enable/Disable) -->
                        <div class="mb-[15px] col-span-12">
                            <label for="status"
                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap items-center gap-[10px]">
                                <!-- First Radio (Enable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="status" id="status" value="1" required {{ $plan->status == 1 ? 'checked' : '' }}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="status">Enable</label>
                                </div>

                                <!-- Second Radio (Disable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="status" id="status" value="0" required {{ $plan->status == 0 ? 'checked' : '' }}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="status">Disable</label>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="col-span-12">
                            <button type="submit"
                                class="px-[30px] py-[10px] rounded bg-primary text-white capitalize hover:bg-primary-dark focus:ring-primary focus:outline-none">
                                Create Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        // Monitor changes on the price type radio buttons
        $('input[name="price_type"]').change(function () {
            if ($(this).val() === 'monthly') {
                $('#durationContainer').removeClass('hidden'); // Show the duration field
            } else {
                $('#durationContainer').addClass('hidden'); // Hide the duration field
            }
        });

        // Monitor changes on the discount radio buttons
        $('input[name="discount"]').change(function () {
            if ($(this).val() === '1') {
                $('#discountContainer').removeClass('hidden'); // Show the discount field
            } else {
                $('#discountContainer').addClass('hidden'); // Hide the discount field
            }
        });

        // Show feature access when a category is selected
        $('#category').on('change', function () {
            const categoryId = $(this).val();
            console.log("Category changed, ID:", categoryId);
            if (categoryId) {
                fetchFeatureData(categoryId);
                $('#feature_access').removeClass('hidden'); // Show the feature access section
            } else {
                resetDropdowns(['practice_sets', 'quizzes_id', 'lessons_id', 'videos_id', 'exams_id']);
                $('#feature_access').addClass('hidden'); // Hide the feature access section
            }
        });

        function fetchFeatureData(categoryId) {
            $.ajax({
                url: "{{ route('get-feature-data') }}", // Backend route to handle feature data
                type: "POST",
                data: {
                    category_id: categoryId,
                    _token: "{{ csrf_token() }}" // CSRF token for Laravel
                },
                success: function (response) {
                    console.log("AJAX response:", response); // Log the response to the console
                    
                    // Populate each dropdown dynamically
                    if (response.practiceSets) {
                        populateDropdown('practice_sets', response.practiceSets, null, 'id', 'title');
                    }

                    if (response.quizzes) {
                        populateDropdown('quizzes_id', response.quizzes, null, 'id', 'title');
                    }

                    if (response.exams) {
                        populateDropdown('exams_id', response.exams, null, 'id', 'title');
                    }

                    if (response.lessons) {
                        populateDropdown('lessons_id', response.lessons, 'lesson', 'id', 'title');
                    }

                    if (response.videos) {
                        populateDropdown('videos_id', response.videos, 'video', 'id', 'title');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", error);
                }
            });
        }

        // Helper function to populate dropdowns
        function populateDropdown(dropdownId, data, subKey = null, valueKey = 'id', textKey = 'title') {
            const dropdown = $(`#${dropdownId}`);
            dropdown.empty(); // Clear existing options
            const uniqueItems = [];

            if (data && data.length > 0) {
                data.forEach(item => {
                    const entry = subKey ? item[subKey] : item; // Handle nested keys like `lesson`, `video`
                    if (entry && !uniqueItems.includes(entry[valueKey])) {
                        uniqueItems.push(entry[valueKey]);
                        dropdown.append(`<option value="${entry[valueKey]}">${entry[textKey]}</option>`);
                    }
                });
            }
        }



        // Helper to reset dropdowns
        function resetDropdowns(dropdownIds) {
            dropdownIds.forEach(dropdownId => {
                const dropdown = $(`#${dropdownId}`);
                dropdown.empty(); // Clear existing options
                console.log(`Reset dropdown: ${dropdownId}`);
            });
        }

        // Initialize form validation
        $("form").validate({
            rules: {
                category: { required: true },
                plan_name: { required: true, minlength: 2 },
                price_type: { required: true },
                duration: {
                    required: function () {
                        return $('#price_type_monthly').is(':checked'); // Require if monthly is checked
                    },
                    number: true,
                    min: 1
                },
                price: { required: true, number: true, min: 0 },
                discount_percentage: {
                    required: function () {
                        return $('input[name="discount"]:checked').val() === '1';
                    },
                    number: true,
                    min: 0
                },
                feature_access: { required: true }
            },
            messages: {
                category: "Please select a category.",
                plan_name: {
                    required: "Please enter a plan name.",
                    minlength: "Plan name must be at least 2 characters."
                },
                price_type: "Please select a price type.",
                duration: {
                    required: "Duration is required when price type is monthly.",
                    number: "Duration must be a number.",
                    min: "Duration must be at least 1 month."
                },
                price: {
                    required: "Please enter a price.",
                    number: "Price must be a number.",
                    min: "Price must be at least 0."
                },
                discount_percentage: "Discount must be a valid percentage.",
                feature_access: "Please select feature access."
            },
            errorElement: "div",
            errorClass: "text-red-500 text-sm mt-1",
            highlight: function (element) {
                $(element).addClass("border-red-500").removeClass("border-normal");
            },
            unhighlight: function (element) {
                $(element).removeClass("border-red-500").addClass("border-normal");
            }
        });
    });
</script>
<script>
    $('form').on('submit', function (e) {
        // Fields to sanitize
        const fields = ['exams', 'quizzes', 'practice_sets', 'lessons', 'videos'];

        fields.forEach(function (field) {
            const selectedValues = $(`#${field}_id`).val(); // Get selected IDs
            const uniqueValues = [...new Set(selectedValues)]; // Remove duplicates

            // Clear existing inputs for the field
            $(`input[name="${field}[]"]`).remove();

            // Append sanitized inputs with unique IDs only
            uniqueValues.forEach(function (value) {
                if (value) {
                    $(`<input type="hidden" name="${field}[]" value="${value}">`).appendTo('form');
                }
            });
        });
    });

</script>
@endpush

