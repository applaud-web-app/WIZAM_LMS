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
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Homepage Settings</h4>
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
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Homepage
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
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-banner') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Slider Section</b></h1>
                            <div id="bannerBox">
                                @foreach ($banners as $index => $banner)
                                    <div class="banner-slide mb-[15px] border-b-2 pb-3" data-index="{{ $index }}">
                                        <h2 class="slide-title text-lg mb-3"><b>Slide {{ $index + 1 }}</b></h2>
                                        <input type="hidden" name="banner_id[]" value="{{ $banner->id }}">

                                        <div class="mb-[15px]">
                                            <label for="title"
                                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Title
                                                <span class="text-danger">*</span></label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" name="title[]" id="title"
                                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                    value="{{ $banner->title }}" placeholder="Your Title" required>
                                            </div>
                                        </div>

                                        <div class="mb-[15px]">
                                            <label for="short_description"
                                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Short
                                                Description <span class="text-danger">*</span></label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" name="short_description[]" id="short_description"
                                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                    value="{{ $banner->description }}" placeholder="Your Short Description"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="mb-[15px]">
                                            <label for="button_text"
                                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Button
                                                Text <span class="text-danger">*</span></label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" name="button_text[]" id="button_text"
                                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                    value="{{ $banner->button_text }}" placeholder="Your Button Text"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="mb-[15px]">
                                            <label for="button_link"
                                                class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Button
                                                Link <span class="text-danger">*</span></label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" name="button_link[]" id="button_link"
                                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                    value="{{ $banner->button_link }}" placeholder="Your Button Link"
                                                    required>
                                            </div>
                                        </div>

                                        {{-- <div class="mb-[15px]">
                                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                                for="file_input">Banner Image <span class="text-danger">*</span></label>
                                            <input
                                                class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                                                name="banner_img[]" type="file">

                                            @if ($banner->image)
                                                <img src="{{ $banner->image }}" alt="Banner Image"
                                                    class="mt-2 w-[150px] h-[100px] object-cover">
                                            @endif
                                        </div> --}}

                                        <!-- Remove Button -->
                                        <button type="button"
                                            class="remove-banner mt-2 bg-danger text-white py-[5px] px-[10px] rounded-4 border-none cursor-pointer hover:bg-danger-dark focus:ring-danger focus:border-danger">Remove</button>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-[15px] flex justify-between">
                                <!-- Button to add more slides -->
                                <button type="button" id="addMoreBanner"
                                    class="mt-3 bg-secondary text-white py-[10px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-secondary-dark focus:ring-secondary focus:border-secondary btn-sm">Add
                                    More</button>

                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[10px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- YOTUBE VIDEO --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-youtube-video') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Youtube Video</b></h1>
                            <div class="mb-[15px]">
                                <label for="youtube_link"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Link <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="url" id="youtube_link" name="youtube_link" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter Youtube Link" value="{{ old('youtube', $youtube->description ?? '') }}" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- VERIFIED TEXT --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-verified-text') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Verified Text</b></h1>
                            <div class="mb-[15px]">
                                <label for="verified_image" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Image 
                                </label>
                                @isset($verified->image)
                                    <img src="{{$verified->image}}" height="100px" width="200px" alt="">
                                @endisset
                                <div class="flex flex-col flex-1">
                                    <input type="file" id="verified_image" name="verified_image" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter Image" value="{{ old('verified', $verified->title ?? '') }}" >
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="verified_text"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Text <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="verified_text" name="verified_text" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter Verified Text" value="{{ old('verified', $verified->title ?? '') }}" required>
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- EXAMS --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-exam') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Popular Exams Section</b></h1>

                            <div class="mb-[15px]">
                                <label for="title"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Site Name" value="{{ old('title', $exam->title ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_text"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Text <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_text" id="button_text"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_text', $exam->button_text ?? '') }}"
                                        placeholder="Your Button Text" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_link"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Link <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_link" id="button_link"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_link',$exam->button_link ?? '') }}"
                                        placeholder="Your Button Link" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- Help You --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-help') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Help Section</b></h1>
                        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Site Name" value="{{ old('title', $help->title ?? '') }}" required>
                                </div>
                            </div>
                        
                            @for ($i = 0; $i < 4; $i++)
                                <div class="border-b-2 mb-[15px]">
                                    <h2 class="slide-title text-lg mb-3"><b>Card {{ $i + 1 }}</b></h2>
                                    
                                    <div class="mb-[15px]">
                                        <label for="card_image[]" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                            Card Image <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex flex-col flex-1">
                                            @php
                                                $existingImage = optional(json_decode($help->extra ?? null))[$i]->image ?? null;
                                            @endphp
                                            <input type="file" name="card_image[]" class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white" {{ $existingImage ? '' : 'required' }} onchange="previewImage(event, {{ $i }})">
                                            
                                            <!-- Show existing image if available -->
                                            @if ($existingImage)
                                                <img src="{{$existingImage}}" alt="Card Image" id="imagePreview{{ $i }}" class="mt-2" width="200px">
                                            @else
                                                <img id="imagePreview{{ $i }}" class="mt-2 h-24 hidden">
                                            @endif
                                        </div>
                                    </div>
                        
                                    <div class="mb-[15px]">
                                        <label for="card_title[]" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                            Card Title <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex flex-col flex-1">
                                            <input type="text" name="card_title[]" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Card Title" value="{{ old('card_title.' . $i, optional(json_decode($help->extra ?? null))[$i]->title ?? '') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-[15px]">
                                        <label for="card_title[]" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                            Card Short Description <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex flex-col flex-1">
                                            <textarea name="card_short_description[]" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Card Short Description" required>{{ old('short_description.' . $i, optional(json_decode($help->extra ?? null))[$i]->short_description ?? '') }}</textarea>
                                        </div>
                                    </div>
                        
                                    <div class="mb-[15px]">
                                        <label for="card_description[]" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                            Card Description <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex flex-col flex-1">
                                            <textarea name="card_description[]" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Card Description" required>{{ old('card_description.' . $i, optional(json_decode($help->extra ?? null))[$i]->description ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mb-[15px]">
                                        <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex flex-wrap items-center gap-[10px]">
                                            <!--First radio-->
                                            <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                                <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" 
                                                type="radio" name="card_status[{{ $i }}]" value="1" {{ old('card_status.' . $i, optional(json_decode($help->extra ?? null))[$i]->status ?? '') == 1 ? 'checked' : '' }}>
                                                <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio1">Enable</label>
                                            </div>
                                            
                                            <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                                <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" 
                                                type="radio" name="card_status[{{ $i }}]" value="0" {{ old('card_status.' . $i, optional(json_decode($help->extra ?? null))[$i]->status ?? '') == 0 ? 'checked' : '' }}>
                                                <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio2">Disable</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endfor
                        
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Why Us --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-whyus') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Why Us Section</b></h1>
                        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                           class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                           placeholder="Your Site Name" value="{{ old('title', $whyus->title ?? '') }}" required>
                                </div>
                            </div>
                        
                            <div class="mb-[15px]">
                                <label for="points" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Points <span class="text-danger">*</span>
                                </label>
                        
                                @for ($i = 0; $i < 6; $i++)
                                    <div class="flex flex-col flex-1 md:flex-row mb-[10px]">
                                        <input type="text" name="points[]" 
                                               class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                               placeholder="Your Point {{ $i + 1 }}" 
                                               value="{{ old('points.' . $i, $whyus->extra ? json_decode($whyus->extra)[$i] : '') }}" required>
                                    </div>
                                @endfor
                            </div>

                            <div class="mb-[15px]">
                                <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-wrap items-center gap-[10px]">
                                    <!--First radio-->
                                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="1" autocompleted="" {{$whyus->status == 1 ? "checked" : ""}}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio1">Enable</label>
                                    </div>
                                    <!--Second radio-->
                                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                    <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="0" autocompleted="" {{$whyus->status == 0 ? "checked" : ""}}>
                                    <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio2">Disable</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


         {{-- FAQ --}}
         <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-faq') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>FAQ Section</b></h1>

                            <div class="mb-[15px]">
                                <label for="title"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Site Name" value="{{ old('title', $faq->title ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_text"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Text <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_text" id="button_text"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_text', $faq->button_text ?? '') }}"
                                        placeholder="Your Button Text" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_link"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Link <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_link" id="button_link"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_link',$faq->button_link ?? '') }}"
                                        placeholder="Your Button Link" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- Resource --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-resource') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Knowledge Hub Section</b></h1>
                            <div class="mb-[15px]">
                                <label for="title"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Site Name" value="{{ old('title', $resource->title ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="image"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Bg Image <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="file" id="image" name="image" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter Image" value="{{ old('image', $resource->image ?? '') }}" >
                                </div>
                                @isset($resource->image)
                                    <img src="{{$resource->image}}" width="100px" alt="">
                                @endisset
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_text"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Text <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_text" id="button_text"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_text', $resource->button_text ?? '') }}"
                                        placeholder="Your Button Text" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_link"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Link <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_link" id="button_link"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_link',$resource->button_link ?? '') }}"
                                        placeholder="Your Button Link" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- GET STARTED --}}
        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-get-started') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Get Started Section</b></h1>
                            <div class="mb-[15px]">
                                <label for="title"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Site Name" value="{{ old('title', $getStarted->title ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="description"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="description" name="description"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Site Name" value="{{ old('description', $getStarted->description ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_text"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Text <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_text" id="button_text"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_text', $getStarted->button_text ?? '') }}"
                                        placeholder="Your Button Text" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <label for="button_link"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Button Link <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text" name="button_link" id="button_link"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        value="{{ old('button_link',$getStarted->button_link ?? '') }}"
                                        placeholder="Your Button Link" required>
                                </div>
                            </div>

                            <div class="mb-[15px]">
                                <button type="submit"
                                    class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        function previewImage(event, index) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('imagePreview' + index);
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    {{-- <script>
        $(document).ready(function() {
            // Function to update slide numbers
            function updateSlideNumbers() {
                $('.banner-slide').each(function(index) {
                    $(this).find('.slide-title').text('Slide ' + (index + 1));
                });
            }

            // Click event for adding more banner slides
            $('#addMoreBanner').on('click', function() {
                // Check the number of existing slides
                if ($('.banner-slide').length >= 5) {
                    alert('You cannot add more than 5 slides.');
                    return; // Exit if the limit is reached
                }

                // Clone the first banner slide
                let newBanner = $('.banner-slide').first().clone();

                // Clear input values in the cloned slide
                newBanner.find('input[type="text"], input[type="file"]').val('');

                // Append the cloned slide to the bannerBox
                $('#bannerBox').append(newBanner);

                // Update slide numbers
                updateSlideNumbers();
            });

            // Click event for removing a banner slide
            $(document).on('click', '.remove-banner', function() {
                // Remove the selected banner slide
                if ($('.banner-slide').length > 1) {
                    $(this).closest('.banner-slide').remove();
                    // Update slide numbers
                    updateSlideNumbers();
                } else {
                    alert('You must have at least one slide.');
                }
            });

            // Initial call to update slide numbers
            updateSlideNumbers();
        });
    </script> --}}


    <script>
        $(document).ready(function() {
            let maxSlides = 5;
            let bannerIndex = $('.banner-slide').length; // Initialize with existing slides count

            // Click event to add more banner slides
            $('#addMoreBanner').click(function() {
                if (bannerIndex < maxSlides) {
                    bannerIndex++;
                    $('#bannerBox').append(`
                    <div class="banner-slide mb-[15px] border-b-2 pb-3" data-index="${bannerIndex}">
                        <h2 class="slide-title text-lg mb-3"><b>Slide ${bannerIndex}</b></h2>
                        <input type="hidden" name="banner_id[]">
                        
                        <div class="mb-[15px]">
                            <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Title <span class="text-danger">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" name="title[]" id="title${bannerIndex}" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Your Title" required>
                            </div>
                        </div>

                        <div class="mb-[15px]">
                            <label for="short_description" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Short Description <span class="text-danger">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" name="short_description[]" id="short_description${bannerIndex}" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Your Short Description" required>
                            </div>
                        </div>

                        <div class="mb-[15px]">
                            <label for="button_text" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Button Text <span class="text-danger">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" name="button_text[]" id="button_text${bannerIndex}" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Your Button Text" required>
                            </div>
                        </div>

                        <div class="mb-[15px]">
                            <label for="button_link" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Button Link <span class="text-danger">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" name="button_link[]" id="button_link${bannerIndex}" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Your Button Link" required>
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <button type="button" class="remove-banner mt-2 bg-danger text-white py-[5px] px-[10px] rounded-4 border-none cursor-pointer hover:bg-danger-dark focus:ring-danger focus:border-danger">
                            Remove
                        </button>
                    </div>
                `);
                }
            });

            // <div class="mb-[15px]">
            //                 <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input${bannerIndex}">
            //                     Banner Image <span class="text-danger">*</span>
            //                 </label>
            //                 <input class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
            //                     name="banner_img[]" type="file" id="file_input${bannerIndex}" required>
            //             </div>

            // Click event to remove a banner slide
            $(document).on('click', '.remove-banner', function() {
                $(this).closest('.banner-slide').remove();
                bannerIndex--;
            });
        });
    </script>
@endpush
