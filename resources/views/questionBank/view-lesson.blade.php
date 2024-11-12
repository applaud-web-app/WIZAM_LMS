@extends('layouts.master')
@section('title', 'Lesson Bank')
@section('content')
<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">
   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Lesson Bank</h4>
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
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Lesson Bank</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>
   <button type="button" class="hidden inline-block rounded bg-primary px-[20px] py-[8px] text-[14px] font-semibold  capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light">
      Open Modal
  </button>
   <!-- Lesson Table Section -->
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
         <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
            Lessons</h1>
            <a href="{{route('add-lesson')}}" class="flex items-center px-[14px] text-sm text-white rounded-md bg-primary border-primary h-10 gap-[6px] transition-[0.3s]">
               <i class="uil uil-plus"></i>
               <span class="m-0">Add Lesson</span>
            </a>
      </div>
      <div class="p-[25px] pt-[15px]">
         <div>
            <table class="w-full border-collapse table-auto" id="lesson-table">
               <thead>
                  <tr>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">#</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Title</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Section</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Skill</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Topic</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Status</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Actions</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</section>

 <!-- Delete Modal -->
 <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
       <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
          <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
             <!--Modal title-->
             <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="exampleModalLabel">
                Do you Want to delete these items?
             </h5>
             <!--Close button-->
             <button type="button" class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
             </button>
          </div>
          <!--Modal body-->
          <div class="relative flex-auto p-4" data-te-modal-body-ref>
             <p class="mb-3 text-breadcrumbs dark:text-subtitle-dark">This action cannot be undone. Click "Confirm" to proceed or "Cancel" to abort.</p>
          </div>
          <!--Modal footer-->
          <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
            <button type="button" class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
               Cancel
            </button>
             <button type="button" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-ripple-init data-te-ripple-color="light" data-url="" id="confirmDelete">
                Confirm
             </button>
          </div>
       </div>
    </div>
 </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTables initialization
            $('#lesson-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('view-lesson') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'title', name: 'title' },
                    { data: 'section', name: 'section' },
                    { data: 'skill', name: 'skill' },
                    { data: 'topic', name: 'topic' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    </script>
    <script>
           $(document).ready(function(){
                // When delete item is clicked, store the URL in the confirm button
                $(document).on('click', '.deleteItem', function(){
                    const delUrl = $(this).data('url');
                    console.log(delUrl);
                    $('#confirmDelete').data('url', delUrl); // Use data method to set the URL
                });
        
                // When confirm delete is clicked, redirect to the URL
                $(document).on('click', '#confirmDelete', function(){
                    const delUrl = $(this).data('url'); // Use data method to get the URL
                    window.location.href = delUrl;
                });
            });
    </script>
@endpush