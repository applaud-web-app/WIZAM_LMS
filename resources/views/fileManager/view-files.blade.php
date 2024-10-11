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
                            data-te-toggle="modal" data-te-target="#addFolder" data-te-ripple-init
                            data-te-ripple-color="light">
                            <i class="uil uil-plus text-15"></i>
                            <span class="m-0">Add Folder</span>
                        </button>
                    </div>
                    <div class="p-[15px]">
                        <!-- Sidebar items -->
                        <ul class="listItemActive" role="tablist" data-te-nav-ref id="listItemActive">
                            <li class="mb-[15px] mt-[10px] directory_files cursor-pointer" data-name="My File" data-id="0" type="button">
                                <span class="text-dark dark:text-title-dark text-[16px] font-medium leading-[20px] px-[15px] mb-[10px]">My Files</span>
                            </li>
                            @isset($parentDirectory)
                                @foreach ($parentDirectory as $item)
                                    <li class="mb-[10px]" role="presentation">
                                        @php
                                            $url = route('fetch-directory-data');
                                            $parms = 'id=' . $item->id;
                                            $encryptUrl = encrypturl($url, $parms);
                                        @endphp
                                        <button data-url="{{ $encryptUrl }}" data-name="{{$item->node_name}}" data-id="{{$item->id}}"
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
                    <h2><i class="uil uil-folder"></i> My Files</h2>
                    <div class="mt-4 flex items-center justify-center max-sm:flex-col sm:justify-between gap-x-[30px] gap-y-[15px]">
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
                            @php
                                $parms = 'id=' . 0;
                                $add_folder = route('add-folder');
                                $add_file = route('save-directory-media');
                                $folderUrl = encrypturl($add_folder,$parms);
                                $fileUrl = encrypturl($add_file,$parms);
                            @endphp
                            <button class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow" title="file" data-te-toggle="modal" data-te-target="#uploadFile" data-te-ripple-init="" data-te-ripple-color="light" id="upload_directory_file" data-url="{{$fileUrl}}">
                                <i class="flex uil uil-upload"></i>
                            </button>
                            <button  title="folder" class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow" data-te-toggle="modal" data-te-target="#addFolder" data-te-ripple-init="" data-te-ripple-color="light" id="addFolderUrl" data-url="{{$folderUrl}}">
                                <i class="flex uil uil-folder"></i>
                            </button>
                        </div>
                    </div>

                    {{-- MY FINAL CODE --}}
                    @isset($parentData)
                        <div class="opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="parentDoc">
                            @php
                                // Initialize arrays for folders and files
                                $folders = [];
                                $files = [];

                                // Loop through $parentData once to separate folders and files
                                foreach ($parentData as $item) {
                                    if ($item->type === 'folder') {
                                        $folders[] = $item;
                                    } else {
                                        $files[] = $item;
                                    }
                                }
                            @endphp
                            {{-- Display Folders Section --}}
                            <div id="foldersSection">
                                {{-- Heading for Folders --}}
                                <div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">
                                    Folders
                                </div>
                                @if (count($folders) > 0)
                                    <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="foldersContainer">
                                        @foreach ($folders as $item)
                                            @php
                                                $parms = 'id=' . $item->id;
                                                $encryptUrl = encrypturl(route('fetch-directory-data'), $parms);
                                                $deleteUrl = encrypturl(route('delete-directory'), $parms);
                                            @endphp
                                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                                    <img class="directory_files cursor-pointer mb-[18px] w-[50px] h-[50px]" src="{{ asset('assets/images/file/folder.png') }}" data-id="{{$item->id}}" data-url="{{ $encryptUrl }}" data-name="{{ $item->node_name }}" alt="{{ $item->node_name }}">
                                                    <h4 class="cursor-pointer directory_files text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]" data-id="{{$item->id}}" data-url="{{ $encryptUrl }}" data-name="{{ $item->node_name }}">{{ $item->node_name }}</h4>
                                                    {{-- Dropdown menu actions --}}
                                                    <div class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                                        <div class="flex items-center" data-te-dropdown-ref>
                                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button" id="fileManager-{{ $item->id }}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                                <i class="uil uil-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]" aria-labelledby="fileManager-{{ $item->id }}" data-te-dropdown-menu-ref>
                                                                <li><button class="directory_files flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]" data-id="{{$item->id}}" data-url="{{$encryptUrl}}" data-name="{{ $item->node_name }}"><i class="uil uil-eye text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>View</button></li>
                                                                <li><button class="deleteMedia flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]" data-url="{{$deleteUrl}}" data-te-toggle="modal" data-te-target="#deleteModal"  data-type="{{ $item->type}}"  data-te-ripple-init data-te-ripple-color="light"><i class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>Delete</button></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Display "No data available" if no folders exist --}}
                                    <div id="noFoldersMessage">
                                        <p class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                            No folders available <i class="uil uil-meh"></i>
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Display Files Section --}}
                            <div id="filesSection">
                                {{-- Heading for Files --}}
                                <div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">
                                    Files
                                </div>
                                @if (count($files) > 0)
                                    <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="filesContainer">
                                        @foreach ($files as $item)
                                            @php
                                                $url = route('fetch-directory-data');
                                                $parms = 'id=' . $item->id;
                                                $encryptUrl = encrypturl($url, $parms);
                                                $deleteUrl = encrypturl(route('delete-directory'),$parms);
                                            @endphp
                                            <div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                                    <img class="mb-[18px] w-[50px] h-[50px]" src="{{ asset('assets/images/file/file.png') }}" alt="{{ $item->node_name }}">
                                                    <h4 class="cursor-pointer text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]" >{{ $item->node_name }}</h4>
                                                    {{-- Dropdown menu actions --}}
                                                    <div class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                                        <div class="flex items-center" data-te-dropdown-ref>
                                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button" id="fileManager-{{ $item->id }}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                                <i class="uil uil-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]" aria-labelledby="fileManager-{{ $item->id }}" data-te-dropdown-menu-ref>
                                                                <li><a href="{{ asset('storage/' . $item->source) }}"  class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]" download ><i class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>Download</a></li>
                                                                <li><button class="previewOption flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]" data-file="{{ asset('storage/' . $item->source) }}"  data-te-toggle="modal" data-te-target="#filePreview" data-te-ripple-init data-te-ripple-color="light"><i class="uil uil-eye text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>View</button></li>
                                                                <li><button data-file="{{ asset('storage/' . $item->source) }}" class="copyUrl flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]"><i class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>Copy Url</button></li>
                                                                <li><button class="deleteMedia flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]" data-url="{{$deleteUrl}}" data-te-toggle="modal" data-te-target="#deleteModal"  data-type="{{ $item->type }}"  data-te-ripple-init data-te-ripple-color="light"><i class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>Delete</button></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Display "No data available" if no files exist --}}
                                    <div id="noFilesMessage">
                                        <p class="capitalize text-[16px] text-warning font-medium flex items-center gap-[10px] my-[30px] justify-center">
                                            No files available <i class="uil uil-meh"></i>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </section>

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
                        class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"  id="folderCloseBtn"
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

    {{-- DELETE MODAL --}}
    <div data-te-modal-init
     class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
     id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="deleteModalLabel">
                        Do you want to delete this <span id="itemType"></span>?
                    </h5>
                    <button type="button"
                            class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" id="closeDeleteModal"
                            data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <p class="mb-3 text-breadcrumbs dark:text-subtitle-dark">
                        This action will delete the complete <span id="itemTypeConfirmation"></span> with all its resources. Please type "CONFIRM" to proceed with this action.
                    </p>
                    <div class="flex flex-col flex-1">
                        <input type="text" name="confirm" id="confirmation" required
                            class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                            placeholder="CONFIRM">
                    </div>
                    <span class="text-danger text-sm mt-2" id="deleteError"></span>
                </div>
                <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button type="button"
                            class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            data-te-modal-dismiss>
                        Cancel
                    </button>
                    <button type="button"
                            class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            id="confirmDelete">
                        Commit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- FILE PREVIEW --}}
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="filePreview" tabindex="-1" aria-labelledby="filePreviewLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="filePreviewLabel">File Preview</h5>
                    <button type="button" class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <div id="filePreviewContent"></div>
                    <span class="text-danger text-sm mt-2" id="previewError" style="display: none;"></span>
                </div>
                {{-- <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <a id="downloadButton" href="#" class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white" style="display: none;">Download</a>
                </div> --}}
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    {{-- UPLOAD FOLDER --}}
    <script>
        $(document).ready(function () {
            $('#addFolderForm').on('submit', function (e) {
                e.preventDefault(); // Prevent the default form submission
                
                // Collect form data
                var formData = $(this).serialize();
                const url = $('#addFolderUrl').data('url');
                // Send AJAX request
                $.ajax({
                    url: url, // Replace with your form's action URL
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        console.log(response);
                        // Handle the success response
                        if (response.success) {
                            iziToast.success({
                                title: 'Success',
                                message: response.message, // Use the success message from the response
                                position: 'topRight',
                                timeout: 3000
                            });
                            $('#addFolderForm')[0].reset(); // Reset the form fields
                            $('#folderCloseBtn').trigger('click');
                        } else {
                            iziToast.error({
                                title: 'Error',
                                message: response.message, // Use the error message from the response
                                position: 'topRight',
                                timeout: 3000
                            });
                        }
                    },
                    error: function (xhr) {
                        // Handle error response
                        let errorMessage = xhr.responseJSON?.message || 'An unexpected error occurred.';
                        iziToast.error({
                            title: 'Error',
                            message: errorMessage, // Display the error message
                            position: 'topRight',
                            timeout: 3000
                        });
                    }
                });
            });
        });
    </script>

    {{-- UPLOAD FILES AND DELETE FILE --}}
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
                file.fullPath = response.path + response.new_name; // Adjusted to use new_name
                // Construct the formatted response
                let formattedResponse = {
                    image: response.path + response.new_name, // Construct the URL
                    original: response.original_name // Original file name
                };
                // Push the formatted response into the uploadedFileNames array
                uploadedFileNames.push(formattedResponse);
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
                // Check if the file has a fullPath
                if (!file.fullPath) return;

                // Extract the file name from the full path
                const fileName = file.fullPath.split('/').pop(); // Extract just the filename

                // Remove file name from the uploadedFileNames array upon removal
                const index = uploadedFileNames.findIndex(item => item.image.endsWith(fileName)); // Match by filename
                if (index > -1) {
                    uploadedFileNames.splice(index, 1); // Remove the filename from the array
                    console.log(`Removed ${fileName} from uploadedFileNames array.`);
                }

                // AJAX request to remove the file from the server
                $.ajax({
                    type: 'POST',
                    url: "{{ route('remove-media') }}",
                    data: {
                        filename: file.fullPath,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        console.log("File has been successfully removed from the server!!");
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

        // UPDATE DATA URL FOR FILE UPLAODS
        $(document).ready(function() {
            // Trigger modal and set action URL
            $(document).on('click', '#upload_directory_file', function() {
                const url = $(this).data('url');
                console.log(url);
                $('#submitFiles').attr('data-url', url);
            });
        });


        function resetDropzoneUI() {
            var dropzoneInstance = Dropzone.forElement("#uploadForm");
            
            // Manually remove each file preview from the Dropzone UI
            dropzoneInstance.files.forEach(function(file) {
                // Remove the preview element
                if (file.previewElement) {
                    file.previewElement.parentNode.removeChild(file.previewElement);
                }
            });

            // Clear the Dropzone file array
            dropzoneInstance.files = []; // Clear the internal file array

            // Reinitialize Dropzone to reset messages
            dropzoneInstance.emit("reset"); // Emit reset event to show the original message
        }

        // SAVE THE FILE DATA TO SERVER
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
                    // Call the reset function
                    resetDropzoneUI();
                    uploadedFileNames = []; // Clear the file array
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

    {{-- Preview Modal --}}
    <script>
       $(document).ready(function() {
            $('.previewOption').on('click', function() {
                const fileUrl = $(this).data('file'); // Get the URL from data-file attribute
                const fileName = fileUrl.split('/').pop();
                const fileExtension = fileName.split('.').pop().toLowerCase();
                
                // Clear previous content
                $('#filePreviewContent').empty();
                $('#previewError').hide();
                $('#downloadButton').hide().attr('href', fileUrl);
            
                let content = '';
            
                // Determine file type and create appropriate HTML
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    // For images
                    content = `<img src="${fileUrl}" alt="${fileName}" style="width: 100%; max-height: 400px; object-fit: contain;">`;
                } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                    // For videos
                    content = `<video controls style="width: 100%; max-height: 400px;">
                                <source src="${fileUrl}" type="video/${fileExtension}">
                                Your browser does not support the video tag.
                            </video>`;
                } else if (fileExtension === 'pdf') {
                    // For PDF
                    content = `<iframe src="${fileUrl}" style="width: 100%; height: 400px;" frameborder="0"></iframe>`;
                } else if (['ppt', 'pptx'].includes(fileExtension)) {
                    // For PowerPoint presentations (can link to viewer)
                    content = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fileUrl)}" style="width: 100%; height: 400px;" frameborder="0"></iframe>`;
                } else {
                    // Unsupported file type
                    $('#previewError').text('This file format preview is not available.').show();
                    $('#downloadButton').show();
                    return;
                }
            
                // Append content to modal
                $('#filePreviewContent').append(content);
                $('#downloadButton').show(); // Show the download button
            });
        });

        $(document).ready(function() {
            // Attach click event to the button with class 'copyUrl'
            $('.copyUrl').on('click', function() {
                const fileUrl = $(this).data('file'); // Get the URL from the data attribute
                
                // Copy to clipboard
                navigator.clipboard.writeText(fileUrl).then(() => {
                    // Show success message using iziToast
                    iziToast.success({
                        title: 'Copied!',
                        message: 'The URL has been copied to your clipboard.',
                        position: 'topRight',
                        timeout: 3000 // Optional timeout
                    });
                }).catch(err => {
                    console.error('Error copying to clipboard: ', err);
                });
            });
        });

    </script>

    {{-- Delete Directory --}}
    <script>
        $(document).ready(function() {
            // Function to capitalize the first letter
            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }

            // Show modal and set confirmation message
            $('.deleteMedia').on('click', function() {
                var itemType = $(this).data('type');
                var deleteUrl = $(this).data('url');
                
                $('#confirmDelete').data('url', deleteUrl); // Store the URL in the confirm button
                $('#itemType').text(capitalizeFirstLetter(itemType));
                $('#itemTypeConfirmation').text(capitalizeFirstLetter(itemType));

                // Clear any previous error messages and input values
                $('#confirmation').val('');
                $('#deleteError').text('');
            });

            // Handle confirm button click
            $('#confirmDelete').on('click', function() {
                var confirmationInput = $('#confirmation').val().trim();
                var deleteUrl = $(this).data('url'); // Get delete URL from the clicked button

                // Validate confirmation input
                if (confirmationInput !== 'CONFIRM') {
                    $('#deleteError').text('Please type "CONFIRM" to proceed.');
                    return;
                }

                // Check if deleteUrl is valid
                if (!deleteUrl) {
                    $('#deleteError').text('Invalid delete URL.');
                    return;
                }

                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: deleteUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        deleteUrl: deleteUrl
                    },
                    success: function(response) {
                        // Clear error message
                        $('#deleteError').text('');
                        
                        // Show success message using iziToast
                        iziToast.success({
                            title: 'Success',
                            message: 'Permanently Deleted Successfully',
                            position: 'topRight',
                            timeout: 3000 // Optional: duration for the toast
                        });
                        $('#closeDeleteModal').trigger('click');
                    },
                    error: function(xhr) {
                        // Display a more user-friendly error message
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'An error occurred. Please try again.';
                        
                        $('#deleteError').text(errorMessage);
                    }
                });
            });
        });

    </script>

    {{-- Fetch Directory Data --}}
    <script>
        // $(document).ready(function() {
        //     $(document).on('click', '.directory_files', function() {
        //         const url = $(this).data('url');
        //         const name = $(this).data('name');
        //         // Show loader
        //         $('#loader').show();
    
        //         $.ajax({
        //             type: 'POST',
        //             url: url,
        //             data: {
        //                 eq: $(this).data('eq'), // Send the required data
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function(response) {
        //                 console.log(response);
        //                 $('#loader').hide(); // Hide loader
        //                 let content = '';
        //                 if (response.success) {
        //                     const filter = `<h2><i class="uil uil-folder"></i> My Files <i class="uil uil-arrow"></i> ${name}</h2><div class="mt-4 flex items-center justify-center max-sm:flex-col sm:justify-between gap-x-[30px] gap-y-[15px]">
        //                        <div class="sm:w-[211px] relative w-full">
        //                           <span
        //                              class="start-5 absolute -translate-y-2/4 leading-[0] top-2/4 text-light dark:text-subtitle-dark text-[14px]">
        //                              <i class="uil uil-search"></i>
        //                           </span>
        //                           <input type="search"
        //                              class="ps-[50px] h-[40px] rounded-6 border border-normal dark:border-box-dark-up bg-white dark:bg-box-dark-up font-normal shadow-none px-[15px] py-[5px] text-[15px] text-dark dark:text-title-dark outline-none placeholder:text-gray dark:placeholder:text-subtitle-dark w-full search-close-icon:appearance-none search-close-icon:w-[20px] search-close-icon:h-[23px] search-close-icon:bg-[url({{ asset('assets/images/svg/x.svg') }})] search-close-icon:cursor-pointer"
        //                              placeholder="Search By name" autocomplete="off">
        //                        </div>
        //                        <div class="flex items-center gap-x-[15px] gap-y-[5px] bg-inherit">
        //                           <button type="button" data-te-toggle="modal" data-url="${response.uploadUrl}" id="upload_directory_file" data-te-target="#uploadFile" data-te-ripple-init data-te-ripple-color="light" title="upload" data-url=""
        //                              class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow">
        //                              <i class="flex uil uil-upload"></i>
        //                           </button>
        //                           <button type="button" title="folder" data-url="${response.addFolderUrl}" id="addFolderUrl"
        //                              class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow" data-te-toggle="modal" data-te-target="#addFolder" data-te-ripple-init data-te-ripple-color="light">
        //                              <i class="flex uil uil-folder"></i>
        //                           </button>
        //                           <button type="button" title="delete" data-url="${response.deleteUrl}" id="remove_directory"
        //                              class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow"
        //                              data-te-toggle="modal" data-te-target="#deletDirectory" data-te-ripple-init
        //                              data-te-ripple-color="light">
        //                              <i class="flex uil uil-trash"></i>
        //                           </button>
        //                        </div>
        //                     </div>`;
        //                     let folder = '';
        //                     let file = '';
    
        //                     // Loop through folders
        //                     response.folders.forEach(folderData => {
        //                         folder += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
        //                             <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
        //                                 <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/folder.png')}}" alt="${folderData.node_name}">
        //                                 <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">${folderData.node_name}</h4>
        //                                 <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
        //                                     <div class="flex items-center" data-te-dropdown-ref>
        //                                         <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
        //                                             id="fileManager-${folderData.id}" data-te-dropdown-toggle-ref aria-expanded="false">
        //                                             <i class="uil uil-ellipsis-v"></i>
        //                                         </button>
        //                                         <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
        //                                             aria-labelledby="fileManager-${folderData.id}" data-te-dropdown-menu-ref>
        //                                             <li>
        //                                                 <a href="#"
        //                                                     class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
        //                                                     <i
        //                                                         class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
        //                                                     download
        //                                                 </a>
        //                                             </li>
        //                                             <li>
        //                                                 <a href="#"
        //                                                     class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
        //                                                     <i
        //                                                         class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
        //                                                     copy
        //                                                 </a>
        //                                             </li>
        //                                             <li>
        //                                                 <a href="#"
        //                                                     class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
        //                                                     <i
        //                                                         class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
        //                                                     delete
        //                                                 </a>
        //                                             </li>
        //                                         </ul>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div>`;
        //                     });
    
        //                     // Loop through files
        //                     response.files.forEach(fileData => {
        //                         file += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
        //                             <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
        //                                 <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/file.png')}}" alt="${fileData.node_name}">
        //                                 <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">${fileData.node_name}</h4>
        //                                 <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
        //                                     <div class="flex items-center" data-te-dropdown-ref>
        //                                         <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
        //                                             id="fileManager-${fileData.id}" data-te-dropdown-toggle-ref aria-expanded="false">
        //                                             <i class="uil uil-ellipsis-v"></i>
        //                                         </button>
        //                                         <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
        //                                             aria-labelledby="fileManager-${fileData.id}" data-te-dropdown-menu-ref>
        //                                             <li>
        //                                                 <a href="#"
        //                                                     class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
        //                                                     <i
        //                                                         class="uil uil-download-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
        //                                                     download
        //                                                 </a>
        //                                             </li>
        //                                             <li>
        //                                                 <a href="#"
        //                                                     class="flex items-center gap-[10px] mb-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
        //                                                     <i
        //                                                         class="uil uil-copy text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
        //                                                     copy
        //                                                 </a>
        //                                             </li>
        //                                             <li>
        //                                                 <a href="#"
        //                                                     class="flex items-center gap-[10px] capitalize text-light dark:text-subtitle-dark group hover:text-primary text-[14px]">
        //                                                     <i
        //                                                         class="uil uil-trash-alt text-body dark:text-subtitle-dark group-hover:text-current text-[15px]"></i>
        //                                                     delete
        //                                                 </a>
        //                                             </li>
        //                                         </ul>
        //                                     </div>
        //                                 </div>
        //                             </div>
        //                         </div>`;
        //                     });
    
        //                     content = `${filter}
        //                         <div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">Folders</div>
        //                         <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="FolderDataBox">${folder}</div>
        //                         <div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">Files</div>
        //                         <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="MediaDataBox">${file}</div>`;
        //                     $('#fileSystem').html(content);
        //                 } else {
        //                     iziToast.error({
        //                         title: 'Error',
        //                         message: 'Something Went Wrong',
        //                         position: 'topRight',
        //                         timeout: 5000
        //                     });
        //                 }
        //             },
        //             error: function(xhr) {
        //                 $('#loader').hide();
        //                 console.log(xhr.responseText); // Debugging info in console
        //                 iziToast.error({
        //                     title: 'Error',
        //                     message: 'Something Went Wrong',
        //                     position: 'topRight',
        //                     timeout: 5000
        //                 });
        //             }
        //         });
        //     });
        // });

        $(document).ready(function() {
            $(document).on('click', '.directory_files', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                console.log(id+'--'+name);
                fetchDirectoryContents(id,name);
            });
        });
    </script>

    <script>
        function fetchDirectoryContents(parentId, name = '') {
            const url = "{{ route('fetch-directory-data') }}";
            $('#loader').show();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    parent_id: parentId,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    console.log(response);
                    $('#loader').hide(); // Hide loader
                    let content = '';
                    if (response.success) {
                        let deleteBtn = ``;
                        if(parentId != 0){
                            deleteBtn=`<button type="button" title="delete" data-url="${response.deleteUrl}" id="remove_directory"
                                class="min-w-[40px] h-[40px] inline-flex items-center justify-center text-light dark:text-subtitle-dark text-[19px] rounded-full hover:bg-primary/10 hover:text-primary [&.active]:bg-primary/10 [&.active]:text-primary shadow"
                                data-te-toggle="modal" data-te-target="#deletDirectory" data-te-ripple-init
                                data-te-ripple-color="light">
                                <i class="flex uil uil-trash"></i>
                            </button>`;
                        }

                        const filter = `<h2><i class="uil uil-folder"></i> My Files <i class="uil uil-arrow"></i> ${name}</h2><div class="mt-4 flex items-center justify-center max-sm:flex-col sm:justify-between gap-x-[30px] gap-y-[15px]">
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
                                ${deleteBtn}
                            </div>
                        </div>`;

                        let folder = '';
                        let file = '';

                        // Loop through folders
                        response.folders.forEach(folderData => {
                            folder += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                    <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/folder.png')}}" alt="${folderData.node_name}">
                                    <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">${folderData.node_name}</h4>
                                    <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-${folderData.id}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-${folderData.id}" data-te-dropdown-menu-ref>
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

                        // Loop through files
                        response.files.forEach(fileData => {
                            file += `<div class="col-span-12 2xl:col-span-3 sm:col-span-6">
                                <div class="pt-[40px] pb-[45px] px-[30px] rounded-10 bg-normalBG dark:bg-box-dark-up relative flex flex-col items-center justify-center">
                                    <img class="mb-[18px] w-[50px] h-[50px]" src="{{asset('assets/images/file/file.png')}}" alt="${fileData.node_name}">
                                    <h4 class="text-[14px] text-dark dark:text-title-dark inline-block font-medium leading-[1.2]">${fileData.node_name}</h4>
                                    <div  class="flex items-center gap-y-[10px] gap-x-[10px] justify-between absolute top-3 end-3 z-10">
                                        <div class="flex items-center" data-te-dropdown-ref>
                                            <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                                                id="fileManager-${fileData.id}" data-te-dropdown-toggle-ref aria-expanded="false">
                                                <i class="uil uil-ellipsis-v"></i>
                                            </button>
                                            <ul class="absolute z-[1000] ltr:float-left rtl:float-right hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100 px-[15px] py-[10px]"
                                                aria-labelledby="fileManager-${fileData.id}" data-te-dropdown-menu-ref>
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

                        content = `${filter}
                            <div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">Folders</div>
                            <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="FolderDataBox">${folder}</div>
                            <div class="text-[16px] leading-[1.25] font-medium text-dark dark:text-title-dark my-[20px] capitalize">Files</div>
                            <div class="grid grid-cols-12 sm:gap-[25px] max-sm:gap-y-[25px]" id="MediaDataBox">${file}</div>`;
                        $('#fileSystem').html(content);
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: 'Something Went Wrong',
                            position: 'topRight',
                            timeout: 5000
                        });
                    }
                },
                error: function(xhr) {
                    $('#loader').hide();
                    console.log(xhr.responseText); // Debugging info in console
                    iziToast.error({
                        title: 'Error',
                        message: 'Something Went Wrong',
                        position: 'topRight',
                        timeout: 5000
                    });
                }
            });
        }
    </script>
    
@endpush
