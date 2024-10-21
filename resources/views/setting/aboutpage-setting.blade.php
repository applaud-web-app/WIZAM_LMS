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
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Aboutpage Settings</h4>
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

                                <li
                                    class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 transition duration-300 capitalize">Setting</span>
                                </li>

                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Aboutpage Settings</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            {{-- Mission Section --}}
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-mission') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Mission</b></h1>
        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', isset($mission) ? $mission->title : '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="image" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Image <span class="text-red-500">*</span></label>
                                <input name="image" id="image" @isset($mission->image) {{$mission->image ? "" : "required"}} @endisset
                                    class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                                    type="file" id="image" />
                                 @isset($mission->image)
                                    <img src="{{$mission->image}}" height="100px" width="200px" alt="">
                                 @endisset
                            </div>
        
                            <div class="mb-[15px]">
                                <label for="vision" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <textarea id="description" name="description"
                                        class="summernote rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[100px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Description">{{ old('description', isset($mission) ? $mission->description : '') }}</textarea>
                                </div>
                            </div>
        
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            {{-- Vision Section --}}
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-vision') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="updateVision">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Vision</b></h1>

                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', isset($vision) ? $vision->title : '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="description" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <textarea id="description" name="description" class="summernote rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[100px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Description">{{ old('description', isset($vision) ? $vision->description : '') }}</textarea>
                                </div>
                            </div>
        
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            {{-- Values Section --}}
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-values') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="updateValues">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Values</b></h1>
        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', isset($values) ? $values->title : '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="image" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Image <span class="text-red-500">*</span></label>
                                <input name="image" id="image" @isset($values->image) {{$values->image ? "" : "required"}} @endisset
                                    class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                                    type="file" id="image" />
                                 @isset($values->image)
                                    <img src="{{$values->image}}" height="100px" width="200px" alt="">
                                 @endisset
                            </div>
        
                            <div class="mb-[15px]">
                                <label for="content" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <textarea id="description" name="description"
                                        class="summernote rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[100px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Description">{{ old('description', isset($values) ? $values->description : '') }}</textarea>
                                </div>
                            </div>
        
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Our Strategy --}}
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-strategy') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="updateOperate">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Strategy</b></h1>
        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', isset($strategy) ? $strategy->title : '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="description" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <textarea id="description" name="description" class="summernote rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[100px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Description">{{ old('description', isset($strategy) ? $strategy->description : '') }}</textarea>
                                </div>
                            </div>
        
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            {{-- How We Operate Section --}}
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-operate') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="updateOperate">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>How We Operate</b></h1>
        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', isset($operate) ? $operate->title : '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="description" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <textarea id="description" name="description" class="summernote rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[100px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Description">{{ old('description', isset($operate) ? $operate->description : '') }}</textarea>
                                </div>
                            </div>
        
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            {{-- Best Data Use Section --}}
            <div class="col-span-12 md:col-span-12">
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-best-data') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="updateBestData">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Best Data Use</b></h1>
        
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', isset($bestData) ? $bestData->title : '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="description" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Description <span class="text-danger">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <textarea id="description" name="description" class="summernote rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[100px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Description">{{ old('description', isset($bestData) ? $bestData->description : '') }}</textarea>
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
        

    </section>

@endsection
@push('scripts')
    
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
<script>
   $(document).ready(function() {
      $('.summernote').summernote({
         height: 300,
       
      });
   });
</script>
@endpush