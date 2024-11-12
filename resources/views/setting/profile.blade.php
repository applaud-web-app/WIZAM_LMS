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
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">

                    <div class="p-[25px]">
                        <form id="editProfileForm" action="{{ route('update-profile') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="name">Name:</label>
                                <input class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" type="text" id="name" name="name" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="email">Email:</label>
                                <input class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" type="email" id="email" name="email" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="phone_number">Phone Number:</label>
                                <input class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" type="text" id="phone_number" name="phone_number" value="{{ $user->phone_number }}">
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="dob">Date of Birth:</label>
                                <input class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" type="date" id="dob" name="dob" value="{{ $user->dob }}">
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="country">Country:</label>
                                <select name="country" id="country" class="w-full rounded border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark px-4 py-2">
                                    <option selected disabled>Select Country</option>
                                    @isset($country)
                                        @foreach ($country as $item)
                                            <option value="{{$item->id}}" {{$item->id == $user->country ? 'selected': ''}}>{{$item->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="password">Password:</label>
                                <input class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" type="password" id="password" name="password">
                            </div>
                            <div class="mb-3">
                                <label class="text-sm text-body dark:text-subtitle-dark mb-2" for="password_confirmation">Confirm Password:</label>
                                <input class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" type="password" id="password_confirmation" name="password_confirmation">
                            </div>
                            <div>
                                <button type="submit" class="w-full px-[30px] py-[10px] rounded bg-primary text-white capitalize hover:bg-primary-dark focus:ring-primary focus:outline-none">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editProfileForm').validate({
            rules: {
                name: {
                    required: true
                },
                phone_number: {
                    required: false
                },
                password: {
                    minlength: 8,
                    pwcheck: function() {
                        return $('#password').val().length > 0; // Apply rule only if password is entered
                    }
                },
                password_confirmation: {
                    equalTo: "#password"
                }
            },
            messages: {
                name: {
                    required: "Please enter your name"
                },
                password: {
                    minlength: "Password must be at least 8 characters"
                },
                password_confirmation: {
                    equalTo: "Passwords do not match"
                }
            }
        });

        // Custom password validation rule
        $.validator.addMethod("pwcheck", function(value) {
            if (value.length > 0) {
                return /[A-Z]/.test(value) && /[a-z]/.test(value) && /\d/.test(value);
            }
            return true; // Skip validation if password is not entered
        }, "Password must contain at least one uppercase letter, one lowercase letter, and one number.");
    });
</script>
@endpush