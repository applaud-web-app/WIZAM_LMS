@extends('layouts.master')

@section('title', 'Email Settings')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Email Settings</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="{{route('admin-dashboard')}}">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Middle (Conditional) -->

                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]">
                        <span class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 transition duration-300 capitalize">Setting</span>
                     </li>

                     <!-- Child (Current Page) -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Email Settings</span>
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
                <form action="{{ route('update-email-settings') }}" method="POST" autocomplete="off" id="updateEmailSettings">
                    @csrf
                    <div class="mb-[15px]">
                        <label for="hostName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Host Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" value="@isset($generalSetting->host_name){{$generalSetting->host_name}}@endisset" id="hostName" name="host_name" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Host Name" required>
                        </div>
                    </div>
                
                    <div class="mb-[15px]">
                        <label for="portNumber" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Port Number <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="number" value="@isset($generalSetting->port){{$generalSetting->port}}@endisset" id="portNumber" name="port" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Port Number" required>
                        </div>
                    </div>
                
                    <div class="mb-[15px]">
                        <label for="userName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            User Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="userName" name="userName" value="@isset($generalSetting->username){{$generalSetting->username}}@endisset" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your User Name" required>
                        </div>
                    </div>
                
                    <div class="mb-[15px]">
                        <label for="password" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="password" id="password" name="password" value="@isset($generalSetting->password){{$generalSetting->password}}@endisset" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Password" required>
                        </div>
                    </div>
                
                    <div class="mb-[15px]">
                        <label for="encryption" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            Encryption <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="encryption" name="encryption" value="@isset($generalSetting->encryption){{$generalSetting->encryption}}@endisset" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your Encryption" required>
                        </div>
                    </div>
                
                    <div class="mb-[15px]">
                        <label for="fromAddress" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            From Address <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="email" id="fromAddress" name="from_mail" value="@isset($generalSetting->from_mail){{$generalSetting->from_mail}}@endisset" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your From Address" required>
                        </div>
                    </div>
                
                    <div class="mb-[15px]">
                        <label for="fromName" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                            From Name <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-col flex-1">
                            <input type="text" id="fromName" name="from_name" value="@isset($generalSetting->from_name){{$generalSetting->from_name}}@endisset" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Your From Name" required>
                        </div>
                    </div>
                
                    <!-- Submit Button -->
                    <div class="mb-[15px]">
                        <button type="submit" class="mt-3 bg-primary text-white py-[10px] px-[20px] rounded-md">
                            Save Changes
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
      // jQuery Validation for the Add Section form
      $("#updateEmailSettings").validate({
        rules: {
            host_name: {
                required: true,
                maxlength: 255
            },
            port: {
                required: true,
                digits: true,
                min: 1,
                max: 65535
            },
            userName: {
                required: true,
                maxlength: 255
            },
            password: {
                required: true,
                minlength: 6
            },
            encryption: {
                required: true,
                maxlength: 50
            },
            from_mail: {
                required: true,
                email: true
            },
            from_name: {
                required: true,
                maxlength: 255
            }
        },
        messages: {
            host_name: {
                required: "Please enter the host name",
                maxlength: "Host name cannot exceed 255 characters"
            },
            port: {
                required: "Please enter the port number",
                digits: "Port number must be a valid number",
                min: "Port number cannot be less than 1",
                max: "Port number cannot exceed 65535"
            },
            userName: {
                required: "Please enter the username",
                maxlength: "Username cannot exceed 255 characters"
            },
            password: {
                required: "Please provide the password",
                minlength: "Password must be at least 6 characters long"
            },
            encryption: {
                required: "Please enter the encryption type (e.g., SSL/TLS)",
                maxlength: "Encryption type cannot exceed 50 characters"
            },
            from_mail: {
                required: "Please enter the 'from' email address",
                email: "Please enter a valid email address"
            },
            from_name: {
                required: "Please enter the 'from' name",
                maxlength: "From name cannot exceed 255 characters"
            }
        },
        submitHandler: function(form) {
            $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
            form.submit();
        }
    });
   </script>
@endpush
