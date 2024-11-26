@extends('layouts.master')

@section('title', 'Add Exam Section')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
          <!-- Stepper Section with Card -->
          <div class="mb-[30px]">
              <!-- Card Container -->
              <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                  <div class="flex items-center justify-between">
                      <!-- Step 1 -->
                      <a href="{{route('exam-detail',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  1
                              </div>
                              <div class="text-primary mt-2">Details</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 2 -->
                      <a href="{{route('exam-setting',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  2
                              </div>
                              <div class="text-primary mt-2">Settings</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 3 (Active) -->
                      <a href="{{route('exam-section',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  3
                              </div>
                              <div class="text-primary mt-2">Sections</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 4 -->
                       <a href="{{route('exam-questions',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  4
                              </div>
                              <div class="text-gray-400 mt-2">Questions</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 5 -->
                      <a href="{{route('exam-schedules',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  5
                              </div>
                              <div class="text-gray-400 mt-2">Schedule</div>
                          </div>
                        </a>
                  </div>
              </div>
              <!-- End of Card -->
          </div>
      </div>
  </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="px-[25px] py-3 text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex max-sm:h-auto border-b border-regular dark:border-box-dark-up">
         <h1 class="mb-0 inline-flex items-center py-1 overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
            List of Sections</h1>
            <button type="button" class="flex items-center px-[14px] text-sm text-white rounded-md bg-primary border-primary h-10 gap-[6px] transition-[0.3s]" data-te-toggle="modal" data-te-target="#sectionModal" data-te-ripple-init data-te-ripple-color="light">
               <i class="uil uil-plus"></i>
               <span class="m-0">Add Section</span>
            </button>
      </div>
      <div class="p-[25px] pt-[15px]">

        <div class="table-responsive" >
            <table class="min-w-full leading-normal table-auto display" id="sectionTable">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Display Name</th>
                     <th>Section</th>
                     <th>Total Questions</th>
                     <th>Total Duration</th>
                     <th>Total Marks</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>

      </div>
   </div>

</section>

 <!-- Modal -->
<div data-te-modal-init class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="sectionModal" tabindex="-1" aria-labelledby="sectionModalLabel" aria-hidden="true">
   <form action="{{route('add-exam-section',['id'=>$examSetting->id])}}" method="POST" data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]" id="addSection">
        @csrf
      <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
         <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
            <!--Modal title-->
            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="sectionModalLabel">
               Add New Section
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
            <!-- Section Name -->
            <div class="mb-[15px]">
               <label for="section_name" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Section Name <span class="text-red-500">*</span>
               </label>
               <div class="flex flex-col flex-1 md:flex-row">
                  <input type="text" id="section_name" name="section_name" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Section Name">
               </div>
            </div>

            <!-- Section Category Dropdown -->
            <div class="mb-[15px]">
               <label for="section_category" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Section Category <span class="text-red-500">*</span>
               </label>
               <div class="flex flex-col flex-1 md:flex-row">
                  <select id="section_category" name="section_category" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                        <option disabled selected>Select Category</option>
                        @isset($section)
                            @foreach ($section as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        @endisset
                  </select>
               </div>
            </div>
         
      
            <!-- Marks -->
            <div class="mb-[15px]">
               <label for="display_order" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Display Order <span class="text-red-500">*</span>
               </label>
               <div class="flex flex-col flex-1 md:flex-row">
                  <input type="number" id="display_order" name="display_order" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter marks">
               </div>
            </div>
         </div>
         <!--Modal footer-->
         <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
            <button type="button" class="ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
               Cancel
            </button>
            <button type="submit" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-ripple-init data-te-ripple-color="light">
               Submit
            </button>
         </div>
      </div>
   </form>
</div>

 <!-- Edit Modal -->
 <div data-te-modal-init class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <form  method="POST" data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]" id="editSection">
        @csrf
        <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
            <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                <!--Modal title-->
                <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="editModalLabel">
                    Edit Section
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
                <!-- Section Name -->
                <div class="mb-[15px]">
                    <label for="section_name" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Section Name <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text" id="section_name" name="section_name" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Section Name">
                    </div>
                </div>
    
                <!-- Section Category Dropdown -->
                <div class="mb-[15px]">
                    <label for="section_category" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Section Category <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                    <select id="section_category" name="section_category" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                            <option disabled selected>Select Category</option>
                            @isset($section)
                                @foreach ($section as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            @endisset
                    </select>
                    </div>
                </div>
            
        
                <!-- Marks -->
                <div class="mb-[15px]">
                    <label for="display_order" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Display Order <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                    <input type="number" id="display_order" name="display_order" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter marks">
                    </div>
                </div>
            </div>
            <!--Modal footer-->
            <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                <button type="button" class="ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                    Cancel
                </button>
                <button type="submit" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-ripple-init data-te-ripple-color="light">
                    Submit
                </button>
            </div>
        </div>
    </form>
 </div>


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

    $(document).on('click', '.editItem', function() {
        // Retrieve data attributes
        const editUrl = $(this).data('url');
        const id = $(this).data('id');
        const section_category = $(this).data('section_category');
        const section_name = $(this).data('section_name');
        const display_order = $(this).data('display_order');

        // Set form action
        let form = $('#editSection');
        form.attr('action', editUrl);
        
        // Populate form fields
        form.find('select[name="section_category"]').val(section_category);
        form.find('input[name="section_name"]').val(section_name);
        form.find('input[name="display_order"]').val(display_order);

        // Show the modal
        $('#editModal').modal('show');
    });
</script>
 <script>
        $('#sectionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('exam-section',['id'=>$examSetting->id]) }}",
                dataSrc: function (json) {
                    console.log(json); // Inspect the response structure
                    return json.data; // Return the data array
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'display_name', name: 'display_name'},
                {data: 'section', name: 'section'},
                {data: 'total_questions', name: 'total_questions'}, 
                {data: 'total_duration', name: 'total_duration'},
                {data: 'total_marks', name: 'total_marks'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $("#addSection").validate({
        rules: {
            section_name: {
                required: true,
                minlength: 2
            },
            section_category: {
                required: true
            },
            display_order: {
                required: true,
                digits: true,
                min: 1
            }
        },
        messages: {
            section_name: {
                required: "Please enter a section name",
                minlength: "Section name must be at least 2 characters long"
            },
            section_category: "Please select a section category",
            display_order: {
                required: "Please enter a display order",
                digits: "Display order must be a number",
                min: "Display order must be at least 1"
            }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            error.addClass('text-red-500 text-sm');
            error.insertAfter(element);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("border-red-500").removeClass("border-normal");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("border-red-500").addClass("border-normal");
        },
        submitHandler: function(form) {
            // Submit the form if validation is successful
            form.submit();
        }
    });

    // Handle toggle between schedule types
    $("#scheduleType").on('change', function() {
        toggleScheduleFields(this);
    });

    // Toggle schedule fields
    function toggleScheduleFields(select) {
        const scheduleType = select.value;
        const flexibleFields = $('#flexibleFields');
        const gracePeriod = $('#gracePerioid');

        if (scheduleType === 'flexible') {
            flexibleFields.removeClass('hidden');
            gracePeriod.addClass('hidden');
        } else if (scheduleType === 'fixed') {
            gracePeriod.removeClass('hidden');
            flexibleFields.addClass('hidden');
        }
    }

    // Handle toggle between schedule types
    $("#scheduleType2").on('change', function() {
        toggleScheduleFields2(this);
    });

    // Toggle schedule fields
    function toggleScheduleFields2(select) {
        const scheduleType = select.value;
        const flexibleFields = $('#flexibleFields2');
        const gracePeriod = $('#gracePerioid2');

        if (scheduleType === 'flexible') {
            flexibleFields.removeClass('hidden');
            gracePeriod.addClass('hidden');
        } else if (scheduleType === 'fixed') {
            gracePeriod.removeClass('hidden');
            flexibleFields.addClass('hidden');
        }
    }
</script>
@endpush