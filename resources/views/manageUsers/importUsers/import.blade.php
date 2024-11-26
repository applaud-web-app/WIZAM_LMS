@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
   
<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-start items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Import Users</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="{{route('admin-dashboard')}}">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Child (Current Page) -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Import Users</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>

   <!-- Instructions Section -->
   <div class="bg-white dark:bg-box-dark p-[25px] rounded-[10px] mb-[30px]">
      <h5 class="text-[18px] font-semibold text-dark dark:text-title-dark mb-[10px]">Instructions</h5>
      <ul class="list-disc list-inside text-body dark:text-subtitle-dark space-y-2">
         <li>Ensure the file is in CSV format before uploading.</li>
         <li>The file should contain the following columns: <strong>First Name, Last Name, Email, Role</strong>.</li>
         <li>If a user already exists, their information will be updated; otherwise, a new user will be created.</li>
         <li>Make sure there are no duplicate email addresses in the file.</li>
         <li>Check that the <strong>Role</strong> column contains valid values such as <strong>Admin, Instructor, Student</strong>.</li>
         <li>After uploading, the system will process the file, and you'll be notified of any errors.</li>
         <li>You can download a <a href="#" class="text-primary hover:underline">sample file</a> to ensure your format is correct.</li>
      </ul>
   </div>

   <!-- File Import Section -->
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="p-[25px]">
         <form id="import-form" action="{{ route('import-users') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-center w-full">
               <label id="file-label" for="import-file" class="flex flex-col items-center justify-center w-full sm:min-h-[280px] bg-white dark:bg-box-dark mb-[30px] mx-auto p-2.5 rounded-[10px] border-2 border-dashed border-[#c6d0dc] dark:border-box-dark-up hover:border-primary dark:hover:border-primary cursor-pointer transition-all duration-300 ease-linear">
                  <div class="flex flex-col items-center justify-center pt-5 pb-6">
                     <div class="text-[70px] text-light dark:text-subtitle-dark">
                        <i class="uil uil-export"></i>
                     </div>
                     <p class="text-[20px] font-medium text-dark dark:text-title-dark">Choose File</p>
                     <p id="file-name" class="text-dark dark:text-title-dark mt-2"></p> 
                  </div>
                  <input id="import-file" type="file" name="file" class="hidden" accept=".csv">
               </label>
            </div>
            <button id="import-button" type="submit" class="text-center bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark transition duration-300 ease-in-out" >
               Import
            </button>
         </form>
      </div>
   </div>
</section>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle the file input click
    $('#file-label').on('click', function() {
        $('#import-file').click();
    });


    // Disable button and change text on form submission
    $('#import-form').on('submit', function() {
        $('#import-button').text('Processing...').prop('disabled', true);
    });
});


    // Update file name on file selection
    $(document).ready(function() {
        // Handle the file input change event
        $('#import-file').on('change', function(e) {
            console.log('File input changed'); // Check if event is triggered
            const file = $(this).get(0).files[0];
            console.log('Selected file:', file); // Check the file object

            if (file) {
                $('#file-name').text(file.name);
            } else {
                $('#file-name').text('');
            }
        });
    });
</script>
@endsection

@endsection
