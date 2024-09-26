<!doctype html>
<html lang="en" dir="ltr" class="scroll-smooth focus:scroll-auto">


<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Meta Tags -->
   <meta name="description" content="This is a page about home page.">
   <meta name="keywords" content="hexadash, web development, UI components">
   <meta name="author" content="dashboardmarket.com">
   <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/favicon.ico') }}">
   <!-- Title -->
   <title>Home page</title>
   @vite(['resources/css/app.css','resources/js/app.js'])
   <!-- inject:css-->
   <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}">
   <!-- endinject -->

   <!-- Fonts -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Jost&display=swap" rel="stylesheet">
   <!-- Icons -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">
   <!-- DataTables CSS -->
   <link href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.css" integrity="sha512-DIW4FkYTOxjCqRt7oS9BFO+nVOwDL4bzukDyDtMO7crjUZhwpyrWBFroq+IqRe6VnJkTpRAS6nhDvf0w+wHmxg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   @stack('style')
</head>

<body class=" bg-white [&.dark]:bg-main-dark font-Jost relative text-[15px] font-normal leading-[1.5] m-0 p-0">
   <!-- Aside -->

   <aside id="asideBar" class="asidebar dark:bg-box-dark fixed start-0 top-0 z-[1035] h-screen overflow-hidden  xl:!w-[280px] xl:[&.collapsed]:!w-[80px] [&.collapsed]:!w-[250px] xl:[&.TopCollapsed]:!w-[0px] [&.TopCollapsed]:!w-[250px] !transition-all !duration-[0.2s] ease-linear delay-[0s] !w-0 xl:[&.collapsed>.logo-wrapper]:w-[80px]">
      <div class="flex w-[280px] bg-gray-900   dark:border-box-dark-up logo-wrapper items-center h-[71px] dark:bg-box-dark-up max-xl:hidden">
         <a href="index.html" class="block text-center">
            <div class="logo-full">
               <img class="ps-[27px] w-auto h-[45px] object-contain dark:hidden" src="{{asset('assets/images/logos/logo_white.svg')}}" alt="Logo">
               <img class="ps-[27px] w-auto h-[45px] object-contain hidden dark:block" src="{{asset('assets/images/logos/logo_white.svg')}}" alt="Logo">
            </div>
            <div class="hidden logo-fold">
               <img class="p-[27px] max-w-[80px]" src="{{asset('assets/images/logos/logo_white.svg')}}" alt="Logo">
            </div>
         </a>
      </div>
      <nav id="navBar" class="navBar dark:bg-box-dark start-0 max-xl:top-[80px] z-[1035] h-full overflow-y-auto bg-gray-900 xl:!w-[280px] xl:[&.collapsed]:!w-[80px] [&.collapsed]:!w-[250px] xl:[&.TopCollapsed]:!w-[0px] [&.TopCollapsed]:!w-[250px] !transition-all !duration-[0.2s] ease-linear delay-[0s] !w-0 pb-[100px] scrollable-content xl:[&.collapsed]:ps-[7px] relative">
         <ul class="relative m-0 list-none px-[0.2rem] ">
          
            <li class="relative">
               <a href="{{route('admin-dashboard')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-apps"></i>
                  </span>
                  <span class="capitalize title">Dashboard</span>
                  
               </a>
            </li>
            

            <li class="relative">
               <a href="{{route('file-manager')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-file"></i>
                  </span>
                  <span class="capitalize title">File manager</span>
               </a>
            </li>
            <li class="relative sub-item-wrapper group  ">
               <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font- uppercase text-primary">ENGAGE</span>
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-apps"></i>
                  </span>
                  <span class="capitalize title">Manage Test</span>
                  <span class="arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="{{route('view-quizzes')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Quizzes</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-exams')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Exams                    </a>
                  </li>

                  <li class="relative">
                     <a href="{{route('quiz-types')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Quiz Types                    </a>
                  </li>

                  <li class="relative">
                     <a href="{{route('exam-types')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Exam Type                   </a>
                  </li>
               </ul>
            </li>
            <li class="relative sub-item-wrapper group  ">
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-folder"></i>
                  </span>
                  <span class="capitalize title">Manage Learning</span>
                  <span class="arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="{{route('view-practice-set')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Practise Test</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('configure-lessons')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">
                        Lessons</a>
                  </li>

                  <li class="relative">
                     <a href="{{route('configure-videos')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">
                        Videos</a>
                  </li>
               </ul>
            </li>

            <li class="relative sub-item-wrapper group ">
               <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font- uppercase text-primary">LIBRARY</span>
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-bag"></i>
                  </span>
                  <span class="title">Question Bank</span>
                  <span class="arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="{{route('view-question')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Questions</a>
                  </li>
                  <li class="relative">
                     <a href="product-details.html" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Import Questions</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-comprehension')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Comprehensions</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('question-types')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Question Types</a>
                  </li>
                 
               </ul>
            </li>

            <li class="relative">
             
               <a href="{{route('view-lesson')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-chat"></i>
                  </span>
                  <span class="capitalize title">Lesson Bank</span>
                 
               </a>
            </li>
           
         
            <li class="relative">
               <a href="{{route('view-video')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-calendar-alt"></i>
                  </span>
                  <span class="capitalize title">Video Bank</span>
               </a>
            </li>
            <li class="relative">
               <a href="{{route('view-plans')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-bill"></i>
                  </span>
                  <span class="capitalize title">Plans</span>
               </a>
            </li>
            <li class="relative">
               <a href="{{route('view-faq')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-question-circle"></i>
                  </span>
                  <span class="capitalize title">Faq</span>
               </a>
            </li>
            <li class="relative sub-item-wrapper group  ">
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-images"></i>
                  </span>
                  <span class="capitalize title">Blogs</span>
                  <span class=" arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="blog-two.html" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Add Blog</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-blog')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Blog List</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-blog-category')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Blog Category</a>
                  </li>
               </ul>
            </li>
            <li class="relative sub-item-wrapper group  ">
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-keyhole-circle"></i>
                  </span>
                  <span class="capitalize title">Manage Categories </span>
                  <span class=" arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="{{route('view-category')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Categories </a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-sub-category')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Sub Categories</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-tags')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Tags</a>
                  </li>
                 
               </ul>
            </li>

            <li class="relative sub-item-wrapper group  ">
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-layer-group"></i>
                  </span>
                  <span class="capitalize title">Manage Subjects</span>
                  <span class="arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="{{route('view-sections')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Section </a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-skills')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Skills</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('view-topics')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Topics</a>
                  </li>
               </ul>
            </li>

            <li class="relative sub-item-wrapper group  ">
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-user"></i>
                  </span>
                  <span class="capitalize title">Manage Users</span>
                  <span class="arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto">
                  <li class="relative">
                     <a href="{{route('users')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Users</a>
                  </li>

                  <li class="relative">
                     <a href="{{route('user-groups')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">User Groups</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('import-users')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Import Users</a>
                  </li>
               </ul>
            </li>

            <li class="relative">
               <a href="{{route('view-pages')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-question-circle"></i>
                  </span>
                  <span class="capitalize title">Pages</span>
               </a>
            </li>
            {{-- <li class="relative">
               <a href="{{route('privacy-policy')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-airplay"></i>
                  </span>
                  <span class="capitalize title">Privacy Policy</span>
               </a>
            </li> --}}
            <li class="relative sub-item-wrapper group">
               <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font- uppercase text-primary">ACCOUNT</span>
               <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary dark:active:text-title-dark active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&amp;.active]:text-current group-focus:text-current">
                     <i class="uil uil-layer-group"></i>
                  </span>
                  <span class="capitalize title">Settings</span>
                  <span class="arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                     <i class="uil uil-angle-down"></i>
                  </span>
               </a>
               <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-auto" >
                  <li class="relative">
                     <a href="{{route('general-settings')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">General</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('home-settings')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Homepage</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('email-settings')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Email SMTP</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('payment-settings')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Payments Details</a>
                  </li>
                  <li class="relative">
                     <a href="{{route('billing-tax-setting')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Billing & Tax</a>
                  </li>
                  
                  <li class="relative">
                     <a href="{{route('maintenance-setting')}}" class="rounded-e-[20px] hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary focus:text-primary dark:focus:text-title-dark dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] text-gray-200 outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up capitalize">Maintenance Mode</a>
                  </li>
               </ul>
            </li>
           
            <li class="relative">
               <a href="{{route('admin-logout')}}" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-gray-200 outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                  <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                     <i class="uil uil-sign-out-alt"></i>
                  </span>
                  <span class="capitalize title">Logout</span>
               </a>
            </li>
          
         </ul>
      </nav>
   </aside>

   <!-- End: Aside -->

   <!-- Wrapping Content -->
   <div class="relative flex flex-col flex-1 xl:ps-[280px] xl:[&.expanded]:ps-[80px] xl:[&.TopExpanded]:ps-[0px] !transition-all !duration-[0.2s] ease-linear delay-[0s] bg-normalBG dark:bg-main-dark" id="content">
      <!-- Header -->
      <header class="sticky top-0 flex w-full bg-white xl:z-[999] max-xl:z-[999] drop-shadow-1 dark:bg-box-dark dark:drop-shadow-none min-h-[70px]">
         <!-- Navigation -->
         <div class="flex flex-1 nav-wrap md:ps-[20px] ps-[30px] pe-[30px] max-xs:ps-[15px] max-xs:pe-[15px] bg-white dark:bg-box-dark">
            <!-- Header left menu -->

            <ul class="flex items-center mb-0 list-none nav-left ps-0 gap-x-[14px] gap-y-[9px]">
               <!-- Navigation Items -->
               <li class="xl:hidden xl:[&.flex]:flex" id="topMenu-logo">
                  <div class="flex md:w-[190px] xs:w-[170px] max-xs:w-[100px] max-md:pe-[30px] max-xs:pe-[15px] border-e border-[#edf2f9] dark:border-box-dark-up logo-wrapper items-center h-[71px] dark:bg-box-dark-up">
                     <a href="index.html" class="block text-center">
                        <div class="logo-full">
                           <img class="md:ps-[15px] w-auto h-[64px] object-contain dark:hidden" src="{{asset('assets/images/logos/logo-dark.png')}}" alt="Logo">
                           <img class="md:ps-[15px] w-auto h-[64px] object-contain hidden dark:block" src="{{asset('assets/images/logos/logo-white.png')}}" alt="Logo">
                        </div>
                     </a>
                  </div>
               </li>
               <li>
                  <a class="flex items-center justify-center sm:min-w-[40px] sm:w-[40px] sm:h-[40px] min-w-[34px] h-[34px] rounded-full bg-transparent hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark dark:hover:bg-box-dark-up group transition duration-200 ease-in-out text-[#525768] dark:text-subtitle-dark max-md:dark:hover:bg-box-dark-up sm:text-[20px] text-[19px] cursor-pointer xl:[&.hide]:hidden max-md:bg-normalBG max-md:dark:bg-box-dark-up max-md:dark:hover:text-subtitle-dark" id="slim-toggler">
                     <i class="uil uil-align-center-alt text-current [&.is-folded]:hidden flex items-center"></i>
                  </a>
               </li>
            
            </ul>

            <!-- Header Center menu -->

            <div class="relative ps-[30px] hexadash-top-menu hidden xl:[&.flex]:flex" id="topMenuWrapper">
               <ul class="flex flex-wrap items-center 2xl:gap-y-[15px] gap-x-[34px]">
                  <li class="has-subMenu">
                     <a href="#" class="active">Home Dashboard</a>
                     <ul class="subMenu">
                        <li class="active">
                           <a href="index.html">Demo 1</a>
                        </li>
                        <li class="">
                           <a href="demo-two.html">Demo 2</a>
                        </li>
                        <li class="">
                           <a href="demo-three.html">Demo 3</a>
                        </li>
                        <li class="">
                           <a href="demo-four.html">Demo 4</a>
                        </li>
                        <li class="">
                           {{-- <a href="demo-five.html">Demo 5</a> --}}
                        </li>
                        <li class="">
                           <a href="demo-six.html">Demo 6</a>
                        </li>
                        <li class="">
                           <a href="demo-seven.html">Demo 7</a>
                        </li>
                        <li class="">
                           <a href="demo-eight.html">Demo 8</a>
                        </li>
                        <li class="">
                           <a href="demo-nine.html">Demo 9</a>
                        </li>
                        <li class="">
                           <a href="demo-ten.html">Demo 10</a>
                        </li>
                     </ul>
                  </li>
                  <li class="has-subMenu">
                     <a href="#" class="">Apps</a>
                     <ul class="subMenu">
                        <li>
                           <ul>
                              <li class="has-subMenu-left">
                                 <a href="#" class="">
                                    <span class="nav-icon uil uil-envelope"></span>
                                    <span class="menu-text">Email</span>
                                 </a>
                                 <ul class="subMenu">
                                    <li>
                                       <a href="inbox.html" class="">Inbox</a>
                                    </li>
                                    <li>
                                       <a href="email.html" class="">Read
                                          Email</a>
                                    </li>
                                 </ul>
                              </li>
                              <li>
                                 <a href="chat.html" class="">
                                    <span class="nav-icon uil uil-message"></span>
                                    <span class="menu-text">Chat</span>
                                    <span class="text-white bg-success absolute -translate-y-2/4 text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center leading-none rounded-full end-[52px] top-2/4">3</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="calendar.html" class="">
                                    <span class="nav-icon uil uil-calender"></span>
                                    <span class="menu-text">Calendar</span>
                                 </a>
                              </li>
                              <li class="has-subMenu-left">
                                 <a href="#" class="">
                                    <span class="nav-icon uil uil-exchange"></span>
                                    <span class="menu-text">Import & export</span>
                                 </a>
                                 <ul class="subMenu">
                                    <li>
                                       <a class="" href="404-2.html">Import</a>
                                    </li>
                                    <li>
                                       <a class="" href="404-3.html">Export</a>
                                    </li>
                                 </ul>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li class="has-subMenu">
                     <a href="#" class="">Features</a>
                     <ul class="subMenu">
                        <li class="has-subMenu-left">
                           <a href="#" class="">
                              <span class="nav-icon uil uil-chart"></span>
                              <span class="menu-text">Charts</span>
                           </a>
                           <ul class="subMenu">
                              <li>
                                 <a class="" href="404-4.html">Apex Chart</a>
                              </li>
                           </ul>
                        </li>
                        <li class="has-subMenu-left">
                           <a href="#" class="">
                              <span class="nav-icon uil uil-document-layout-left"></span>
                              <span class="menu-text">Froms</span>
                           </a>
                           <ul class="subMenu">
                              <li>
                                 <a class="" href="form.html">Basics</a>
                              </li>
                              <li>
                                 <a class="" href="form-elements.html">Elements</a>
                              </li>
                              <li>
                                 <a class="" href="form-layouts.html">Layouts</a>
                              </li>
                              <li>
                                 <a class="" href="form-components.html">Components</a>
                              </li>
                           </ul>
                        </li>
                        <li class="has-subMenu-left">
                           <a href="#" class="">
                              <span class="nav-icon uil uil-square-shape"></span>
                              <span class="menu-text">Wizards</span>
                           </a>
                           <ul class="subMenu">
                              <li>
                                 <a href="create-account.html" class="">Wizard
                                    1</a>
                              </li>
                           </ul>
                        </li>
                        <li class="has-subMenu-left">
                           <a href="#" class="">
                              <span class="nav-icon uil uil-icons"></span>
                              <span class="menu-text">Icons</span>
                           </a>
                           <ul class="subMenu">
                              <li>
                                 <a href="404-5.html" class="">Unicon
                                    Icons</a>
                              </li>
                           </ul>
                        </li>
                        <li>
                           <a href="404-6.html" class="">
                              <span class="nav-icon uil uil-font"></span>
                              <span class="menu-text">Editors</span>
                           </a>
                        </li>
                        <li class="has-subMenu-left">
                           <a href="#" class="">
                              <span class="nav-icon uil uil-map"></span>
                              <span class="menu-text">Maps</span>
                           </a>
                           <ul class="subMenu">
                              <li>
                                 <a href="vector-map.html" class="">Vector
                                    Maps</a>
                                 <a href="google-map.html" class="">Google
                                    Maps</a>
                              </li>
                           </ul>
                        </li>
                        <li class="has-subMenu-left">
                           <a href="#" class="">
                              <span class="nav-icon uil uil-table"></span>
                              <span class="menu-text">Table</span>
                           </a>
                           <ul class="subMenu">
                              <li>
                                 <a class="" href="404-4.html">Basic table</a>
                              </li>
                              <li>
                                 <a class="" href="404-4.html">Data table</a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li class="mega-item has-subMenu">
                     <a href="#" class="">Pages</a>
                     <ul class="megaMenu-wrapper megaMenu-small">
                        <li>
                           <ul>
                              <li>
                                 <a href="change-log.html" class="">
                                    <span class="menu-text">Changelog</span>
                                    <span class="text-white bg-success absolute -translate-y-2/4 text-[10px] font-bold min-w-[18px] h-[18px] flex items-center justify-center leading-none rounded-[20px] end-[52px] top-2/4 px-[6px]">1.0.2</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="gallery.html" class="">
                                    <span class="menu-text">Gallery 1</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="pricing.html" class="">
                                    <span class="menu-text">Pricing</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="faq.html" class="">
                                    <span class="menu-text">FAQ's</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="blank.html" class="">
                                    <span class="menu-text">Blank Page</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="knowledge-base.html" class="">knowledge base</a>
                              </li>
                              <li>
                                 <a href="blog.html" class="">Blog</a>
                              </li>
                           </ul>
                        </li>
                        <li>
                           <ul>
                              <li>
                                 <a href="blog-two.html" class="">Blog two</a>
                              </li>
                              <li>
                                 <a href="blog-three.html" class="">Blog three</a>
                              </li>
                              <li>
                                 <a href="blog-details.html" class="">Blog details</a>
                              </li>
                              <li>
                                 <a href="" class="">
                                    <span class="menu-text">Terms & Conditions</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="" class="">
                                    <span class="menu-text">Maintenance</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="404.html" class="">
                                    <span class="menu-text">404</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="coming-soon.html" class="">
                                    <span class="menu-text">Coming Soon</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="log-in.html" class="">
                                    <span class="menu-text">Log In</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="sign-up.html" class="">
                                    <span class="menu-text">Sign Up</span>
                                 </a>
                              </li>
                              <li>
                                 <a href="reset.html" class="">
                                    <span class="menu-text">Forget password</span>
                                 </a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li class="mega-item has-subMenu">
                     <a href="#" class="">Components</a>
                     <ul class="megaMenu-wrapper megaMenu-wide">
                        <li>
                           <span class="mega-title">Components</span>
                           <ul>
                              <li>
                                 <a class="" href="alerts.html">Alert</a>
                              </li>
                              <li>
                                 <a class="" href="avatars.html">Avatar</a>
                              </li>
                              <li>
                                 <a class="" href="badges.html">Badge</a>
                              </li>
                           </ul>
                        </li>
                        <li>
                           <span class="mega-title">Components</span>
                           <ul>
                              <li>
                                 <a class="" href="buttons.html">Button</a>
                              </li>
                              <li>
                                 <a class="" href="cards.html">Cards</a>
                              </li>
                              <li>
                                 <a class="" href="404-7.html">Breadcrumb</a>
                              </li>
                           </ul>
                        </li>
                        <li>
                           <span class="mega-title">Components</span>
                           <ul>
                              <li>
                                 <a class="" href="carousel.html">Carousel</a>
                              </li>
                              <li>
                                 <a class="" href="checkbox.html">Checkbox</a>
                              </li>
                           </ul>
                        </li>
                        <li>
                           <span class="mega-title">Components</span>
                           <ul>
                              <li>
                                 <a class="" href="collapse.html">Collapse</a>
                              </li>
                              <li>
                                 <a class="" href="comments.html">typography</a>
                              </li>
                           </ul>
                        </li>
                     </ul>
                  </li>
               </ul>
            </div>

            <!-- Header right menu -->

            <div class="flex items-center ms-auto py-[15px] sm:gap-x-[25px] max-sm:gap-x-[15px] gap-y-[15px] relative">

               {{-- <div class="relative">
                  <button type="button" class="transition-all theme-dropdown-trigger text-[20px] text-light dark:text-subtitle-dark [&.close>.uil-search]:hidden [&.close>.uil-multiply]:block">
                     <i class="uil uil-search "></i>
                     <i class="hidden uil uil-multiply "></i>
                  </button>
                  <input type="search" placeholder="search here" id="searchInput" name="search" class="theme-dropdown absolute end-0 transition-[opacity,margin] duration [&.visible]:opacity-100 [&.visible]:block opacity-0 hidden min-w-[15rem] mt-2 dark:bg-box-dark-down p-1.5 h-[48px] px-[20px] dark:shadow-none border-1 border-regular dark:border-box-dark-up rounded-6 capitalize bg-white text-body dark:text-title-dark placeholder:text-body dark:placeholder:text-subtitle-dark text-ellipsis outline-none search-close-icon:appearance-none search-close-icon:w-[20px] search-close-icon:h-[23px] search-close-icon:bg-[url(images/svg/x.svg)] search-close-icon:cursor-pointer">
               </div>

               <button type="button" class="flex xl:hidden items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]" id="author-dropdown">
                  <i class="uil uil-ellipsis-v text-[18px]"></i>
               </button> --}}
               <ul id="right-ellipsis-trigger" class="xl:flex hidden items-center justify-end flex-auto mb-0 list-none ps-0 sm:gap-x-[25px] max-sm:gap-x-[15px] gap-y-[15px] max-xl:absolute max-xl:z-[1000] max-xl:m-0 max-xl:rounded-lg max-xl:border-none max-xl:bg-white max-xl:bg-clip-padding max-xl:text-left max-xl:shadow-lg max-xl:dark:bg-neutral-700 max-xl:[&.active]:flex max-xl:end-0 max-xl:px-[20px] max-sm:px-[15px] max-xl:py-[10px] max-xl:top-[70px]">
                  {{-- <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button id="message" data-te-dropdown-toggle-ref aria-expanded="false" type="button" class="flex items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark relative min-h-[40px] group">
                           <i class="uil uil-envelope"></i>
                           <span class="absolute flex w-1.5 h-1.5 translate-x-2/4 -translate-y-2/4 origin-[100%_0%] end-[3px] top-[8px] group-[[data-te-dropdown-show]]:hidden">
                              <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-success/20"></span>
                              <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-success"></span>
                           </span>
                        </button>
                        <div aria-labelledby="message" data-te-dropdown-menu-ref class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark [&[data-te-dropdown-show]]:block">
                           <div class="bg-white dark:bg-box-dark shadow-[0_2px_8px_rgba(0,0,0,.15)] dark:shadow-[0_5px_30px_rgba(1,4,19,.60)] rounded-4 px-[15px] py-[12px] md:min-w-[380px] sm:w-[300px] max-sm:w-[230px]">
                              <h1 class="flex items-center justify-center text-sm rounded-md bg-section dark:bg-box-dark-up h-[50px] p-[10px] text-dark dark:text-title-dark font-semibold">
                                 <span class="title-text">
                                    Messages
                                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs text-white rounded-full ms-[8px] bg-success dark:text-title-dark">
                                       3
                                    </span>
                                 </span>
                              </h1>
                              <ul class="p-0 max-h-[250px] relative overflow-x-hidden overflow-y-auto scrollbar">

                                 <li class="w-full">
                                    <div class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark transition-[0.3s] hover:text-primary hover:bg-white dark:hover:bg-white/[.06] hover:shadow-custom dark:shadow-none dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.20)] dark:rounded-4">
                                       <figure class="inline-flex w-full mb-0 align-top sm:gap-x-[16px] gap-y-[8px] max-sm:flex-wrap">
                                          <div class="text-light w-[40px] min-w-[40px] h-[40px] rounded-full relative">
                                             <span class="bg-current absolute content-[''] w-[12px] h-[12px] rounded-full border-2 border-white right-0 bottom-0" *ngif="!messages.isRead"></span>
                                             <img class="object-cover w-[40px] h-[40px] bg-light-extra rounded-full" src="{{asset('assets/images/messages/app-developer.png')}}">
                                          </div>
                                          <figcaption class="w-full -mt-1 text-start">
                                             <h1 class="flex items-center justify-between mb-0.5 text-sm text-current font-semibold">
                                                App developer
                                                <span class="text-xs font-normal text-text-light">2.5 hrs ago</span>
                                             </h1>
                                             <div class="flex items-center gap-[30px] text-start">
                                                <span class="ps-0 min-w-[216px] text-body dark:text-subtitle-dark">Lorem ipsum
                                                   dolor amet cosec...</span>
                                                <span class="inline-flex items-center justify-center w-4 h-4 text-white rounded-full bg-light dark:text-white/[.87 text-10">3</span>
                                             </div>
                                          </figcaption>
                                       </figure>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark transition-[0.3s] hover:text-primary hover:bg-white dark:hover:bg-white/[.06] hover:shadow-custom dark:shadow-none dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.20)] dark:rounded-4">
                                       <figure class="inline-flex w-full mb-0 align-top sm:gap-x-[16px] gap-y-[8px] max-sm:flex-wrap">
                                          <div class="text-success w-[40px] min-w-[40px] h-[40px] rounded-full relative">
                                             <span class="bg-current absolute content-[''] w-[12px] h-[12px] rounded-full border-2 border-white right-0 bottom-0" *ngif="!messages.isRead"></span>
                                             <img class="object-cover w-[40px] h-[40px] bg-light-extra rounded-full" src="{{asset('assets/images/messages/product.png')}}">
                                          </div>
                                          <figcaption class="w-full -mt-1 text-start">
                                             <h1 class="flex items-center justify-between mb-0.5 text-sm text-current font-semibold">
                                                Product manager
                                                <span class="text-xs font-normal text-text-success">2.5 hrs ago</span>
                                             </h1>
                                             <div class="flex items-center gap-[30px] text-start">
                                                <span class="ps-0 min-w-[216px] text-body dark:text-subtitle-dark">Lorem ipsum
                                                   dolor amet cosec...</span>
                                                <span class="inline-flex items-center justify-center w-4 h-4 text-white rounded-full bg-success dark:text-white/[.87 text-10">3</span>
                                             </div>
                                          </figcaption>
                                       </figure>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark transition-[0.3s] hover:text-primary hover:bg-white dark:hover:bg-white/[.06] hover:shadow-custom dark:shadow-none dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.20)] dark:rounded-4">
                                       <figure class="inline-flex w-full mb-0 align-top sm:gap-x-[16px] gap-y-[8px] max-sm:flex-wrap">
                                          <div class="text-success w-[40px] min-w-[40px] h-[40px] rounded-full relative">
                                             <span class="bg-current absolute content-[''] w-[12px] h-[12px] rounded-full border-2 border-white right-0 bottom-0" *ngif="!messages.isRead"></span>
                                             <img class="object-cover w-[40px] h-[40px] bg-light-extra rounded-full" src="{{asset('assets/images/messages/ui-ux-design.png')}}">
                                          </div>
                                          <figcaption class="w-full -mt-1 text-start">
                                             <h1 class="flex items-center justify-between mb-0.5 text-sm text-current font-semibold">
                                                UI UX Designing
                                                <span class="text-xs font-normal text-text-success">6 hrs ago</span>
                                             </h1>
                                             <div class="flex items-center gap-[30px] text-start">
                                                <span class="ps-0 min-w-[216px] text-body dark:text-subtitle-dark">Lorem ipsum
                                                   dolor amet cosec...</span>
                                                <span class="inline-flex items-center justify-center w-4 h-4 text-white rounded-full bg-success dark:text-white/[.87 text-10">3</span>
                                             </div>
                                          </figcaption>
                                       </figure>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark transition-[0.3s] hover:text-primary hover:bg-white dark:hover:bg-white/[.06] hover:shadow-custom dark:shadow-none dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.20)] dark:rounded-4">
                                       <figure class="inline-flex w-full mb-0 align-top sm:gap-x-[16px] gap-y-[8px] max-sm:flex-wrap">
                                          <div class="text-warning w-[40px] min-w-[40px] h-[40px] rounded-full relative">
                                             <span class="bg-current absolute content-[''] w-[12px] h-[12px] rounded-full border-2 border-white right-0 bottom-0" *ngif="!messages.isRead"></span>
                                             <img class="object-cover w-[40px] h-[40px] bg-light-extra rounded-full" src="{{asset('assets/images/messages/web-design-software-engineering.png')}}">
                                          </div>
                                          <figcaption class="w-full -mt-1 text-start">
                                             <h1 class="flex items-center justify-between mb-0.5 text-sm text-current font-semibold">
                                                Software Development
                                                <span class="text-xs font-normal text-text-warning">3 hrs ago</span>
                                             </h1>
                                             <div class="flex items-center gap-[30px] text-start">
                                                <span class="ps-0 min-w-[216px] text-body dark:text-subtitle-dark">Lorem ipsum
                                                   dolor amet cosec...</span>
                                                <span class="inline-flex items-center justify-center w-4 h-4 text-white rounded-full bg-warning dark:text-white/[.87 text-10">3</span>
                                             </div>
                                          </figcaption>
                                       </figure>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark transition-[0.3s] hover:text-primary hover:bg-white dark:hover:bg-white/[.06] hover:shadow-custom dark:shadow-none dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.20)] dark:rounded-4">
                                       <figure class="inline-flex w-full mb-0 align-top sm:gap-x-[16px] gap-y-[8px] max-sm:flex-wrap">
                                          <div class="text-success w-[40px] min-w-[40px] h-[40px] rounded-full relative">
                                             <span class="bg-current absolute content-[''] w-[12px] h-[12px] rounded-full border-2 border-white right-0 bottom-0" *ngif="!messages.isRead"></span>
                                             <img class="object-cover w-[40px] h-[40px] bg-light-extra rounded-full" src="{{asset('assets/images/messages/firecircle-icon-graphic-branding-graphic-design-large-white.png')}}">
                                          </div>
                                          <figcaption class="w-full -mt-1 text-start">
                                             <h1 class="flex items-center justify-between mb-0.5 text-sm text-current font-semibold">
                                                Designing Services
                                                <span class="text-xs font-normal text-text-success">1.30 hrs ago</span>
                                             </h1>
                                             <div class="flex items-center gap-[30px] text-start">
                                                <span class="ps-0 min-w-[216px] text-body dark:text-subtitle-dark">Lorem ipsum
                                                   dolor amet cosec...</span>
                                                <span class="inline-flex items-center justify-center w-4 h-4 text-white rounded-full bg-success dark:text-white/[.87 text-10">2</span>
                                             </div>
                                          </figcaption>
                                       </figure>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark transition-[0.3s] hover:text-primary hover:bg-white dark:hover:bg-white/[.06] hover:shadow-custom dark:shadow-none dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.20)] dark:rounded-4">
                                       <figure class="inline-flex w-full mb-0 align-top sm:gap-x-[16px] gap-y-[8px] max-sm:flex-wrap">
                                          <div class="text-warning w-[40px] min-w-[40px] h-[40px] rounded-full relative">
                                             <span class="bg-current absolute content-[''] w-[12px] h-[12px] rounded-full border-2 border-white right-0 bottom-0" *ngif="!messages.isRead"></span>
                                             <img class="object-cover w-[40px] h-[40px] bg-light-extra rounded-full" src="{{asset('assets/images/messages/app.png')}}">
                                          </div>
                                          <figcaption class="w-full -mt-1 text-start">
                                             <h1 class="flex items-center justify-between mb-0.5 text-sm text-current font-semibold">
                                                App Development
                                                <span class="text-xs font-normal text-text-warning">2 hrs ago</span>
                                             </h1>
                                             <div class="flex items-center gap-[30px] text-start">
                                                <span class="ps-0 min-w-[216px] text-body dark:text-subtitle-dark">Lorem ipsum
                                                   dolor amet cosec...</span>
                                                <span class="inline-flex items-center justify-center w-4 h-4 text-white rounded-full bg-warning dark:text-white/[.87 text-10">1</span>
                                             </div>
                                          </figcaption>
                                       </figure>
                                    </div>
                                 </li>
                              </ul>
                              <a class="flex items-center justify-center text-[13px] font-medium bg-normalBG dark:bg-box-dark-up h-[50px] text-light hover:text-primary dark:hover:text-subtitle-dark dark:text-title-dark mx-[-15px] mb-[-15px] rounded-b-6" href="#">
                                 See all messages
                              </a>
                           </div>
                        </div>
                     </div>

                  </li>
                  <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button type="button" id="notification" data-te-dropdown-toggle-ref aria-expanded="false" class="flex items-center hs-dropdown-toggle text-[23px] text-[#a0a0a0] dark:text-subtitle-dark relative min-h-[40px] group">
                           <i class="uil uil-bell"></i>
                           <span class="absolute flex w-1.5 h-1.5 translate-x-2/4 -translate-y-2/4 origin-[100%_0%] end-[3px] top-[8px] group-[[data-te-dropdown-show]]:hidden">
                              <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-warning/20"></span>
                              <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-warning"></span>
                           </span>
                        </button>
                        <div aria-labelledby="notification" data-te-dropdown-menu-ref class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down  [&[data-te-dropdown-show]]:block">
                           <div class="bg-white dark:bg-box-dark shadow-[0_2px_8px_rgba(0,0,0,.15)] dark:shadow-[0_5px_30px_rgba(1,4,19,.60)] rounded-4 px-[15px] py-[12px] md:min-w-[400px] sm:w-[300px] max-sm:w-[230px]">
                              <h1 class="flex items-center justify-center text-sm rounded-md bg-section dark:bg-box-dark-up h-[50px] p-[10px] text-dark dark:text-title-dark font-semibold">
                                 <span class="title-text me-2.5">Notifications<span class="inline-flex items-center justify-center w-5 h-5 text-xs text-white rounded-full bg-warning ms-3 dark:text-title-dark">3</span></span>
                              </h1>
                              <ul class="p-0 max-h-[250px] relative overflow-x-hidden overflow-y-auto scrollbar">
                                 <li class="w-full sm:mb-0 mb-[10px]">
                                    <button class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark hover:bg-white dark:hover:bg-box-dark-up hover:shadow-custom dark:shadow-none dark:rounded-4">
                                       <div class="flex items-start gap-x-[15px] gap-y-[5px] flex-wrap max-xs:flex-col">
                                          <div class="flex items-center justify-center rounded-full w-[30px] h-[30px] bg-warning/10 text-warning">
                                             <i class="text-current uil uil-heart text-[16px]"></i>
                                          </div>
                                          <div class="flex items-center justify-between flex-1">
                                             <div class="text-start">
                                                <h1 class="flex items-center justify-between mb-0.5 text-[#5a5f7d] dark:text-title-dark text-sm font-normal flex-wrap">
                                                   <span class="text-primary me-1.5 font-medium">Ibrahim Riaz</span>
                                                   sent you a message
                                                </h1>
                                                <p class="mb-0 text-xs text-body dark:text-subtitle-dark">
                                                   3 hours ago
                                                </p>
                                             </div>
                                             <div><span class="inline-flex items-center justify-center bg-warning w-1.5 h-1.5 ms-3 rounded-full"></span>
                                             </div>
                                          </div>
                                       </div>
                                    </button>
                                 </li>
                                 <li class="w-full sm:mb-0 mb-[10px]">
                                    <button class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark hover:bg-white dark:hover:bg-box-dark-up hover:shadow-custom dark:shadow-none dark:rounded-4">
                                       <div class="flex items-start gap-x-[15px] gap-y-[5px] flex-wrap max-xs:flex-col">
                                          <div class="flex items-center justify-center rounded-full w-[30px] h-[30px] bg-primary/10 text-primary">
                                             <i class="text-current uil uil-inbox text-[16px]"></i>
                                          </div>
                                          <div class="flex items-center justify-between flex-1">
                                             <div class="text-start">
                                                <h1 class="flex items-center justify-between mb-0.5 text-[#5a5f7d] dark:text-title-dark text-sm font-normal flex-wrap">
                                                   <span class="text-primary me-1.5 font-medium">Shamim Ahmed</span>
                                                   sent you a message
                                                </h1>
                                                <p class="mb-0 text-xs text-body dark:text-subtitle-dark">
                                                   3 hours ago
                                                </p>
                                             </div>
                                             <div><span class="inline-flex items-center justify-center bg-primary w-1.5 h-1.5 ms-3 rounded-full"></span>
                                             </div>
                                          </div>
                                       </div>
                                    </button>
                                 </li>
                                 <li class="w-full sm:mb-0 mb-[10px]">
                                    <button class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark hover:bg-white dark:hover:bg-box-dark-up hover:shadow-custom dark:shadow-none dark:rounded-4">
                                       <div class="flex items-start gap-x-[15px] gap-y-[5px] flex-wrap max-xs:flex-col">
                                          <div class="flex items-center justify-center rounded-full w-[30px] h-[30px] bg-secondary/10 text-secondary">
                                             <i class="text-current uil uil-upload text-[16px]"></i>
                                          </div>
                                          <div class="flex items-center justify-between flex-1">
                                             <div class="text-start">
                                                <h1 class="flex items-center justify-between mb-0.5 text-[#5a5f7d] dark:text-title-dark text-sm font-normal flex-wrap">
                                                   <span class="text-primary me-1.5 font-medium">Tanjim Ahmed</span>
                                                   sent you a message
                                                </h1>
                                                <p class="mb-0 text-xs text-body dark:text-subtitle-dark">
                                                   2 hours ago
                                                </p>
                                             </div>
                                             <div><span class="inline-flex items-center justify-center bg-secondary w-1.5 h-1.5 ms-3 rounded-full"></span>
                                             </div>
                                          </div>
                                       </div>
                                    </button>
                                 </li>
                                 <li class="w-full sm:mb-0 mb-[10px]">
                                    <button class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark hover:bg-white dark:hover:bg-box-dark-up hover:shadow-custom dark:shadow-none dark:rounded-4">
                                       <div class="flex items-start gap-x-[15px] gap-y-[5px] flex-wrap max-xs:flex-col">
                                          <div class="flex items-center justify-center rounded-full w-[30px] h-[30px] bg-info/10 text-info">
                                             <i class="text-current uil uil-signout text-[16px]"></i>
                                          </div>
                                          <div class="flex items-center justify-between flex-1">
                                             <div class="text-start">
                                                <h1 class="flex items-center justify-between mb-0.5 text-[#5a5f7d] dark:text-title-dark text-sm font-normal flex-wrap">
                                                   <span class="text-primary me-1.5 font-medium">Masud Rana</span>
                                                   sent you a message
                                                </h1>
                                                <p class="mb-0 text-xs text-body dark:text-subtitle-dark">
                                                   9 hours ago
                                                </p>
                                             </div>
                                             <div><span class="inline-flex items-center justify-center bg-info w-1.5 h-1.5 ms-3 rounded-full"></span>
                                             </div>
                                          </div>
                                       </div>
                                    </button>
                                 </li>
                                 <li class="w-full sm:mb-0 mb-[10px]">
                                    <button class="group relative block w-full px-3 sm:py-3.5 max-sm:py-1.5 text-body dark:text-subtitle-dark hover:bg-white dark:hover:bg-box-dark-up hover:shadow-custom dark:shadow-none dark:rounded-4">
                                       <div class="flex items-start gap-x-[15px] gap-y-[5px] flex-wrap max-xs:flex-col">
                                          <div class="flex items-center justify-center rounded-full w-[30px] h-[30px] bg-danger/10 text-danger">
                                             <i class="text-current uil uil-at text-[16px]"></i>
                                          </div>
                                          <div class="flex items-center justify-between flex-1">
                                             <div class="text-start">
                                                <h1 class="flex items-center justify-between mb-0.5 text-[#5a5f7d] dark:text-title-dark text-sm font-normal flex-wrap">
                                                   <span class="text-primary me-1.5 font-medium">Abdur Rahim</span>
                                                   sent you a message
                                                </h1>
                                                <p class="mb-0 text-xs text-body dark:text-subtitle-dark">
                                                   1 min ago
                                                </p>
                                             </div>
                                             <div><span class="inline-flex items-center justify-center bg-danger w-1.5 h-1.5 ms-3 rounded-full"></span>
                                             </div>
                                          </div>
                                       </div>
                                    </button>
                                 </li>
                              </ul>
                              <a class="flex items-center justify-center text-[13px] font-medium bg-normalBG dark:bg-box-dark-up h-[50px] text-light hover:text-primary dark:hover:text-subtitle-dark dark:text-title-dark mx-[-15px] mb-[-15px] rounded-b-6" href="#">See all incoming activity</a>
                           </div>
                        </div>
                     </div>

                  </li>
                  <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button type="button" id="settings" data-te-dropdown-toggle-ref aria-expanded="false" class="flex items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]">
                           <i class="uil uil-setting"></i>
                        </button>
                        <div class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down  [&[data-te-dropdown-show]]:block" aria-labelledby="settings" data-te-dropdown-menu-ref>
                           <div class="lg:w-[700px] md:w-[300px] max-md:w-[230px] px-[15px] py-[15px] bg-white dark:bg-box-dark shadow-[0_2px_8px_rgba(0,0,0,.15)] dark:shadow-[0_5px_30px_rgba(1,4,19,.60)] rounded-4">
                              <ul class="flex flex-wrap items-center lg:[&>li]:flex-[50%] max-lg:[&>li]:flex-[100%] max-h-[251px] relative overflow-x-hidden overflow-y-auto scrollbar scrollbar">

                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/audio.png')}}" alt="audio">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             Diagram Maker</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">Plan user flows & test scenarios </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/payment.png')}}" alt="payment">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             payments</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">We handle billions of dollars </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/feature.png')}}" alt="feature">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             All Features</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">Introducing Increment subscriptions </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/theme.png')}}" alt="theme">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             Themes</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">Third party themes that are compatible
                                          </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/payment.png')}}" alt="payment">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             payments</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">We handle billions of dollars </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/design.png')}}" alt="design">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             Design Mockups</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">Share planning visuals with clients </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/file.png')}}" alt="file">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             Content Planner</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">Centralize content gathering and editing
                                          </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                                 <li class="w-full">
                                    <figure class="flex items-start px-4 py-3 mb-0 hover:shadow-action dark:hover:shadow-[0_5px_30px_rgba(1,4,19,.60)]">
                                       <img class="h-fit me-4" src="{{asset('assets/images/settings/audio.png')}}" alt="audio">
                                       <figcaption>
                                          <h1 class="mb-0.5 -mt-1 text-[15px] font-medium capitalize text-dark dark:text-title-dark text-start">
                                             Diagram Maker</h1>
                                          <p class="mb-0 text-body dark:text-subtitle-dark text-start">Plan user flows & test scenarios </p>
                                       </figcaption>
                                    </figure>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>

                  </li>
                  <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button id="flags" data-te-dropdown-toggle-ref aria-expanded="false" type="button" class="flex items-center">
                           <img class="min-w-[20px] min-h-[20px]" src="{{asset('assets/images/flags/english.png')}}" alt="flags">
                        </button>
                        <div class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down  [&[data-te-dropdown-show]]:block" aria-labelledby="flags" data-te-dropdown-menu-ref>
                           <ul class="block bg-white dark:bg-box-dark-down w-[100px] shadow-[0_2px_8px_rgba(0,0,0,.15)] dark:shadow-[0_5px_30px_rgba(1,4,19,.60)] rounded-4 overflow-hidden">
                              <li class="bg-white dark:bg-box-dark-down hover:bg-primary/10 dark:hover:bg-primary/10">
                                 <button class="flex items-center px-3 py-1.5 text-sm text-dark dark:text-subtitle-dark">
                                    <img class="w-3.5 h-3.5 me-2" src="{{asset('assets/images/flags/english.png')}}" alt="english">
                                    <span>English</span>
                                 </button>
                              </li>
                              <li class="bg-white dark:bg-box-dark-down hover:bg-primary/10 dark:hover:bg-primary/10">
                                 <button class="flex items-center px-3 py-1.5 text-sm text-dark dark:text-subtitle-dark">
                                    <img class="w-3.5 h-3.5 me-2" src="{{asset('assets/images/flags/spanish.png')}}" alt="spanish">
                                    <span>Spanish</span>
                                 </button>
                              </li>
                              <li class="bg-white dark:bg-box-dark-down hover:bg-primary/10 dark:hover:bg-primary/10">
                                 <button class="flex items-center px-3 py-1.5 text-sm text-dark dark:text-subtitle-dark">
                                    <img class="w-3.5 h-3.5 me-2" src="{{asset('assets/images/flags/arabic.png')}}" alt="arabic">
                                    <span>Arabic</span>
                                 </button>
                              </li>
                           </ul>
                        </div>
                     </div>

                  </li> --}}
                  <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button type="button" id="author-dropdown" data-te-dropdown-toggle-ref aria-expanded="false" class="flex items-center me-1.5 text-body dark:text-subtitle-dark text-sm font-medium capitalize rounded-full md:me-0 group whitespace-nowrap">
                           <span class="sr-only">Open user menu</span>
                           <img class="min-w-[32px] w-8 h-8 rounded-full xl:me-2" src="{{asset('assets/images/avatars/thumbs.png')}}" alt="user photo">
                           <span class="hidden xl:block">Admin</span>
                           <i class="uil uil-angle-down text-light dark:text-subtitle-dark text-[18px] hidden xl:block"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down  [&[data-te-dropdown-show]]:block" aria-labelledby="author-dropdown" data-te-dropdown-menu-ref>
                           <div class="min-w-[310px] max-sm:min-w-full pt-4 px-[15px] py-[12px] bg-white dark:bg-box-dark shadow-[0_2px_8px_rgba(0,0,0,.15)] dark:shadow-[0_5px_30px_rgba(1,4,19,.60)] rounded-4">
                              <figure class="flex items-center text-sm rounded-[8px] bg-section dark:bg-box-dark-up py-[20px] px-[25px] mb-[12px] gap-[15px]">
                                 <img class="w-8 h-8 rounded-full bg-regular" src="{{asset('assets/images/avatars/thumbs.png')}}" alt="user">
                                 <figcaption>
                                    <div class="text-dark dark:text-title-dark mb-0.5 text-sm">Admin</div>
                                    <div class="mb-0 text-xs text-body dark:text-subtitle-dark">Admin</div>
                                 </figcaption>
                              </figure>
                              <ul class="m-0 pb-[10px] overflow-x-hidden overflow-y-auto scrollbar bg-transparent max-h-[230px]">
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-user"></i>
                                          Profile
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-users-alt"></i>
                                          Payment
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-key-skeleton"></i>
                                          Billing
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-bell"></i>
                                          Maintanace Mode
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-setting"></i>
                                          Site Settings
                                       </button>
                                    </div>
                                 </li>
                              </ul>
                              <a class="flex items-center justify-center text-sm font-medium bg-normalBG dark:bg-box-dark-up h-[50px] text-light hover:text-primary dark:hover:text-subtitle-dark dark:text-title-dark mx-[-15px] mb-[-15px] rounded-b-6 gap-[6px]" href="log-in.html">
                                 <i class="uil uil-sign-out-alt"></i> Sign Out</a>
                           </div>
                        </div>
                     </div>


                  </li>
               </ul>
            </div>

         </div>
         <!-- End: Navigation -->
      </header>
      <!-- End: Header -->

      <!-- Main Content -->
      <main class="bg-normalBG dark:bg-main-dark">


         @yield('content')

      </main>
      <!-- End: Main Content -->


   
   </div>
   <!-- End: Wrapping Content -->

  

   <!-- Preloader -->

   <div class="preloader fixed w-full h-full z-[9999] flex items-center justify-center top-0 bg-white dark:bg-black">
      <div class="animate-spin inline-block w-[50px] h-[50px] border-[3px] border-current border-t-transparent text-primary rounded-full" role="status" aria-label="loading">
         <span class="sr-only">Loading...</span>
      </div>
   </div>

   <!-- End: Preloader -->

   <!-- inject:js-->
   <script src="{{ asset('assets/js/plugins.min.js') }}"></script>
   <script src="{{ asset('assets/js/script.min.js') }}"></script>
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <!-- DataTables JS -->
   <script src="https://cdn.datatables.net/2.1.6/js/dataTables.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js" integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   {{-- <script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/index.min.js"></script> --}}
   <!-- endinject-->
   @stack('scripts')
   <!-- Alert Component -->
   <x-alert/>
   <!-- End Alert Component -->
</body>


</html>