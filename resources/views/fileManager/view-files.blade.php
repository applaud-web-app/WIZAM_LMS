@extends('layouts.master')
@section('title', 'Permissions')
@section('content')
    @push('style')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    @endpush
    <section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">File Manager</h4>
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
                                <!-- Current Page -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">File
                                        Manager</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <!-- Responsive Toggler -->
        <div class="flex items-center justify-center lg:hidden ssm:mb-[30px] mb-[15px]">
            <button id="inbox-sidebar-selector" type="button"
                class=" text-danger text-sm font-semibold inline-flex justify-center items-center w-[40px] h-[40px] bg-white rounded-6 dark:bg-box-dark-up">
                <i class="uil uil-align-left text-[24px]"></i>
            </button>
        </div>
        <div class="lg:flex lg:gap-[30px]">
            <!-- Start sidebar -->
            <div class="2xl:flex-[0_0_19%] lg:2xl:flex-[0_0_24%] w-full">
                <div id="inbox-sidebar-target"
                    class="bg-white dark:bg-box-dark lg:rounded-[10px] max-lg:rounded-e-[10px] max-lg:w-[260px] max-lg:fixed max-lg:z-[11] max-lg:start-0 [&.nav-open]:translate-x-0 max-lg:top-[70px] max-lg:h-full ltr:max-lg:translate-x-[-260px] rtl:max-lg:translate-x-[260px] max-lg:shadow-lg duration-200 h-full">
                    <!-- Sidebar btn -->
                    <div class="px-[30px] pt-[30px]">
                        <button type="button"
                            class="w-full flex items-center justify-center dark:text-title-dark h-11 px-[20px] gap-1.5 text-sm  rounded-[20px] bg-primary text-white"
                            data-te-toggle="modal" data-te-target="#addFileModal" data-te-ripple-init
                            data-te-ripple-color="light">
                            <i class="uil uil-plus text-15"></i>
                            <span class="m-0">Add Directory</span>
                        </button>
                    </div>
                    <div class="p-[15px]">
                        <!-- Sidebar items -->
                        <ul class="listItemActive" role="tablist" data-te-nav-ref id="listItemActive">
                            <li class="mb-[15px] mt-[10px]">
                                <span
                                    class=" text-dark dark:text-title-dark text-[16px] font-medium leading-[20px] px-[15px] mb-[10px]">Directory</span>
                            </li>
                            @isset($directory)
                                @foreach ($directory as $item)
                                    <li class="mb-[10px]" role="presentation">
                                        @php
                                            $url = route('fetch-directory-data');
                                            $parms = 'id=' . $item->id;
                                            $encryptUrl = encrypturl($url, $parms);
                                        @endphp
                                        <button data-url="{{ $encryptUrl }}" data-name="{{$item->node_name}}"
                                            class="directory_files w-full flex items-center px-[15px] gap-[15px] rounded-md group text-body dark:text-subtitle-dark m-0 [&.active]:text-primary [&.active>span>i]:text-primary group">
                                            <span
                                                class=" text-[16px] text-light-extra dark:text-subtitle-dark group-hover:text-primary">
                                                <i class="uil uil-folder"></i>
                                            </span>
                                            <div
                                                class="flex items-center justify-between flex-auto m-0 text-[15px] font-normal group-hover:text-primary capitalize">
                                                <span>{{ $item->node_name }}</span>
                                            </div>
                                        </button>
                                    </li>
                                @endforeach
                            @endisset
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End sidebar -->
            <!-- Start Content -->
            <div class="2xl:flex-[0_0_79%] lg:flex-[0_0_74%] w-full">
                <div class="bg-white dark:bg-box-dark rounded-[10px] p-[30px]" id="fileSystem">
                    <div
                        class="flex items-center justify-center max-sm:flex-col sm:justify-between gap-x-[30px] gap-y-[15px]">
                        <div class="sm:w-[211px] relative w-full">
                            <span
                                class="start-5 absolute -translate-y-2/4 leading-[0] top-2/4 text-light dark:text-subtitle-dark text-[14px]">
                                <i class="uil uil-search"></i>
                            </span>
                            <input type="search"
                                class="ps-[50px] h-[40px] rounded-6 border border-normal dark:border-box-dark-up bg-white dark:bg-box-dark-up font-normal shadow-none px-[15px] py-[5px] text-[15px] text-dark dark:text-title-dark outline-none placeholder:text-gray dark:placeholder:text-subtitle-dark w-full search-close-icon:appearance-none search-close-icon:w-[20px] search-close-icon:h-[23px] search-close-icon:bg-[url({{ asset('assets/images/svg/x.svg') }})] search-close-icon:cursor-pointer"
                                placeholder="Search By name" autocomplete="off">
                        </div>
                        <div class="flex items-center gap-x-[15px] gap-y-[5px] bg-inherit">
                            <a href="file-manager.html"
                                class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow">
                                <i class="flex uil uil-upload"></i>
                            </a>
                            <a href="file-manager-list.html" title="folder"
                                class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow">
                                <i class="flex uil uil-folder"></i>
                            </a>
                            <button type="button" href="file-manager-list.html" title="delete"
                                class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow"
                                data-te-toggle="modal" data-te-target="#deletDirectory" data-te-ripple-init
                                data-te-ripple-color="light">
                                <i class="flex uil uil-trash"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Start Inbox body -->
                    <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-inbox" role="tabpanel" aria-labelledby="tabs-inbox-tab" data-te-tab-active>
                        <div
                            class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">
                            Folders</div>
                        <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]">
                            <!-- Grid Items -->
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                    <img class="mb-[18px] w-[50px] h-[50px]" src="{{ asset('assets/images/file/pdf.png') }}"
                                        alt="{title}}">
                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Product-guidelines.pdf</h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-5" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-5" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/psd.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        admin-wireframe.psd
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-6" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-6" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/zip.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Main-admin-design.zip
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-7" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-7" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/pdf.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Product-guidelines.pdf
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-8" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-8" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div
                            class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">
                            Files</div>
                        <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]">
                            <!-- Grid Items -->
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/zip.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Main-admin-design.zip
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-1" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-1" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/pdf.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Product-guidelines.pdf
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-2" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-2" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                    <img class="mb-[18px] w-[50px] h-[50px]" src="{{ asset('assets/images/file/psd.png') }}" alt="{title}}">
                                    <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">admin-wireframe.psd</h4>
                                    <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-3" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-3" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/zip.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Main-admin-design.zip
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-4" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-4" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/pdf.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Product-guidelines.pdf
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-5" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-5" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/psd.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        admin-wireframe.psd
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-6" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-6" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div
                                    class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/zip.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Main-admin-design.zip
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-7" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-7" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">

                                    <img class="mb-[18px] w-[50px] h-[50px]"
                                        src="{{ asset('assets/images/file/pdf.png') }}" alt="{title}}">

                                    <h4
                                        class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">
                                        Product-guidelines.pdf
                                    </h4>
                                    <div
                                        class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-8" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-8" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-prototypes" role="tabpanel" aria-labelledby="tabs-prototypes-tab">
                        <div class="max-h-[540px] relative xl:overflow-x-hidden overflow-x-auto overflow-y-auto scrollbar">
                            <div class="grid grid-cols-12 gap-[25px]">
                                <div class="col-span-12">
                                    <p
                                        class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                        No data avaiable <i class="uil uil-meh"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-images" role="tabpanel" aria-labelledby="tabs-images-tab">
                        <div class="max-h-[540px] relative xl:overflow-x-hidden overflow-x-auto overflow-y-auto scrollbar">
                            <div class="grid grid-cols-12 gap-[25px]">
                                <div class="col-span-12">
                                    <p
                                        class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                        No data avaiable <i class="uil uil-meh"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-illustrations" role="tabpanel" aria-labelledby="tabs-illustrations-tab">
                        <div class="max-h-[540px] relative xl:overflow-x-hidden overflow-x-auto overflow-y-auto scrollbar">
                            <div class="grid grid-cols-12 gap-[25px]">
                                <div class="col-span-12">
                                    <p
                                        class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                        No data avaiable <i class="uil uil-meh"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden opacity-0 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                        id="tabs-my" role="tabpanel" aria-labelledby="tabs-my-tab">
                        <div class="max-h-[540px] relative xl:overflow-x-hidden overflow-x-auto overflow-y-auto scrollbar">
                            <div class="grid grid-cols-12 gap-[25px]">
                                <div class="col-span-12">
                                    <p
                                        class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                        No data avaiable <i class="uil uil-meh"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Inbox body -->
                </div>
                <!-- Modal editor compose -->
            </div>
            <!-- End Content -->
        </div>
    </section>

    <!-- Add Directory -->
    <div data-te-modal-init
        class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="addFileModal" tabindex="-1" aria-labelledby="addFileModal" aria-hidden="true">
        <form method="POST" data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[768px]:mx-auto min-[768px]:mt-7 min-[768px]:max-w-[768px]"
            id="addDirectory">
            @csrf
            <div
                class="min-[768px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="addFileModal">
                        Add Directory
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <!-- Category Name -->
                    <div class="mb-[15px]">
                        <label for="directory_name"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Directory Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="directory_name" name="directory_name" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                placeholder="Directory Name">
                        </div>
                    </div>
                </div>
                <!--Modal footer-->
                <div
                    class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button type="submit"
                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-ripple-init data-te-ripple-color="light">
                        Submit
                    </button>
                    <button type="button"
                        class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>


    {{-- FOR UPLOAD FILE --}}
    <div data-te-modal-init
        class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="uploadFile" tabindex="-1" aria-labelledby="uploadFileLabel" aria-hidden="true">
        <div
            class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600 min-[768px]:mx-auto min-[768px]:mt-7 min-[768px]:max-w-[768px]">
            <div
                class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                <!--Modal title-->
                <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                    id="uploadFileLabel">
                    Upload Media
                </h5>
                <!--Close button-->
                <button type="button" id="closeUploadFileModal"
                    class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-modal-dismiss aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!--Modal body-->
            <div class="relative flex-auto p-4" data-te-modal-body-ref>
                <form action="{{ route('upload-media') }}" method="POST" enctype="multipart/form-data"
                    data-te-modal-dialog-ref class="dropzone" id="uploadForm">
                    @csrf
                    <input type="file" name="files" hidden>
                </form>
            </div>
            <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                <button type="button"
                    class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                    data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                    Cancel
                </button>
                <button type="button" disabled id="submitFiles"
                    class="hidden ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                    data-te-ripple-init data-te-ripple-color="light">
                    Submit
                </button>
            </div>
        </div>
    </div>


    {{-- DELETE Directory --}}
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="deletDirectory" tabindex="-1" aria-labelledby="deletDirectoryLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="deletDirectoryLabel">
                        Do you Want to delete these Directory?
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p class="mb-3 text-breadcrumbs dark:text-subtitle-dark"> This Action will delete complete directory
                        with all its resources? Please type "CONFIRM" to proceed this action.</p>
                    <div class="flex flex-col flex-1">
                        <input type="text" name="confirm" id="confirmation" required
                            class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                            placeholder="CONFIRM">
                    </div>
                    <span class="text-danger text-sm mt-2" id="deleteError"></span>
                </div>
                <!--Modal footer-->
                <div
                    class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button type="button"
                        class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                        Cancel
                    </button>
                    <button type="button"
                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-ripple-init data-te-ripple-color="light" data-url="" id="confirmDelete">
                        Commit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD FOLDER MODE --}}
    <div data-te-modal-init
        class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="addFolder" tabindex="-1" aria-labelledby="addFolderLabel" aria-hidden="true">
        <form method="POST" action="" data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
            id="addFolderForm">
            @csrf
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="addFolderLabel">
                        Add Folder Model
                    </h5>
                    <!--Close button-->
                    <button type="button"
                        class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                        data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!--Modal body-->
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <!-- Folder Name -->
                    <div class="mb-[15px]">
                        <label for="FolderName"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Folder Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="FolderName" name="folder_name" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                placeholder="Folder Name">
                        </div>
                    </div>
                </div>
                <!--Modal footer-->
                <div
                    class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button type="button"
                        class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                        Cancel
                    </button>
                    <button type="submit"
                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-ripple-init data-te-ripple-color="light">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script>
        // Array to hold the names of uploaded files
        let uploadedFileNames = [];
    
        Dropzone.options.uploadForm = {
            parallelUploads: 1,
            maxFilesize: 2048, // 2 GB
            chunking: true,
            forceChunking: true,
            parallelChunkUploads: true,
            chunkSize: 2000000, // 2 MB
            retryChunks: true,
            retryChunksLimit: 3,
            addRemoveLinks: true,
            timeout: 500000,
    
            success: function(file, response) {
                // Store the full path in the file object for later use
                file.fullPath = response.path + response.name;
                uploadedFileNames.push(response.name); // Store the file name in the array
                console.log("Uploaded file path:", file.fullPath);
                iziToast.success({
                    title: 'Success',
                    message: "File uploaded successfully! Please Submit to save it",
                    position: 'topRight',
                    timeout: 3000
                });
                $('#submitFiles').show().removeAttr('disabled');
            },
    
            removedfile: function(file) {
                if (!file.fullPath) return;

                // Remove file name from the array upon removal
                const fileName = file.fullPath.split('/').pop(); // Extract just the filename
                const index = uploadedFileNames.indexOf(fileName); // Match by filename
                if (index > -1) {
                    uploadedFileNames.splice(index, 1); // Remove the filename from the array
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('remove-media') }}",
                    data: {
                        filename: file.fullPath,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log("File has been successfully removed!!");
                        iziToast.success({
                            title: 'Success',
                            message: "File has been successfully removed!",
                            position: 'topRight',
                            timeout: 3000
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        let errorMessage = xhr.responseJSON?.message || "File could not be removed.";
                        iziToast.error({
                            title: 'Error',
                            message: errorMessage,
                            position: 'topRight',
                            timeout: 3000
                        });
                    }
                });

                // Remove the file preview from Dropzone
                var fileRef;
                return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
    
            error: function(file, response) {
                let message = typeof response === 'string' ? response : response.message || "File could not be uploaded.";
                iziToast.error({
                    title: 'Upload Error',
                    message: message,
                    position: 'topRight',
                    timeout: 3000
                });
                return false;
            }
        };
    
        // Event listener for the submit button
        document.getElementById('submitFiles').addEventListener('click', function() {
            // Check if there are uploaded files
            if (uploadedFileNames.length === 0) {
                iziToast.error({
                    title: 'Error',
                    message: "No files to submit!",
                    position: 'topRight',
                    timeout: 3000
                });
                return; // Exit the function if there are no files
            }

            const storeUrl = $(this).data('url');
            // Proceed to submit the file names to the server
            $.ajax({
                type: 'POST',
                url: storeUrl, // Update with your route
                data: {
                    fileNames: uploadedFileNames,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log("File names stored successfully:", response);
                    $('#closeUploadFileModal').trigger('click');
                    if (response.created_files) {
                        let content = ``;
                        created_files.forEach(element => {
                            content += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                    <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/file.png')}}" alt="${data[i].node_name}">
                                    <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2] text-center">${data[i].node_name}</h4>
                                    <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-${data[i].id}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-${data[i].id}" data-te-dropdown-menu-ref>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        download
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        copy
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                        class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                        <i
                                                            class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                        delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });
                    }
                    $('#MediaDataBox').append(content);
                    iziToast.success({
                        title: 'Success',
                        message: "Media Save Successfully",
                        position: 'topRight',
                        timeout: 3000
                    });
                },
                error: function(xhr) {
                    console.log(xhr);
                    
                    // Check for validation errors
                    if (xhr.status === 422) {
                        // Extract and display validation error messages
                        const errors = xhr.responseJSON.errors;
                        for (const key in errors) {
                            errors[key].forEach(errorMessage => {
                                iziToast.error({
                                    title: 'Validation Error',
                                    message: errorMessage,
                                    position: 'topRight',
                                    timeout: 3000
                                });
                            });
                        }
                    } else {
                        // Handle other errors
                        let errorMessage = xhr.responseJSON?.message || "Could not store file names.";
                        iziToast.error({
                            title: 'Error',
                            message: errorMessage,
                            position: 'topRight',
                            timeout: 3000
                        });
                    }
                }
            });
        });
    </script>


    {{-- Add Directory --}}
    <script>
        $(document).ready(function() {
            $('#addDirectory').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                $('#addDirectory').find('button[type="submit"]').html('Processing...').prop('disabled',
                    true);
                // Get the form data
                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route("add-directory") }}', // Update with your actual route
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success
                        console.log('Success:', response);

                        // Show success message using iziToast
                        iziToast.success({
                            title: 'Success',
                            message: response.message ||
                                'Directory added successfully!',
                            position: 'topRight',
                            timeout: 3000 // Optional: duration for the toast
                        });

                        // Create a new list item for the directory
                        var newDirectoryItem = `
                        <li class="mb-[10px]" role="presentation">
                              <a href="#tabs-prototypes" data-te-toggle="pill" data-te-target="#tabs-prototypes"
                                 role="tab" aria-controls="tabs-prototypes" aria-selected="false"
                                 class="w-full flex items-center px-[15px] gap-[15px] rounded-md group text-body dark:text-subtitle-dark m-0 [&.active]:text-primary [&.active>span>i]:text-primary group">
                                 <span class="text-[16px] text-light-extra dark:text-subtitle-dark group-hover:text-primary">
                                    <i class="uil uil-file"></i>
                                 </span>
                                 <div class="flex items-center justify-between flex-auto m-0 text-[15px] font-normal group-hover:text-primary capitalize">
                                    <span>${response.data.node_name}</span>
                                 </div>
                              </a>
                        </li>
                     `;

                        // Append the new directory item to the list
                        $('#listItemActive').append(newDirectoryItem);

                        // Close the modal or show a success message
                        $('#addFileModal button[data-te-modal-dismiss]')
                            .click(); // Or use a method to hide the modal if using a modal library
                        $('#addDirectory').find('button[type="submit"]').html('Submit')
                            .removeAttr('disabled');
                        // Optionally, reset the form
                        $('#addDirectory')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error('Error:', error);
                        // Show error message using iziToast
                        let errorMessage =
                            'There was an error submitting the form. Please try again.';

                        // If you have specific error messages from the response
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message; // Controller message
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ', '); // Validation errors
                        }

                        iziToast.error({
                            title: 'Error',
                            message: errorMessage,
                            position: 'topRight',
                            timeout: 5000 // Optional: duration for the toast
                        });

                        $('#addFileModal button[data-te-modal-dismiss]').click();
                        $('#addDirectory').find('button[type="submit"]').html('Submit')
                            .removeAttr('disabled');
                    }
                });
            });
        });
    </script>

    {{-- Delete Directory --}}
    <script>
        $(document).ready(function() {
            // Show modal
            // Handle confirm button click
            $('#confirmDelete').on('click', function() {
                var confirmationInput = $('#confirmation').val();

                // Validate confirmation input
                if (confirmationInput !== 'CONFIRM') {
                    $('#deleteError').text('Please type "CONFIRM" to proceed.');
                    return;
                }

                // Make AJAX request
                var deleteUrl = $('#remove_directory').data('url'); // Assuming you set the URL dynamically
                $.ajax({
                    type: 'POST',
                    url: deleteUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        deleteUrl: deleteUrl
                    },
                    success: function(response) {
                        // Handle success response
                        $('#deleteError').text('');

                        // Show success message using iziToast
                        iziToast.success({
                            title: 'Success',
                            message: 'Directory deleted successfully',
                            position: 'topRight',
                            timeout: 3000 // Optional: duration for the toast
                        });
                        location.reload(); // Refresh the page to see the updated list
                    },
                    error: function(xhr) {
                        // Handle error response
                        $('#deleteError').text('An error occurred: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>

    {{-- Fetch Directory Data --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.directory_files', function() {
                const url = $(this).data('url');
                const name = $(this).data('name');
                // Show loader
                $('#loader').show();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        eq: $(this).data('eq'), // Send the required data
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#loader').hide(); // Hide loader
                        let content = '';
                        if (response.success) {
                            const filter = `<h2><i class="uil uil-folder"></i> ${name}</h2><div class="mt-4 flex items-center justify-center max-sm:flex-col sm:justify-between gap-x-[30px] gap-y-[15px]">
                           <div class="sm:w-[211px] relative w-full">
                              <span
                                 class="start-5 absolute -translate-y-2/4 leading-[0] top-2/4 text-light dark:text-subtitle-dark text-[14px]">
                                 <i class="uil uil-search"></i>
                              </span>
                              <input type="search"
                                 class="ps-[50px] h-[40px] rounded-6 border border-normal dark:border-box-dark-up bg-white dark:bg-box-dark-up font-normal shadow-none px-[15px] py-[5px] text-[15px] text-dark dark:text-title-dark outline-none placeholder:text-gray dark:placeholder:text-subtitle-dark w-full search-close-icon:appearance-none search-close-icon:w-[20px] search-close-icon:h-[23px] search-close-icon:bg-[url({{ asset('assets/images/svg/x.svg') }})] search-close-icon:cursor-pointer"
                                 placeholder="Search By name" autocomplete="off">
                           </div>
                           <div class="flex items-center gap-x-[15px] gap-y-[5px] bg-inherit">
                              <button type="button" data-te-toggle="modal" data-url="${response.uploadUrl}" id="upload_directory_file" data-te-target="#uploadFile" data-te-ripple-init data-te-ripple-color="light" title="upload" data-url=""
                                 class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow">
                                 <i class="flex uil uil-upload"></i>
                              </button>
                              <button type="button" title="folder" data-url="${response.addFolderUrl}" id="addFolderUrl"
                                 class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow" data-te-toggle="modal" data-te-target="#addFolder" data-te-ripple-init data-te-ripple-color="light">
                                 <i class="flex uil uil-folder"></i>
                              </button>
                              <button type="button" title="delete" data-url="${response.deleteUrl}" id="remove_directory"
                                 class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow"
                                 data-te-toggle="modal" data-te-target="#deletDirectory" data-te-ripple-init
                                 data-te-ripple-color="light">
                                 <i class="flex uil uil-trash"></i>
                              </button>
                           </div>
                        </div>`;
                            if (response.data.length) {
                                const data = response.data;
                                let folder = '';
                                let file = '';
                                for (let i = 0; i < data.length; i++) {
                                    if (data[i].type === "folder") {
                                        folder += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                            <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                                <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/folder.png')}}" alt="${data[i].node_name}">
                                                <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">${data[i].node_name}</h4>
                                                <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                                    <div class="flex items-center" data-te-dropdown-ref>
                                                        <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                            id="fileManager-${data[i].id}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                            <i class="uil uil-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                            aria-labelledby="fileManager-${data[i].id}" data-te-dropdown-menu-ref>
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                                    <i
                                                                        class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                                    download
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                                    <i
                                                                        class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                                    copy
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                                    <i
                                                                        class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                                    delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                                    } else if (data[i].type === "media") {
                                        file += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                            <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                                <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/file.png')}}" alt="${data[i].node_name}">
                                                <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2] text-center">${data[i].node_name}</h4>
                                                <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                                    <div class="flex items-center" data-te-dropdown-ref>
                                                        <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                            id="fileManager-${data[i].id}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                            <i class="uil uil-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                            aria-labelledby="fileManager-${data[i].id}" data-te-dropdown-menu-ref>
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                                    <i
                                                                        class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                                    download
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                                    <i
                                                                        class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                                    copy
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#"
                                                                    class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
                                                                    <i
                                                                        class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
                                                                    delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                                    }
                                }
                                content =`${filter}<div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">Folders</div>
                                    <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="FolderDataBox">${folder}</div><div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">Files</div><div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]"  id="MediaDataBox">${file}</div>`;
                            } else {
                                content = `${filter}<div class="opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block"
                                 id="tabs-prototypes" role="tabpanel" aria-labelledby="tabs-prototypes-tab">
                                 <div class="max-h-[540px] relative xl:overflow-x-hidden overflow-x-auto overflow-y-auto scrollbar">
                                    <div class="grid grid-cols-12 gap-[25px]">
                                       <div class="col-span-12">
                                             <p
                                                class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                                No data avaiable <i class="uil uil-meh"></i></p>
                                       </div>
                                    </div>
                                 </div>
                              </div>`;
                            }
                            $('#fileSystem').html(content);
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: 'Something Went Wrong',
                                position: 'topRight',
                                timeout: 5000 // Optional: duration for the toast
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#loader').hide(); // Hide loader on error
                        console.log('An error occurred: ' + xhr.responseText);
                        alert('An error occurred while fetching data. Please try again.');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Trigger modal and set action URL
            $(document).on('click', '#upload_directory_file', function() {
                const url = $(this).data('url');
                console.log(url);
                $('#submitFiles').attr('data-url', url);
            });
        });

        // Trigger modal and set action URL
        $(document).on('click', '#addFolderUrl', function() {
            const url = $(this).data('url');
            $('#addFolderForm').attr('action', url);
        });
    </script>
@endpush
