@extends('layouts.master')
@section('title', 'Wizam : User Groups')
@section('content')
    <section class=" mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">User Groups</h4>
                    <!-- Breadcrumb Navigation -->
                    <div class="flex flex-wrap justify-center">
                        <nav>
                            <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                                <!-- Parent Link -->
                                <li class="inline-flex items-center">
                                    <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group"
                                        href="{{route('admin-dashboard')}}">
                                        <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                                </li>
                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">User
                                        Groups</span>
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
                    List of User Groups</h1>
                <button type="button" id="addNew"
                    class="flex items-center px-[14px] text-sm text-white rounded-md  bg-primary border-primary h-10 gap-[6px] transition-[0.3s]"
                    data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light">
                   
                    <span class="m-0">+ Add Group</span>
                </button>
            </div>
            <div class="p-[25px] pt-[15px]">
                <div >
                    <table id="userGroupsTable" class="min-w-full leading-normal table-auto display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Visibility</th>
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

    <!-- Delete Modal -->
    <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
    id="exampleModalConfirm" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
        <div data-te-modal-dialog-ref
        class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
        <div class="relative flex flex-col w-full text-current bg-white border-none rounded-md shadow-lg outline-none pointer-events-auto bg-clip-padding dark:bg-neutral-600">
            <div class="relative flex items-start justify-start flex-auto p-5"
                data-te-modal-body-ref>
                <span class="text-warning text-[20px]">
                    <i class="uil uil-exclamation-circle"></i>
                </span>
                <div class="ms-4">
                    <h6 class="p-0 m-0 mb-3 font-semibold text-16 text-dark dark:text-title-dark">
                    Do you Want to delete these items?
                    </h6>
                    <p class="mb-3 text-14 fonnt-normal text-breadcrumbs">
                        This action cannot be undone. Click "<b>Confirm</b>" to proceed or "<b>Cancel</b>" to abort.
                    </p>
                </div>
            </div>
            <!--Modal footer-->
            <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4">
                <button type="button" class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                    data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                    Cancel
                </button>
                <button type="button" id="confirm_delete" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                    data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                    Confirm
                </button>
            </div>
        </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div data-te-modal-init
        class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[700px]" action="{{route('add-new-group')}}" method="POST" id="addNewGroup">
            @csrf
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="exampleModalLabel">
                        Create New Group
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
                    <!-- Group Name -->
                    <div class="mb-[15px]">
                        <label for="name"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Group Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1 ">
                            <input type="text" id="name" name="name" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                placeholder="Your Group Name">
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="mb-[15px]">
                        <label for="description"
                            class="inline-flex items-center mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Description <b class="ml-1">(Max. 1000 Characters)</b>
                        </label>
                        <div class="flex flex-col flex-1 ">
                            <textarea id="description" name="description" rows="3" 
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                placeholder="Your SEO Description"></textarea>
                        </div>
                    </div>
                    <!-- Active Checkbox -->
                    <div class="mb-[0.125rem] flex min-h-[1.5rem]">
                        <input name="status" data-default="In-active" data-checked="Active"
                            class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                            type="checkbox" id="checkboxActive" checked>
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxActive">
                            <span class="head font-semibold">Active</span><span class="text-red-500">*</span><br/>
                            <small>Active (Shown Everywhere). In-active (Hidden Everywhere)</small> 
                        </label>
                        
                    </div>
                    <!-- Public Group Checkbox -->
                    <div class="mb-[0.125rem] flex min-h-[1.5rem]">
                        <input name="visibility" data-checked="Private" data-default="Public" class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                            type="checkbox" id="checkboxPublicGroup">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxPublicGroup">
                            <span class="head font-semibold">Public</span><span class="text-red-500">*</span><br/>
                            <small>Private Group (Only admin can add users). Public Group (Anyone can join)</small> 
                        </label>
                    </div>
                    <!-- Is Free Checkbox -->
                    <div class="mb-[0.125rem] flex min-h-[1.5rem]">
                        <input name="is_free" data-checked="Paid" data-default="Free"
                            class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                            type="checkbox" id="checkboxIsFree">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxIsFree">
                                <span class="head font-semibold">Free</span><span class="text-red-500">*</span><br/>
                                <small>If the user is in this group, they don't have to pay for the paid exam</small> 
                        </label>
                    </div>
                </div>
                <!--Modal footer-->
                <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button type="button"
                        class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                        Cancel
                    </button>
                    <button type="submit"
                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
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
        id="editexampleModal" tabindex="-1" aria-labelledby="editexampleModalLabel" aria-hidden="true">
        <form data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[700px]" action="{{route('update-group-data')}}" method="POST" id="editGroupData">
            @csrf
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <!--Modal title-->
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                        id="editexampleModalLabel">
                        Edit Group
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
                    <!-- Group Name -->
                    <div class="mb-[15px]">
                        <label for="name"
                            class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Group Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1 ">
                            <input type="text" id="name" name="name" required
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                placeholder="Your Group Name">
                                <input type="hidden" name="id" value="">
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="mb-[15px]">
                        <label for="description"
                            class="inline-flex items-center mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Description <b class="ml-1">(Max. 1000 Characters)</b>
                        </label>
                        <div class="flex flex-col flex-1 ">
                            <textarea id="description" name="description" rows="3" 
                                class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                placeholder="Your SEO Description"></textarea>
                        </div>
                    </div>
                    <!-- Active Checkbox -->
                    <div class="mb-[15px] flex min-h-[1.5rem]">
                        <input name="status" data-checked="Active" data-default="In-active"
                            class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                            type="checkbox" id="checkboxActive" checked>
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxActive">
                            <span class="head font-semibold">Active</span><span class="text-red-500">*</span><br/>
                            <small>Active (Shown Everywhere). In-active (Hidden Everywhere)</small> 
                        </label>
                    </div>
                    <!-- Public Group Checkbox -->
                    <div class="mb-[15px] flex min-h-[1.5rem]">
                        <input name="visibility" data-checked="Private" data-default="Public" class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                            type="checkbox" id="checkboxPublicGroup">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxPublicGroup">
                            <span class="head font-semibold">Public</span><span class="text-red-500">*</span><br/>
                            <small>Private Group (Only admin can add users). Public Group (Anyone can join)</small> 
                        </label>
                    </div>
                    <!-- Is Free Checkbox -->
                    <div class="mb-[0.125rem] flex min-h-[1.5rem]">
                        <input name="is_free" data-checked="Paid" data-default="Free"
                            class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                            type="checkbox" id="checkboxIsFree">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="checkboxIsFree">
                            <span class="head font-semibold">Free</span><span
                            class="text-red-500">*</span><br/>
                           <small>If the user is in this group, they don't have to pay for the paid exam</small> 
                        </label>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button type="button"
                        class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                        Cancel
                    </button>
                    <button type="submit"
                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-ripple-init data-te-ripple-color="light">
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
<!-- jQuery -->
    <script>
       $(document).ready(function() {
            $('#userGroupsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("user-groups") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'visibility', name: 'visibility', orderable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                rowCallback: function(row, data) {
                    $(row).attr('id', 'row' + data.id);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $(document).on('click','#addNew',function(){
                let formData = $('#addNewGroup');
                formData.find('input[type="checkbox"]').each(function() {
                    $(this).trigger('change'); // Trigger the change event
                });
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $("#addNewGroup").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 200
                    },
                    description: {
                        maxlength: 1000
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        maxlength: "Name can't be greater than 200 characters"
                    },
                    description: {
                        maxlength: "Description can't be greater than 1000 characters"
                    }
                },
                errorPlacement: function(error, element) {
                    // Find or create the span for error messages
                    var errorSpan = element.next('small.text-danger');
                    if (errorSpan.length === 0) {
                        // Create a new span element if it does not exist
                        errorSpan = $('<small class="text-danger"></small>').insertAfter(element);
                    }
                    // Set the error message
                    errorSpan.html(error);
                },
                success: function(label, element) {
                    // Clear the error message
                    $(element).next('small.text-danger').text('');
                },
                submitHandler: function(form, e) {
                    e.preventDefault(); // Prevent default form submission

                    // Update button text to "Processing..." and disable the button
                    var $submitButton = $(form).find('button[type="submit"]');
                    $submitButton.html('Processing...').prop('disabled', true);
                    
                    var formData = $(form).serialize();
                    
                    $.ajax({
                        url: $(form).attr('action'), // Get the form's action URL
                        type: $(form).attr('method'), // Get the form's method (GET/POST)
                        data: formData, // Serialized form data
                        success: function(response) {
                            if (response.status === 'success') {
                                $(form).trigger("reset");
                                $submitButton.html('Submit').prop('disabled', false);
                                
                                // Hide the modal
                                $('#exampleModal').modal('hide'); 
                                
                                // Show success message
                                iziToast.success({
                                    title: 'Success',
                                    position: "topRight",
                                    message: response.message,
                                });

                                // Reload DataTable to show the new group
                                var table = $('#userGroupsTable').DataTable();
                                table.ajax.reload(); // Correct way to reload data
                            } else {
                                $submitButton.html('Submit').prop('disabled', false);
                                // Show error message
                                iziToast.error({
                                    title: 'Error',
                                    position: "topRight",
                                    message: response.message,
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            $submitButton.html('Submit').prop('disabled', false);
                            console.log(xhr.responseText);
                            iziToast.error({
                                title: 'Error',
                                position: "topRight",
                                message: 'Something went wrong. Please try again.',
                            });
                        }
                    });
                },
            });
        });
    </script>

    {{-- DELETE GROUP --}}
    <script>
        $(document).ready(function(){
            $(document).on('click','.deleteItem',function(){
                const id = $(this).data('id');
                $('#confirm_delete').data('id',id);
            })
        });

        $('#confirm_delete').on('click', function() {
            var id = $(this).data('id'); // Get the ID from the modal
            console.log('Deleting item with ID:', id);
            $.ajax({
                url: "{{route('user-groups-delete')}}", // Route to your Laravel controller
                method: "POST", // HTTP method
                data: {
                    _token: "{{csrf_token()}}", // CSRF token for security
                    id: id 
                },
                success: function(response) {
                    // Handle success response
                    if (response.status === 'success') {
                        iziToast.success({
                            title: 'Success',
                            position:"topRight",
                            message: response.message,
                        });
                    } else {
                        iziToast.error({
                            title: 'Error',
                            position:"topRight",
                            message: response.message,
                        });
                    }
                },
                error: function(xhr) {
                    // Handle error response
                    iziToast.error({
                        title: 'Error',
                        position:"topRight",
                        message: 'An error occurred: ' + xhr.status + ' ' + xhr.statusText,
                    });
                }
            });
            $(`#row${id}`).hide();
        });
    </script>

    {{-- Edit Catgeory --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.editItem', function() {
                const id = $(this).data('id');
                let data = [];
                data['id'] = $(this).data('id');
                data['name'] = $(this).data('name');
                data['description'] = $(this).data('description');
                data['free'] = $(this).data('free');
                data['active'] = $(this).data('active');
                data['visibility'] = $(this).data('visibility');


                let formData = $('#editGroupData');
                $(formData).find('input[name="name"]').val(data['name']);
                $(formData).find('input[name="id"]').val(data['id']);
                $(formData).find('textarea[name="description"]').html(data['description']);
                $(formData).find('input[name="status"]').attr("checked",data['active'] == 1 ? true : false);
                $(formData).find('input[name="visibility"]').attr("checked",data['visibility'] == 0 ? true : false);
                $(formData).find('input[name="is_free"]').attr("checked",data['free'] == 0 ? true : false);


                formData.find('input[type="checkbox"]').each(function() {
                    $(this).trigger('change'); // Trigger the change event
                });
            });
        });
    </script>

    <script>
        $(document).ready(function(){
            $(document).on('submit','.editGroupData',function(){
                $.ajax({
                    url: "{{route('update-group-data')}}", // Route to your Laravel controller
                    method: "POST", // HTTP method
                    data: {
                        _token: "{{csrf_token()}}", // CSRF token for security
                        id: id,
                        formData
                    },
                    success: function(response) {
                        // Handle success response
                        if (response.status === 'success') {
                            iziToast.success({
                                title: 'Success',
                                position:"topRight",
                                message: response.message,
                            });
                        } else {
                            iziToast.error({
                                title: 'Error',
                                position:"topRight",
                                message: response.message,
                            });
                        }
                    },
                    error: function(xhr) {
                        // Handle error response
                        iziToast.error({
                            title: 'Error',
                            position:"topRight",
                            message: 'An error occurred: ' + xhr.status + ' ' + xhr.statusText,
                        });
                    }
                });
            })
        })

        $(document).ready(function() {
            $("#editGroupData").validate({
                rules: {
                    name: {
                        required: true,
                        maxlength: 200
                    },
                    description: {
                        maxlength: 1000
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        maxlength: "Name can't be greater than 200 characters"
                    },
                    description: {
                        maxlength: "Description can't be greater than 1000 characters"
                    }
                },
                errorPlacement: function(error, element) {
                    // Find or create the span for error messages
                    var errorSpan = element.next('small.text-danger');
                    if (errorSpan.length === 0) {
                        // Create a new span element if it does not exist
                        errorSpan = $('<small class="text-danger"></small>').insertAfter(element);
                    }
                    // Set the error message
                    errorSpan.html(error);
                },
                success: function(label, element) {
                    // Clear the error message
                    $(element).next('small.text-danger').text('');
                },
                submitHandler: function(form, e) {
                    e.preventDefault(); // Prevent default form submission

                    // Update button text to "Processing..." and disable the button
                    var $submitButton = $(form).find('button[type="submit"]');
                    $submitButton.html('Processing...').prop('disabled', true);
                    
                    var formData = $(form).serialize();
                    
                    $.ajax({
                        url: $(form).attr('action'), // Get the form's action URL
                        type: $(form).attr('method'), // Get the form's method (GET/POST)
                        data: formData, // Serialized form data
                        success: function(response) {
                            if (response.status === 'success') {
                                $(form).trigger("reset");
                                $submitButton.html('Submit').prop('disabled', false);
                                
                                // Hide the modal
                                $('#editexampleModal').modal('hide'); 
                                
                                // Show success message
                                iziToast.success({
                                    title: 'Success',
                                    position: "topRight",
                                    message: response.message,
                                });

                                // Reload DataTable to show the new group
                                var table = $('#userGroupsTable').DataTable();
                                table.ajax.reload(); // Correct way to reload data
                            } else {
                                $submitButton.html('Submit').prop('disabled', false);
                                // Show error message
                                iziToast.error({
                                    title: 'Error',
                                    position: "topRight",
                                    message: response.message,
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            $submitButton.html('Submit').prop('disabled', false);
                            console.log(xhr.responseText);
                            iziToast.error({
                                title: 'Error',
                                position: "topRight",
                                message: 'Something went wrong. Please try again.',
                            });
                        }
                    });
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Handle checkbox change event
            $(document).on('change', 'input[type="checkbox"]', function() {
                var $checkbox = $(this); // Current checkbox
                
                // Get the data attributes from the checkbox
                var checkedText = $checkbox.data('checked');
                var defaultText = $checkbox.data('default');
                
                // Find the closest label and update its text
                var $label = $checkbox.closest('div').find('.head');
                
                // Update the text based on the checkbox state
                if ($checkbox.is(':checked')) {
                    $label.text(checkedText); // Set text to data-checked value
                } else {
                    $label.text(defaultText); // Set text to data-default value
                }
            });
        });

    </script>
    <!-- Alert Component -->
        <x-alert/>
    <!-- End Alert Component -->
@endpush