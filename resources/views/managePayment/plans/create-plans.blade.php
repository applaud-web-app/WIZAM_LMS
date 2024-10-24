@extends('layouts.master')

@section('title', 'Create Plan')

@section('content')

    <section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">

                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Create Plan</h4>
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
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Create
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
                    <form action="{{route('save-plan')}}" method="POST" class="grid grid-cols-12 gap-[25px]">
                        @csrf

                        <!-- Category -->
                        <div class="col-span-6">
                            <label for="category"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Category<span
                                    class="text-red-500">*</span></label>
                            <select name="category" id="category"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                required>
                                <option disabled selected>Select a category</option>
                                @isset($subCategory)
                                    @foreach ($subCategory as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>


                        <!-- Plan Name -->
                        <div class="col-span-6">
                            <label for="plan_name"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Plan Name<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="plan_name" id="plan_name"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter plan name" required>
                        </div>

                        <!-- Price Type (Fixed/Monthly) -->
                        <div class="col-span-12">
                            <label class="block text-sm font-medium text-body dark:text-title-dark mb-2">Price Type<span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                <!-- First Radio (Fixed) -->
                                {{-- <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="price_type" id="price_type_fixed" value="fixed" required checked>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="price_type_fixed">Fixed</label>
                                </div> --}}

                                <!-- Second Radio (Monthly) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="price_type" id="price_type_monthly" value="monthly" checked required>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="price_type_monthly">Monthly</label>
                                </div>
                            </div>
                        </div>

                        <!-- Duration (hidden)-->
                        <div class="col-span-12 " id="durationContainer"> 
                            <label for="duration"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Duration (Months) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" step="1" name="duration" id="duration"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter Duration" value="1" min="1" required>
                        </div>

                        <!-- Price -->
                        <div class="col-span-12">
                            <label for="price"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Price<span
                                    class="text-red-500">*</span></label>
                            <input type="number" value="0" min="0" name="price" id="price"
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
                                        type="radio" name="discount" id="discount" value="1" required>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="discount">Yes</label>
                                </div>

                                <!-- Second Radio (Monthly) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="discount" id="discount" value="0" required checked>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="discount">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Discount -->
                        <div class="col-span-12 hidden" id="discountContainer">
                            <label for="discount"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Discount Percentage <span
                                    class="text-red-500">*</span></label>
                            <input type="number" value="0" min="0" name="discount_percentage" id="discount"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter Discount" required>
                        </div>

                        <!-- Feature Access - Unlimited Checkbox -->
                        <div class="col-span-12">
                            <label class="block text-sm font-medium text-body dark:text-title-dark mb-2">Feature Access <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                <!-- First Radio (Fixed) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="feature_access" id="feature_access" value="1" required checked>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="feature_access">Unlimited</label>
                                </div>

                                <!-- Second Radio (Monthly) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="feature_access" id="feature_access" value="0" required>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="feature_access">Restricted</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 sm:col-span-12 md:col-span-12 hidden" id="RestrictedFeatures">
                            <label for="features" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Features <span class="text-red-500">*</span></label>
                            <div class="flex flex-col flex-1">
                                <select id="features" name="features[]" 
                                    data-te-select-init 
                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto" 
                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" 
                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none" 
                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" 
                                    data-te-select-placeholder="Select Features" multiple>
                                    <option value="practice">Practice Sets</option>
                                    <option value="quizzes">Quizzes</option>
                                    <option value="lessons">Lessons</option>
                                    <option value="videos">Videos</option>
                                    <option value="exams">Exams</option>
                                </select>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div class="col-span-12">
                            <label for="description"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Short Description (Max
                                200 Characters)</label>
                            <textarea name="description" id="description" maxlength="200" rows="4"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter a short description"></textarea>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-span-12">
                            <label for="order"
                                class="block text-sm font-medium text-body dark:text-title-dark mb-2">Sort Order<span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="order" min="1"  id="order"
                                class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark"
                                placeholder="Enter sort order" required>
                        </div>

                        <!-- Popular - Yes Checkbox -->
                        <div class="col-span-12">
                            <label class="block text-sm font-medium text-body dark:text-title-dark mb-2">Popular</label>
                            <div class="flex flex-wrap items-center gap-[10px]">
                                <!-- First Radio (Enable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="popular" id="popular" value="1" checked
                                        required>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="popular">Enable</label>
                                </div>

                                <!-- Second Radio (Disable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="popular" id="popular" value="0" checked
                                        required>
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
                                        type="radio" name="status" id="status" value="1"
                                        required checked>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                        for="status">Enable</label>
                                </div>

                                <!-- Second Radio (Disable) -->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                        type="radio" name="status" id="status" value="0"
                                        required>
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
    $(document).ready(function() {
        // Monitor changes on the price type radio buttons
        $('input[name="price_type"]').change(function() {
            if ($(this).val() === 'monthly') {
                $('#durationContainer').removeClass('hidden'); // Show the duration field
            } else {
                $('#durationContainer').addClass('hidden'); // Hide the duration field
            }
        });
    
        // Monitor changes on the discount radio buttons
        $('input[name="discount"]').change(function() {
            if ($(this).val() === '1') {
                $('#discountContainer').removeClass('hidden'); // Show the discount field
            } else {
                $('#discountContainer').addClass('hidden'); // Hide the discount field
            }
        });
    
        // Monitor changes on the feature access radio buttons
        $('input[name="feature_access"]').change(function() {
            if ($(this).val() === '0') {
                $('#RestrictedFeatures').removeClass('hidden'); // Show the restricted features field
            } else {
                $('#RestrictedFeatures').addClass('hidden'); // Hide the restricted features field
            }
        });
    
        // Initialize form validation
        $("form").validate({
            rules: {
                category: {
                    required: true
                },
                plan_name: {
                    required: true,
                    minlength: 2
                },
                price_type: {
                    required: true
                },
                duration: {
                    required: function() {
                        return $('#price_type_monthly').is(':checked'); // Require if monthly is checked
                    },
                    number: true,
                    min: 1
                },
                price: {
                    required: true,
                    number: true,
                    min: 0
                },
                discount: {
                    required: function() {
                        return $('input[name="discount"]:checked').val() === '1'; // Require discount only if '1' is checked
                    },
                    number: true,
                    min: 0
                },
                feature_access: {
                    required: true
                },
                features: {
                    required: function() {
                        return $('input[name="feature_access"]:checked').val() === "0"; // Require features if '0' is checked
                    }
                }
            },
            messages: {
                category: {
                    required: "Please select a category."
                },
                plan_name: {
                    required: "Please enter a plan name.",
                    minlength: "Plan name must be at least 2 characters long."
                },
                price_type: {
                    required: "Please select a price type."
                },
                duration: {
                    required: "Please enter a duration.",
                    number: "Duration must be a number.",
                    min: "Duration must be at least 1 month."
                },
                price: {
                    required: "Please enter a price.",
                    number: "Price must be a number.",
                    min: "Price must be at least 0."
                },
                discount: {
                    required: "Please select a discount option.",
                    number: "Discount must be a number."
                },
                feature_access: {
                    required: "Please select feature access."
                },
                features: {
                    required: "Please select at least one feature."
                }
            },
            errorElement: "div",
            errorClass: "text-red-500 text-sm mt-1",
            highlight: function(element, errorClass) {
                $(element).addClass("border-red-500").removeClass("border-normal");
            },
            unhighlight: function(element, errorClass) {
                $(element).removeClass("border-red-500").addClass("border-normal");
            }
        });
    });
    </script>
@endpush