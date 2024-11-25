@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Enquiry</h4>
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
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Enquiry</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>

    <button type="button" class="hidden rounded bg-primary px-[20px] py-[8px] text-[14px] font-semibold  capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light">
        Open Modal
    </button>
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="px-[25px] py-3 text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex max-sm:h-auto border-b border-regular dark:border-box-dark-up">
         <h1 class="mb-0 inline-flex items-center py-1 overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
            List of Enquiry</h1>
      </div>
      <div class="p-[25px] pt-[15px]">
         <div >
            <table id="sections-table" class="min-w-full leading-normal table-auto display">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Name</th>
                     <th>Course</th>
                     <th>Email</th>
                     <th>Phone</th>
                     <th>Hear By</th>
                     <th>Study Mode</th>
                     <th>Contact Me</th>
                   
                     <th>Created At</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
         </div>

      </div>
   </div>
</section>
 <!-- Delete Modal (existing) -->

 <!-- View Modal (added) -->
 <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[768px]:mx-auto min-[768px]:mt-7 min-[768px]:max-w-[768px]">
        <div class="min-[768px]:shadow-[0_0.5rem_1rem_rgba(#000,0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
            <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                <!-- Modal title -->
                <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="viewModalLabel">
                    Enquiry Details
                </h5>
                <!-- Close button -->
                <button type="button" class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="relative flex-auto p-4" id="viewModalBody">

               <div class="divide-y">
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Name:</div>
                  <div id="enquiryName" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Course:</div>
                  <div id="enquiryCourse" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Email:</div>
                  <div id="enquiryEmail" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Phone:</div>
                  <div id="enquiryPhone" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Heard By:</div>
                  <div id="enquiryHearBy" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Study Mode:</div>
                  <div id="enquiryStudyMode" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Contact Me:</div>
                  <div id="enquiryContactMe" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Message:</div>
                  <div id="enquiryMessage" class="text-gray-600 dark:text-gray-400"></div>
               </div>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 py-3">
                  <div class="font-medium">Created At:</div>
                  <div id="enquiryCreatedAt" class="text-gray-600 dark:text-gray-400"></div>
                </div>
            </div>
            </div>
            <!-- Modal footer -->
            <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                <button type="button" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 focus:bg-primary-600 focus:outline-none focus:ring-0 active:bg-primary-700" data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#sections-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('enquiry') }}",
    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'course', name: 'course' },
        { data: 'email', name: 'email' },
        { data: 'phone', name: 'phone' },
        { data: 'hear_by', name: 'hear_by' },
        { data: 'study_mode', name: 'study_mode' },
        { data: 'contact_me', name: 'contact_me' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ]
});


            // Delete functionality
            $(document).on('click', '.deleteItem', function(){
                const delUrl = $(this).data('url');
                $('#confirmDelete').data('url', delUrl);
            });

            $(document).on('click', '#confirmDelete', function(){
                const delUrl = $(this).data('url');
                window.location.href = delUrl;
            });

            // View functionality
            $(document).on('click', '.viewItem', function(){
                const viewUrl = $(this).data('url');

                // Make an AJAX request to fetch the enquiry details
                $.ajax({
                    url: viewUrl,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response){
                        if(response.status === 'success'){
                            const data = response.data;
                            $('#enquiryName').text(data.name);
                            $('#enquiryCourse').text(data.course);
                            $('#enquiryEmail').text(data.email);
                            $('#enquiryPhone').text(data.phone);
                            $('#enquiryHearBy').text(data.hear_by);
                            $('#enquiryStudyMode').text(data.study_mode);
                            $('#enquiryContactMe').text(data.contact_me);
                            $('#enquiryMessage').text(data.message);
                            $('#enquiryCreatedAt').text(data.created_at);
                        } else {
                            alert('Failed to fetch enquiry details.');
                        }
                    },
                    error: function(){
                        alert('An error occurred while fetching enquiry details.');
                    }
                });
            });
        });
    </script>
@endpush
