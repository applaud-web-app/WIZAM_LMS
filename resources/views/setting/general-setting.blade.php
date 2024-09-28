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
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">General Settings</h4>
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
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">General
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
                <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{route('update-settings')}}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addSetting">
                           @csrf
                            <div class="mb-[15px]">
                                <label for="siteName"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Site
                                    Name <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="siteName" name="site_name"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Site Name" value="@isset($generalSetting->site_name){{$generalSetting->site_name}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="tagLine"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Tag
                                    Line <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <input type="text" id="tagLine" name="tag_line"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Tag Line" value="@isset($generalSetting->tag_line){{$generalSetting->tag_line}}@endisset">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="seoDescription"
                                    class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">SEO
                                    Description <span class="text-red-500">*</span></label>
                                <div class="flex flex-col flex-1 ">
                                    <textarea id="seoDescription" rows="3" name="seo_description"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                        placeholder="Your SEO Description">@isset($generalSetting->description){{$generalSetting->description}}@endisset</textarea>
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitefavicon"
                                    class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Site Favicon <span class="text-red-500">*</span></label>
                                <input id="site_favicon" name="site_favicon" @isset($generalSetting->favicon) {{$generalSetting->favicon ? "" : "required"}} @endisset class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                                    type="file" id="sitefavicon" />
                                 @isset($generalSetting->favicon)
                                    <img src="{{asset('setting/'.$generalSetting->favicon)}}" height="100px" width="200px" alt="">
                                 @endisset
                            </div>
                            <div class="mb-[15px]">
                                <label for="sitelogo" class="mb-2 inline-block text-neutral-500 dark:text-neutral-400">Site
                                    Logo <span class="text-red-500">*</span></label>
                                <input name="site_logo" id="site_logo" @isset($generalSetting->site_logo) {{$generalSetting->site_logo ? "" : "required"}} @endisset
                                    class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                                    type="file" id="sitelogo" />
                                 @isset($generalSetting->site_logo)
                                    <img src="{{asset('setting/'.$generalSetting->site_logo)}}" height="100px" width="200px" alt="">
                                 @endisset
                            </div>
                            <!-- You can add a submit button if needed -->
                            <div class="mb-[15px]">
                                <button type="submit"
                                    class=" mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
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
      // jQuery Validation for the Add Section form
      $("#addSetting").validate({
         rules: {
            site_name: {
                  required: true
            },
            tag_line: {
                  required: true
            },
            seo_description: {
                  required: true,
                  maxlength: 1000
            },
         },
         messages: {
            site_name: {
                  required: "Please enter a site name"
            },
            tag_line: {
                  required: "Please enter a tag line"
            },
            seo_description: {
                  required: "Please provide an SEO description",
                  maxlength: "Description cannot exceed 1000 characters"
            }
         },
         submitHandler: function(form) {
            $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
            form.submit();
         }
      });

   </script>
@endpush