<!doctype html>
<html lang="en" dir="ltr">


<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Meta Tags -->
   <meta name="description" content="This is a page about log-in.">
   <meta name="keywords" content="buttons, web development, UI components">
   <meta name="author" content="dashboardmarket.com">
   <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/favicon.ico') }}">
   <!-- Title -->
   <title>Wizam | Forget Password</title>
   @vite(['resources/css/app.css','resources/js/app.js'])
   <!-- inject:css-->
   <link rel="stylesheet" href="{{ asset('assets/css/plugin.min.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
   <!-- endinject -->

   <!-- Fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
   <!-- Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.css" integrity="sha512-DIW4FkYTOxjCqRt7oS9BFO+nVOwDL4bzukDyDtMO7crjUZhwpyrWBFroq+IqRe6VnJkTpRAS6nhDvf0w+wHmxg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="scrollbar">
   <!-- Main Content -->
   <main class="relative bg-top bg-no-repeat  dark:bg-[#1e2836] bg-contain bg-cyan-50">


      <!-- Main content container with responsive design -->
      <div class="h-[calc(var(--vh,1vh)_*_100)] w-full flex">

         <!-- Login form container -->
         <div class="flex flex-col justify-center w-full max-w-[520px] px-[30px] mx-auto my-[150px]">
            <a href="{{route('admin-dashboard')}}" class="text-center">
               <!-- Logo for the light theme -->
               <img src="{{ asset('assets/images/logos/logo-dark.png')}}" alt="image" class="inline w-auto h-[64px] dark:hidden">
               <!-- Logo for the dark theme -->
               <img src="{{ asset('assets/images/logos/logo-white.png')}}" alt="image" class="hidden w-auto h-[64px] dark:inline">
            </a>

             <!-- Login form background -->
             <div class="rounded-6 mt-[25px] shadow-sm dark:shadow-regular bg-white dark:bg-[#111726]">
               <div class="p-[25px] text-center border-b border-regular dark:border-white/[.05] top">
                  <!-- Heading for the login form -->
                  <h2 class="text-18 font-semibold leading-[1] mb-0 text-dark dark:text-title-dark">Forget Your Password</h2>
               </div>

               <!-- Login form inputs and elements -->
               <div class="py-[30px] px-[40px]">
                  <p class="text-[16px] text-light dark:text-subtitle-dark mb-[15px]">Enter the email address you used when you joined and we’ll send you instructions to reset your password.</p>
                  <form id="forgotPassword">
                     <!-- Email Address input -->
                     <div class="mb-6">
                        <label for="email" class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">Email
                           Address <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary" placeholder="example@example.com" required>
                     </div>
                     <!-- Submit button for the login form -->
                     <button type="submit" class="inline-flex w-full items-center justify-center h-[48px] text-14 rounded-6 font-medium bg-primary text-white cursor-pointer hover:bg-primary-hbr border-primary transition duration-300 px-[50px]" value="submit">Send Reset Password</button>
                  </form>

               </div>
               <!-- End of the login form inputs and elements -->
               <div class="text-center p-[25px] rounded-b-6 bg-white-100 border-t dark:bg-gray-600">
                  <p class="text-[14px] font-medium text-body dark:text-title-dark inline-flex items-center gap-[6px] mb-0">
                     Back to Sign In? <a class="transition duration-300 text-primary hover:text-dark dark:text-dark dark:hover:text-subtitle-dark" href="{{route('admin-login')}}">Sign In</a>
                  </p>
               </div>
            </div>
         </div>
      </div>

      <!-- End of the content block -->

   </main>

   <!-- inject:js-->
   <script src="{{asset('/assets/js/plugins.min.js')}}"></script>
   <script src="{{asset('/assets/js/script.min.js')}}"></script>
   <!-- endinject-->

   <!-- Enternal CDN's-->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <!-- End Enternal CDN's-->

   <!-- Form Validation -->
   <script>
      $(document).ready(function() {
         $("#forgotPassword").validate({
            rules: {
               email: "required",
            },
            messages: {
               email: "Please enter your email",
            },
            errorPlacement: function(error, element) {
               // Find or create the span for error messages
               var errorSpan = element.next('span.text-danger');
               if (errorSpan.length === 0) {
                  // Create a new span element if it does not exist
                  errorSpan = $('<small class="text-danger"></small>').insertAfter(element);
               }
               // Set the error message
               errorSpan.html(error);
            },
            success: function(label, element) {
               // Optionally handle the success scenario, e.g., clear the error message
               $(element).next('span.text-danger').text('');
            },
            submitHandler: function(form, e) {
               e.preventDefault(); // Prevent default form submission

               // Update button text to "Processing..." and disable the button
               $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);

               $.ajax({
                  url: "{{route('verify-email')}}", // Route to your Laravel controller
                  method: "POST", // HTTP method
                  data: {
                     _token: "{{ csrf_token() }}", // CSRF token for security
                     email: $('#email').val() // Include form data (e.g., email)
                  },
                  success: function(response) {
                     // Handle success response
                     if (response.status === 'success') {
                        iziToast.success({
                           title: 'Success',
                           position:"topRight",
                           message: response.message,
                        });
                        $(form).find('button[type="submit"]').html('Check Your Inbox').addClass('bg-success');   
                     } else {
                        iziToast.error({
                           title: 'Error',
                           position:"topRight",
                           message: response.message,
                        });
                        $(form).find('button[type="submit"]').html('Send Reset Password').removeAttr('disabled', true);   
                     }
                  },
                  error: function(xhr) {
                     // Handle error response
                     iziToast.error({
                        title: 'Error',
                        position:"topRight",
                        message: 'An error occurred: ' + xhr.status + ' ' + xhr.statusText,
                     });
                     $(form).find('button[type="submit"]').html('Send Reset Password').removeAttr('disabled', true);   
                  }
               });

               // Now submit the form manually
               // form.submit(); // This submits the form naturally
            },
         });
      });
   </script>
   <!-- End Form Validation -->

   <!-- Alert Component -->
   <x-alert/>
   <!-- End Alert Component -->
</body>


</html>