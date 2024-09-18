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
   <title>Login </title>
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

</head>

<body class="scrollbar">
   <!-- Main Content -->
   <main class="relative bg-top bg-no-repeat  dark:bg-[#1e2836] bg-contain bg-cyan-50">


      <!-- Main content container with responsive design -->
      <div class="h-[calc(var(--vh,1vh)_*_100)] w-full flex">

         <!-- Login form container -->
         <div class="flex flex-col justify-center w-full max-w-[520px] px-[30px] mx-auto my-5">
            <a href="index.html" class="text-center">
               <!-- Logo for the light theme -->
               <img src="{{ asset('assets/images/logos/logo-dark.png')}}" alt="image" class="inline w-auto h-[64px] dark:hidden">
               <!-- Logo for the dark theme -->
               <img src="{{ asset('assets/images/logos/logo-white.png')}}" alt="image" class="hidden w-auto h-[64px] dark:inline">
            </a>

            <!-- Login form background -->
            <div class="rounded-6 mt-[25px] shadow-sm dark:shadow-xl bg-white dark:bg-[#111726]">
               <div class="p-[25px] text-center border-b border-regular dark:border-white/[.05] top">
                  <!-- Heading for the login form -->
                  <h2 class="text-[24px] font-semibold leading-[1] mb-0 text-dark dark:text-title-dark">Sign in</h2>
               </div>

               <!-- Login form inputs and elements -->
               <div class="py-[30px] px-[40px]">
                  <form id="admin-form">

                     <!-- Email Address input -->
                     <div class="mb-6">
                        <label for="email-username" class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">Email
                           Address</label>
                        <input type="text" id="email-username" class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary" placeholder="username or name@example.com" autocomplete="off" value="name@example.com" required>
                     </div>

                     <!-- Password input -->
                     <div class="mb-6">
                        <label for="password" class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">
                           Password</label>
                        <div class="relative w-full">
                           <div class="absolute inset-y-0 end-0 flex items-center px-[15px]">
                              <input class="hidden js-password-toggle" id="toggle" type="checkbox">
                              <label class=" rounded cursor-pointer text-light text-[15px] js-password-label dark:text-subtitle-dark" for="toggle"><i class="uil uil-eye-slash"></i></label>
                           </div>
                           <input class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary js-password" id="password" type="password" value="123456" autocomplete="off" placeholder="Password">
                        </div>
                     </div>

                     <!-- Remember me and forgot password options -->
                     <div class="flex items-center sm:justify-between justify-center max-sm:flex-wrap capitalize mb-[19px] mt-[23px] gap-[15px]">
                        <div class="flex">
                           <div class="flex items-center h-5">
                              <input id="remember" type="checkbox" value="" class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-box-dark-up dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" required>
                           </div>
                           <label for="remember" class="text-sm text-gray-500 ms-1 dark:text-gray-400">Keep me logged in</label>
                        </div>
                        <a class="text-13 text-primary hover:text-dark dark:hover:text-title-dark" href="reset.html">Forgot
                           password?</a>
                     </div>

                     <!-- Submit button for the login form -->
                     <button type="submit" class="inline-flex items-center justify-center w-full h-[48px] text-14 rounded-6 font-medium bg-primary text-white cursor-pointer hover:bg-primary-hbr border-primary transition duration-300" value="submit">Submit</button>
                  </form>

                
              
               </div>

               <!-- Footer with signup link -->
               <div class="text-center p-[25px] rounded-b-6 bg-white-100 border-t dark:bg-gray-600">
                  <p class="text-[14px] font-medium text-body dark:text-title-dark inline-flex items-center gap-[6px] mb-0">
                     Don't have an account? <a class="transition duration-300 text-primary hover:text-dark dark:text-dark dark:hover:text-subtitle-dark" href="sign-up.html">Sign up</a>
                  </p>
               </div>
            </div>
         </div>
      </div>

      <!-- End of the content block -->

   </main>




   <!-- inject:js-->
   <script src="js/plugins.min.js"></script>
   <script src="js/script.min.js"></script>
   <!-- endinject-->
</body>


</html>