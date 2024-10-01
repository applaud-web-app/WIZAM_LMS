@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

    <section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">

                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Subcategories</h4>
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
                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Subcategories</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div
            class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div
                class="px-[25px] py-3 text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                <h1
                    class="mb-0 inline-flex items-center py-1 overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                    List of Subcategories</h1>
                <button type="button"
                    class="flex items-center px-[14px] text-sm text-white rounded-md bg-primary border-primary h-10 gap-[6px] transition-[0.3s]"
                    data-te-toggle="modal" data-te-target="#subcategoryModal" data-te-ripple-init
                    data-te-ripple-color="light">
                    <i class="uil uil-plus"></i>
                    <span class="m-0">Add Subcategory</span>
                </button>
            </div>
            <div class="p-[25px] pt-[15px]">

                <div>
                    <table id="sub-category-table" class="min-w-full leading-normal table-auto display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Parent Category</th>
                                <th>Type</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

    <!-- Add Modal -->
    <div data-te-modal-init
        class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="subcategoryModal" tabindex="-1" aria-labelledby="subcategoryModalLabel" aria-hidden="true">
        <form method="POST" action="{{ route('add-sub-category') }}" data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
            id="addSubCatgeory">
            @csrf
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="subcategoryModalLabel">
                        Create New Subcategory
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
                    <!-- Subcategory Name -->
                    <div class="mb-[15px]">
                        <label for="subcategoryName"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Subcategory Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="subcategoryName" name="subcategory_name" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                placeholder="Subcategory Name">
                        </div>
                    </div>
                    <!-- Parent Category Selection -->
                    <div class="mb-[15px]">
                        <label for="parentCategory"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Parent Category <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <select id="parentCategory" name="parent_category" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                <option selected disabled>Select Category</option>
                                @isset($category)
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endisset
                                <!-- Add more parent categories as needed -->
                            </select>
                        </div>
                    </div>
                    <!-- Type Selection -->
                    <div class="mb-[15px]">
                        <label for="type"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <select id="type" name="subcategory_type" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                <option selected disabled>Select Type</option>
                                <option value="Course">Course</option>
                                <option value="Certification">Certification</option>
                                <option value="Class">Class</option>
                                <option value="Exam">Exam</option>
                                <option value="Grade">Grade</option>
                                <option value="Standard">Standard</option>
                                <option value="Stream">Stream</option>
                                <option value="Level">Level</option>
                                <option value="Skill">Skill</option>
                                <option value="Branch">Branch</option>
                                <!-- Add more types if necessary -->
                            </select>
                        </div>
                    </div>

                    <!-- Map Sections -->
                    <div class="col-span-12 md:col-span-6 mb-[15px]">
                        <label for="groups"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Map Sections <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <select id="map_section" name="map_section[]" data-te-select-init
                                data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                data-te-select-placeholder="Map Sections" multiple>
                                @isset($section)
                                    @foreach ($section as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-[15px]">
                        <label for="subcategoryDescription"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Description
                        </label>
                        <div class="flex flex-col flex-1">
                            <textarea id="subcategoryDescription" name="subcategory_description" rows="3"
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                placeholder="Subcategory Description"></textarea>
                        </div>
                    </div>
                    <!-- Status Radio Buttons -->
                    <div class="mb-[15px]">
                        <label for="status"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-wrap items-center gap-[10px]">
                            <!--First radio-->
                            <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                <input
                                    class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                    type="radio" name="subcategory_status" id="subcategory_status" value="1"
                                    autocompleted="" checked>
                                <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                    for="subcategory_status">Enable</label>
                            </div>
                            <!--Second radio-->
                            <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                <input
                                    class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                    type="radio" name="subcategory_status" id="subcategory_status" value="0"
                                    autocompleted="">
                                <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                    for="subcategory_status">Disable</label>
                            </div>

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

    <!-- Edit Modal -->
    <div data-te-modal-init
        class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <form method="POST" data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
            id="editSubCatgeory">
            @csrf
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="subcategoryModalLabel">
                        Edit Subcategory
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
                    <!-- Subcategory Name -->
                    <div class="mb-[15px]">
                        <label for="subcategoryName"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Subcategory Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="subcategoryName" name="subcategory_name" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                placeholder="Subcategory Name">
                        </div>
                    </div>
                    <!-- Parent Category Selection -->
                    <div class="mb-[15px]">
                        <label for="parentCategory"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Parent Category <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <select id="parentCategory" name="parent_category" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                <option selected disabled>Select Category</option>
                                @isset($category)
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endisset
                                <!-- Add more parent categories as needed -->
                            </select>
                        </div>
                    </div>
                    <!-- Type Selection -->
                    <div class="mb-[15px]">
                        <label for="type"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <select id="type" name="subcategory_type" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                <option selected disabled>Select Type</option>
                                <option value="Course">Course</option>
                                <option value="Certification">Certification</option>
                                <option value="Class">Class</option>
                                <option value="Exam">Exam</option>
                                <option value="Grade">Grade</option>
                                <option value="Standard">Standard</option>
                                <option value="Stream">Stream</option>
                                <option value="Level">Level</option>
                                <option value="Skill">Skill</option>
                                <option value="Branch">Branch</option>
                                <!-- Add more types if necessary -->
                            </select>
                        </div>
                    </div>

                    <!-- Map Sections -->
                    <div class="col-span-12 md:col-span-6 mb-[15px]">
                        <label for="groups"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Map Sections <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <select id="editmap_section" name="map_section[]" data-te-select-init
                                data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto"
                                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                data-te-select-placeholder="Map Sections" multiple>
                                @isset($section)
                                    @foreach ($section as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-[15px]">
                        <label for="subcategoryDescription"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Description
                        </label>
                        <div class="flex flex-col flex-1">
                            <textarea id="subcategoryDescription" name="subcategory_description" rows="3"
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                placeholder="Subcategory Description"></textarea>
                        </div>
                    </div>
                    <!-- Status Radio Buttons -->
                    <div class="mb-[15px]">
                        <label for="status"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-wrap items-center gap-[10px]">
                            <!--First radio-->
                            <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                <input
                                    class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                    type="radio" name="subcategory_status" id="subcategory_status" value="1"
                                    autocompleted="" checked>
                                <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                    for="subcategory_status">Enable</label>
                            </div>
                            <!--Second radio-->
                            <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                <input
                                    class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary"
                                    type="radio" name="subcategory_status" id="subcategory_status" value="0"
                                    autocompleted="">
                                <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer"
                                    for="subcategory_status">Disable</label>
                            </div>
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

    <!-- Delete Modal -->
    <div data-te-modal-init
        class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div data-te-modal-dialog-ref
            class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div
                class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div
                    class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="exampleModalLabel">
                        Do you Want to delete these items?
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
                    <p class="mb-3 text-breadcrumbs dark:text-subtitle-dark">This action cannot be undone. Click "Confirm"
                        to proceed or "Cancel" to abort.</p>
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
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTables initialization
            $('#sub-category-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('view-sub-category') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'parent_category',
                        name: 'parent_category'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });


            subcategoryName, parentCategory, type, subcategoryDescription, subcategory_status
            // jQuery Validation for the Add Section form
            $("#addSubCatgeory").validate({
                rules: {
                    subcategoryName: {
                        required: true,
                        minlength: 3
                    },
                    parentCategory: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    subcategoryDescription: {
                        maxlength: 1000
                    },
                    subcategory_status: {
                        required: true
                    }
                },
                messages: {
                    subcategoryName: {
                        required: "Please enter a section name",
                        minlength: "Section name must be at least 3 characters long"
                    },
                    parentCategory: {
                        required: "This field is required."
                    },
                    type: {
                        required: "This field is required."
                    },
                    subcategoryDescription: {
                        maxlength: "Description cannot exceed 1000 characters"
                    },
                    subcategory_status: {
                        required: "Please select a status"
                    }
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                    // Form submission code can go here
                    form.submit();
                }
            });

            $("#editSubCatgeory").validate({
                rules: {
                    subcategoryName: {
                        required: true,
                        minlength: 3
                    },
                    parentCategory: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    subcategoryDescription: {
                        maxlength: 1000
                    },
                    subcategory_status: {
                        required: true
                    }
                },
                messages: {
                    subcategoryName: {
                        required: "Please enter a section name",
                        minlength: "Section name must be at least 3 characters long"
                    },
                    parentCategory: {
                        required: "This field is required."
                    },
                    type: {
                        required: "This field is required."
                    },
                    subcategoryDescription: {
                        maxlength: "Description cannot exceed 1000 characters"
                    },
                    subcategory_status: {
                        required: "Please select a status"
                    }
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                    // Form submission code can go here
                    form.submit();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            // When delete item is clicked, store the URL in the confirm button
            $(document).on('click', '.deleteItem', function(){
                  const delUrl = $(this).data('url');
                  console.log(delUrl);
                  $('#confirmDelete').data('url', delUrl); // Use data method to set the URL
            });
      
            // When confirm delete is clicked, redirect to the URL
            $(document).on('click', '#confirmDelete', function(){
                  const delUrl = $(this).data('url'); // Use data method to get the URL
                  window.location.href = delUrl;
            });

            
            $(document).on('click', '.editItem', function() {
                // Retrieve data attributes
                const editUrl = $(this).data('url');
                const name = $(this).data('name');
                const category = $(this).data('category');
                const type = $(this).data('type');
                const description = $(this).data('description');
                const status = $(this).data('status');
                let sections = $(this).data(
                'sections'); // Assuming sections is the array of selected values

                console.log("Sections Data (before processing):", sections);

                // Convert sections to an array if it's a JSON string or object
                if (typeof sections === 'string') {
                    sections = JSON.parse(sections);
                }
                if (typeof sections === 'object' && !Array.isArray(sections)) {
                    sections = Object.values(sections);
                }

                console.log("Final Sections Array:", sections);

                // Set form action
                let form = $('#editSubCatgeory');
                form.attr('action', editUrl);

                // Update form fields
                form.find('input[name="subcategory_name"]').val(name);
                form.find('textarea[name="subcategory_description"]').val(description);

                // Set radio button based on status
                form.find('input[name="subcategory_status"]').each(function() {
                    $(this).prop('checked', $(this).val() == status);
                });

                // Set the parent category and subcategory type
                form.find('select[name="parent_category"]').val(category);
                form.find('select[name="subcategory_type"]').val(type);

                // Reference to the dropdown
                const dropdown = $('#editmap_section');

                // Clear existing selections
                dropdown.find('option').prop('selected', false);

                // Set the selected options based on the array
                dropdown.val(sections);

                // Trigger change event to update the UI
                dropdown.trigger('change');

                // Display the dropdown if it's hidden
                dropdown.hide();

                // Check if the selections are applied
                console.log("Selected options after setting:", dropdown.val());
            });
        });
    </script>
@endpush
