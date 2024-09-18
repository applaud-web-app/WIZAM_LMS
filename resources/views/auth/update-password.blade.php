<!doctype html>
<html lang="en" dir="ltr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <meta name="description" content="This is a page about log-in.">
   <meta name="keywords" content="buttons, web development, UI components">
   <meta name="author" content="dashboardmarket.com">
   <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/favicon.ico') }}">
   <title>Wizam | Update Password</title>
   @vite(['resources/css/app.css','resources/js/app.js'])
   <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.css" integrity="sha512-DIW4FkYTOxjCqRt7oS9BFO+nVOwDL4bzukDyDtMO7crjUZhwpyrWBFroq+IqRe6VnJkTpRAS6nhDvf0w+wHmxg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="scrollbar">
   <main class="relative bg-top bg-no-repeat dark:bg-[#1e2836] bg-contain bg-cyan-50">
      <div class="h-[calc(var(--vh,1vh)_*_100)] w-full flex">
         <div class="flex flex-col justify-center w-full max-w-[520px] px-[30px] mx-auto my-[150px]">
            <a href="index.html" class="text-center">
               <img src="{{ asset('assets/images/logos/logo-dark.png')}}" alt="image" class="inline w-auto h-[64px] dark:hidden">
               <img src="{{ asset('assets/images/logos/logo-white.png')}}" alt="image" class="hidden w-auto h-[64px] dark:inline">
            </a>
            <div class="rounded-6 mt-[25px] shadow-sm dark:shadow-regular bg-white dark:bg-[#111726]">
               <div class="p-[25px] text-center border-b border-regular dark:border-white/[.05] top">
                  <h2 class="text-18 font-semibold leading-[1] mb-0 text-dark dark:text-title-dark">New Password</h2>
               </div>
               <div class="py-[30px] px-[40px]">
                  <p class="text-[16px] text-light dark:text-subtitle-dark mb-[15px]">Please enter a strong password with at least 8 characters, including at least one uppercase letter, one lowercase letter, one digit, and one special character.</p>
                  <form id="updatepassword" action="{{url()->full()}}" method="post">
                     @csrf
                     <div class="mb-5">
                        <label for="password" class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">
                           Password <span class="text-danger">*</span></label>
                        <div class="relative w-full">
                           <div class="absolute inset-y-0 end-0 flex items-center px-[15px]">
                              <input class="hidden js-password-toggle" id="toggle" type="checkbox">
                              <label class=" rounded cursor-pointer text-light text-[15px] js-password-label dark:text-subtitle-dark" for="toggle"><i class="uil uil-eye-slash"></i></label>
                           </div>
                           <input class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary js-password" id="password" name="password" type="password" autocomplete="off" placeholder="Password" required>
                           <span class="text-danger"></span>
                        </div>
                     </div>
                     <div class="mb-5">
                        <label for="confirm_password" class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">
                           Confirm Password <span class="text-danger">*</span></label>
                        <div class="relative w-full">
                           <div class="absolute inset-y-0 end-0 flex items-center px-[15px]">
                              <input class="hidden js-password-toggle" id="toggle" type="checkbox">
                              <label class=" rounded cursor-pointer text-light text-[15px] js-password-label dark:text-subtitle-dark" for="toggle"><i class="uil uil-eye-slash"></i></label>
                           </div>
                           <input class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary js-password" id="confirm_password" name="confirm_password" type="password" autocomplete="off" placeholder="Confirm Password" required>
                           <span class="text-danger"></span>
                        </div>
                     </div>
                     <button type="submit" class="inline-flex w-full items-center justify-center h-[48px] text-14 rounded-6 font-medium bg-primary text-white cursor-pointer hover:bg-primary-hbr border-primary transition duration-300 px-[50px]" value="submit">Update Password</button>
                  </form>
               </div>
               <div class="text-center p-[25px] rounded-b-6 bg-white-100 border-t dark:bg-gray-600">
                  <p class="text-[14px] font-medium text-body dark:text-title-dark inline-flex items-center gap-[6px] mb-0">
                     Back to Sign In? <a class="transition duration-300 text-primary hover:text-dark dark:text-dark dark:hover:text-subtitle-dark" href="{{route('admin-login')}}">Sign In</a>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </main>

   <script src="{{asset('/assets/js/plugins.min.js')}}"></script>
   <script src="{{asset('/assets/js/script.min.js')}}"></script>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <script>
   $(document).ready(function() {
      // Add custom method for password pattern validation
      $.validator.addMethod("passwordPattern", function(value, element) {
         return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
      }, "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");

      $("#updatepassword").validate({
         rules: {
               password: {
                  required: true,
                  minlength: 8,
                  passwordPattern: true // Use custom method for pattern validation
               },
               confirm_password: {
                  required: true,
                  equalTo: "#password"
               }
         },
         messages: {
               password: {
                  required: "Please enter your password",
                  minlength: "Your password must be at least 8 characters long"
               },
               confirm_password: {
                  required: "Please confirm your password",
                  equalTo: "Passwords do not match"
               }
         },
         errorPlacement: function(error, element) {
               var errorMsg = element.siblings("span.text-danger");
               errorMsg.html(error);
         },
         submitHandler: function(form) {
               $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
               form.submit();
         }
      });
   });

   </script>

   <!-- Alert Component -->
   <x-alert/>
   <!-- End Alert Component -->
</body>

</html>
