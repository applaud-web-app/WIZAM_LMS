@extends('layouts.master')

@section('title', 'Add User')

@section('content')
<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">

            <!-- Breadcrumb Section -->
            <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                <!-- Title -->
                <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Add User</h4>
                <!-- Breadcrumb Navigation -->
                <div class="flex flex-wrap justify-center">
                    <nav>
                        <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                            <!-- Parent Link -->
                            <li class="inline-flex items-center">
                                <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="">
                                    <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                            </li>
                            <!-- Current Page -->
                            <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                                <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Add User</span>
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
                    <form action="" method="POST" class="ssm:grid ssm:grid-cols-12 max-ssm:flex-col max-ssm:flex gap-[15px]">
                        @csrf

                        <!-- Title -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Title
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <select id="title" name="title" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary">
                                    <option value="Mr">Mr.</option>
                                    <option value="Mrs">Mrs.</option>
                                    <option value="Miss">Miss</option>
                                    <option value="Ms">Ms.</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- First Name -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="first_name" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" id="first_name" name="first_name" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="First Name" required>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="last_name" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" id="last_name" name="last_name" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Last Name" required>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="dob" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Date of Birth 
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="date" id="dob" name="dob" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                            </div>
                        </div>

                        <!-- Nationality -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="nationality" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Nationality 
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <select id="nationality" name="nationality" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary" >
                                    <option value="USA">United States</option>
                                    <option value="Canada">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="Australia">Australia</option>
                                    <option value="India">India</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="phone" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Phone Number
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" id="phone" name="phone" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Phone Number">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="email" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="email" id="email" name="email" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <!-- User Role -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="role" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                User Role <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <select id="role" name="role" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary" required>
                                    <option value="admin">Admin</option>
                                    <option value="instructor">Instructor</option>
                                    <option value="student">Student</option>
                                    <!-- Add more roles as needed -->
                                </select>
                            </div>
                        </div>

                        <!-- User Groups -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="groups" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                User Groups
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="text" id="groups" name="groups" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter User Groups">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="password" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="password" id="password" name="password" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter Password" required>
                            </div>
                            <p class="text-xs text-red-500 mt-1">
                                Password must contain at least 8 characters, including an uppercase letter, a lowercase letter, and a number.
                            </p>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="password_confirmation" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Confirm Password" required>
                            </div>
                        </div>

                        <!-- Email Verified -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="email_verified" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Email Verified <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <select id="email_verified" name="email_verified" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary" required>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap items-center gap-[15px]">
                                <!--First radio-->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                   <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="statusRadioOptions" id="statusRadio1" value="option1" autocompleted="">
                                   <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio1">Enable</label>
                                </div>
                                <!--Second radio-->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                   <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="statusRadioOptions" id="statusRadio2" value="option2" autocompleted="">
                                   <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio2">Disable</label>
                                </div>
                            
                             </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="col-span-12 md:col-span-12">
                            <button type="submit" class="px-[30px] py-[10px] rounded bg-primary text-white capitalize hover:bg-primary-dark focus:ring-primary focus:outline-none">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
