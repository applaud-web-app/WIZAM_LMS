@extends('layouts.master')

@section('title', 'Add Practice')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <div class="flex justify-between items-center mb-[30px]">
        <h2><b>{{$practice->title}} - Detailed Report</b></h2>
        <a href="{{route('overall-practice-set-report',[$practice->id])}}" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Overall Report</a>
    </div>
    <div class="bg-white m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        <div class="p-[25px] pt-[15px]">
         <div class="table-responsive" >
               <table id="category-table" class="min-w-full leading-normal table-auto display">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Task Taker</th>
                        <th>Completed On</th>
                        <th>Percentage</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
</section>

<button type="button" class="hidden inline-block rounded bg-primary px-[20px] py-[8px] text-[14px] font-semibold  capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-toggle="modal" data-te-target="#practicepleModal" data-te-ripple-init data-te-ripple-color="light">
    Open Modal
</button>
 <!-- Delete Modal -->
 <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="practicepleModal" tabindex="-1" aria-labelledby="practicepleModalLabel" aria-hidden="true">
    <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
       <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
          <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
             <!--Modal title-->
             <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="practicepleModalLabel">
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
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // DataTables initialization
        $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('detailed-practice-report',[$practice->id]) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                { data: 'task_taker', name: 'task_taker' },
                { data: 'completed_on', name: 'completed_on' },
                { data: 'percenatge', name: 'percenatge' },
                { data: 'ipadddress', name: 'ipadddress' },
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