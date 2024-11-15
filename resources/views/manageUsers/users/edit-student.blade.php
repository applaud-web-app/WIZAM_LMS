@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">

            <!-- Breadcrumb Section -->
            <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                <!-- Title -->
                <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Edit Student</h4>
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
                                <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Edit Student</span>
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
                    <form action="{{url()->full()}}" method="POST" class="ssm:grid ssm:grid-cols-12 max-ssm:flex-col max-ssm:flex gap-[15px]" enctype="multipart/form-data" id="editUser">
                        @csrf
                        <!-- Title -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Title
                            </label>
                            <div class="flex flex-col flex-1">
                                <select id="title" name="title" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary">
                                    <option selected disabled>Select Title</option>
                                    <option value="Mr" {{$user->title == "Mr" ? "selected" : ""}}>Mr.</option>
                                    <option value="Mrs" {{$user->title == "Mrs" ? "selected" : ""}}>Mrs.</option>
                                    <option value="Miss" {{$user->title == "Miss" ? "selected" : ""}}>Miss</option>
                                    <option value="Ms" {{$user->title == "Ms" ? "selected" : ""}}>Ms.</option>
                                    <option value="Other" {{$user->title == "Other" ? "selected" : ""}}>Other</option>
                                </select>
                            </div>
                        </div>
                        <!-- First Name -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="full_name" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="text" id="full_name" value="{{$user->name}}" name="full_name" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Full Name" required>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label for="image" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Profile Image 
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="file" id="image" name="image" class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white" >
                            </div>
                            @isset($user->image)
                                <img src="{{$user->image}}" alt="" class="w-[100px] h-[100px]">
                            @endisset
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="dob" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="date" id="dob" name="dob" value="{{$user->dob}}" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" required>
                            </div>
                        </div>

                        <!-- Nationality -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="nationality" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Nationality <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1">
                                <select id="nationality" name="nationality" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary" required>
                                    <option selected disabled>Select Nationality</option>
                                    @isset($country)
                                        @foreach ($country as $item)
                                            <option value="{{$item->id}}" {{$user->country == $item->id ? "selected" : ""}}>{{$item->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="phone" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="text" id="phone" value="{{$user->phone_number}}" name="phone" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Phone Number" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="email" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="email" id="email" value="{{$user->email}}" name="email" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="example@gmail.com" required>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6">
                            <label for="groups" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                User Groups
                            </label>
                            <div class="flex flex-col flex-1">
                                <select id="groups" name="groups[]" 
                                    data-te-select-init 
                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto" 
                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" 
                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none" 
                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" 
                                    data-te-select-placeholder="Select Groups" 
                                    multiple>
                                    @isset($userGroups)
                                        @foreach ($userGroups as $item)
                                            <option value="{{ $item->id }}" {{ in_array($item->id, $groups) ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>

                        @if ($user->roles->first()->name == "student")
                            <div class="col-span-12 md:col-span-6">
                                <label for="exams" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Exams
                                </label>
                                <div class="flex flex-col flex-1">
                                    <select id="exams" name="exams[]" 
                                        data-te-select-init 
                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] w-full capitalize [&~span]:top-[18px] [&~span]:w-[12px] [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-regular dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[0.75rem] rtl:[&~span]:left-[0.75rem] rtl:[&~span]:right-auto" 
                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" 
                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none" 
                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" 
                                        data-te-select-placeholder="Select Exams" 
                                        multiple>
                                        @isset($examGroup)
                                            @foreach ($examGroup as $item)
                                                <option value="{{ $item->id }}" {{ in_array($item->id, $exams) ? 'selected' : '' }}>
                                                    {{$item->title}}
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Password -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="password" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Password 
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="password" id="password" name="password" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter Password">
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="col-span-12 md:col-span-6">
                            <label for="password_confirmation" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Confirm Password 
                            </label>
                            <div class="flex flex-col flex-1">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Confirm Password">
                            </div>
                        </div>

                        <!-- Email Verified -->
                        {{-- <div class="col-span-12 md:col-span-6">
                            <label for="email_verified" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Email Verified <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-col flex-1">
                                <select id="email_verified" name="email_verified" class="py-[13px] px-[20px] w-full capitalize rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-light dark:text-subtitle-dark outline-none focus:ring-primary focus:border-primary" required>
                                    <option value="yes" {{$user->email_verified_at == null ? "" : "selected"}}>Yes</option>
                                    <option value="no" {{$user->email_verified_at == null ? "selected" : ""}}>No</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-span-12 md:col-span-6">
                            <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap items-center gap-[15px]">
                                <!--First radio-->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                   <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="statusRadio1" value="1" autocompleted="" {{$user->status == 1 ? "checked" : ""}}>
                                   <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio1">Enable</label>
                                </div>
                                <!--Second radio-->
                                <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                                   <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="statusRadio2" value="0" autocompleted="" {{$user->status == 0 ? "checked" : ""}}>
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

@push('scripts')
<script>
    $(document).ready(function () {
        $("#editUser").validate({
            rules: {
                full_name: {
                    required: true,
                    minlength: 3
                },
                phone : {
                    required: true,
                },
                dob: {
                    required: true,
                    date: true
                },
                nationality: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                role: {
                    required: true
                },
                email_verified: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                full_name: {
                    required: "Please enter the user's full name",
                    minlength: "Name must be at least 3 characters long"
                },
                phone: {
                    required: "Please provide a Phone Number"
                },
                dob: {
                    required: "Please select the date of birth"
                },
                nationality: {
                    required: "Please select nationality"
                },
                email: {
                    required: "Please enter the email address",
                    email: "Please enter a valid email address"
                }
            },
            submitHandler: function (form) {
                $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                form.submit(); // Submit the form if validation passes
            }
        });

        // Custom password validation rule
        $.validator.addMethod("pwcheck", function (value) {
            return /[A-Z]/.test(value) && /[a-z]/.test(value) && /\d/.test(value);
        }, "Password must contain at least one uppercase letter, one lowercase letter, and one number.");
    });
</script>
@endpush