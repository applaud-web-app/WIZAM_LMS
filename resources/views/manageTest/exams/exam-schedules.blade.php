@extends('layouts.master')

@section('title', 'Add Exam Schedule')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
          <!-- Stepper Section with Card -->
          <div class="mb-[30px]">
              <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                  <div class="flex items-center justify-between">
                      <!-- Step 1 -->
                      <div class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  1
                              </div>
                              <div class="text-primary mt-2">Details</div>
                          </div>
                      </div>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 2 -->
                      <div class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  2
                              </div>
                              <div class="text-primary mt-2">Settings</div>
                          </div>
                      </div>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 3 -->
                      <div class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  3
                              </div>
                              <div class="text-primary mt-2">Sections</div>
                          </div>
                      </div>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 4 -->
                      <div class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  4
                              </div>
                              <div class="text-primary mt-2">Questions</div>
                          </div>
                      </div>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 5 (Active) -->
                      <div class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  5
                              </div>
                              <div class="text-primary mt-2">Schedule</div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="px-[25px] py-3 text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex max-sm:h-auto border-b border-regular dark:border-box-dark-up">
         <h1 class="mb-0 inline-flex items-center py-1 overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
            List of Schedules</h1>
            <button type="button" class="flex items-center px-[14px] text-sm text-white rounded-md bg-primary border-primary h-10 gap-[6px] transition-[0.3s]" data-te-toggle="modal" data-te-target="#scheduleModal" data-te-ripple-init data-te-ripple-color="light">
               <i class="uil uil-plus"></i>
               <span class="m-0">Add Schedule</span>
            </button>
      </div>
      <div class="p-[25px] pt-[15px]">
         <div>
            <table class="min-w-full leading-normal table-auto display" id="quizz-table">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Type</th>
                     <th>Start At</th>
                     <th>End At</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</section>

<!-- Modal -->
<div data-te-modal-init class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
   <form method="POST" action="{{route('save-exam-schedules',['id'=>$id])}}" data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]" id="addForm">
    @csrf
      <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
         <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
            <!--Modal title-->
            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="scheduleModalLabel">
               Add New Schedule
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
            <!-- Schedule Type Dropdown -->
            <div class="mb-[15px]">
               <label for="scheduleType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Schedule Type <span class="text-red-500">*</span>
               </label>
               <div class="flex flex-col flex-1 md:flex-row">
                  <select id="scheduleType" name="scheduleType" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" onchange="toggleScheduleFields(this)">
                     <option value="fixed">Fixed</option>
                     <option value="flexible">Flexible</option>
                     <option value="attempts">Attempts</option>
                  </select>
               </div>
            </div>

            <!-- Start Date and Time (Common) -->
            <div class="mb-[15px]">
               <label for="startDate" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Start Date <span class="text-red-500">*</span>
               </label>
               <input type="date" id="startDate" name="startDate" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
            </div>
            <div class="mb-[15px]">
               <label for="startTime" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Start Time <span class="text-red-500">*</span>
               </label>
               <input type="time" id="startTime" name="startTime" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
            </div>

            <!-- End Date and Time (Flexible) -->
            <div id="flexibleFields" class="hidden">
               <div class="mb-[15px]">
                  <label for="endDate" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                     End Date <span class="text-red-500">*</span>
                  </label>
                  <input type="date" id="endDate" name="endDate" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
               </div>
               <div class="mb-[15px]">
                  <label for="endTime" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                     End Time <span class="text-red-500">*</span>
                  </label>
                  <input type="time" id="endTime" name="endTime" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
               </div>
            </div>

            <!-- Number of Attempts (Attempts) -->
            <div id="attemptsFields" class="hidden">
               <div class="mb-[15px]">
                  <label for="numAttempts" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                     Number of Attempts <span class="text-red-500">*</span>
                  </label>
                  <input type="number" id="numAttempts" name="numAttempts" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter number of attempts">
               </div>
            </div>

            <!-- Grace Period to Join (Common) -->
            <div class="mb-[15px]" id="gracePeriod">
               <label for="gracePeriod" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark" >
                  Grace Period (minutes) <span class="text-red-500">*</span>
               </label>
               <input type="number" id="gracePeriod" name="gracePeriod" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter grace period in minutes">
            </div>

            <!-- Schedule User Group -->
            <div class="mb-[15px]">
               <label for="userGroup" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  User Group <span class="text-red-500">*</span>
               </label>
               <select id="userGroup" name="userGroup" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                  <option selected disabled>Select User Group</option>
                  @isset($userGroup)
                      @foreach ($userGroup as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                      @endforeach
                  @endisset
                  <!-- Add more groups as needed -->
               </select>
            </div>
         </div>

         <!--Modal footer-->
         <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
            <button type="submit" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600">
               Submit
            </button>
            <button type="button" class="ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out" data-te-modal-dismiss>
               Cancel
            </button>
         </div>
      </div>
   </form>
</div>


<!-- Edit Modal -->
<div data-te-modal-init class="fixed p-3 left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <form method="POST" data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]" id="editForm">
        @csrf
       <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
          <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
             <!--Modal title-->
             <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="editModalLabel">
                Edit Schedule
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
             <!-- Schedule Type Dropdown -->
             <div class="mb-[15px]">
                <label for="scheduleType" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                   Schedule Type <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-col flex-1 md:flex-row">
                   <select id="scheduleType2" name="scheduleType" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" onchange="toggleScheduleFields2(this)">
                      <option value="fixed">Fixed</option>
                      <option value="flexible">Flexible</option>
                      <option value="attempts">Attempts</option>
                   </select>
                </div>
             </div>
 
             <!-- Start Date and Time (Common) -->
             <div class="mb-[15px]">
                <label for="startDate" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                   Start Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="startDate" name="startDate" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
             </div>
             <div class="mb-[15px]">
                <label for="startTime" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                   Start Time <span class="text-red-500">*</span>
                </label>
                <input type="time" id="startTime" name="startTime" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
             </div>
 
             <!-- End Date and Time (Flexible) -->
             <div id="flexibleFields2" class="hidden">
                <div class="mb-[15px]">
                   <label for="endDate" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                      End Date <span class="text-red-500">*</span>
                   </label>
                   <input type="date" id="endDate" name="endDate" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-[15px]">
                   <label for="endTime" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                      End Time <span class="text-red-500">*</span>
                   </label>
                   <input type="time" id="endTime" name="endTime" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                </div>
             </div>
 
             <!-- Number of Attempts (Attempts) -->
             <div id="attemptsFields2" class="hidden">
                <div class="mb-[15px]">
                   <label for="numAttempts" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                      Number of Attempts <span class="text-red-500">*</span>
                   </label>
                   <input type="number" id="numAttempts" name="numAttempts" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter number of attempts">
                </div>
             </div>
 
             <!-- Grace Period to Join (Common) -->
             <div class="mb-[15px]" id="gracePerioid2">
                <label for="gracePeriod" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark" >
                   Grace Period (minutes) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="gracePeriod" name="gracePeriod" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Enter grace period in minutes">
             </div>
 
             <!-- Schedule User Group -->
             <div class="mb-[15px]">
                <label for="userGroup" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                   User Group <span class="text-red-500">*</span>
                </label>
                <select id="userGroup" name="userGroup" required class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                    <option selected disabled>Select User Group</option>
                    @isset($userGroup)
                        @foreach ($userGroup as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @endisset
                </select>
             </div>
          </div>
 
          <!--Modal footer-->
          <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
             <button type="submit" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out hover:bg-primary-600">
                Submit
             </button>
             <button type="button" class="ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white transition duration-150 ease-in-out" data-te-modal-dismiss>
                Cancel
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
    $(document).ready(function() {
        // When delete item is clicked, store the URL in the confirm button
        $(document).on('click', '.deleteItem', function() {
            const delUrl = $(this).data('url');
            console.log(delUrl);
            $('#confirmDelete').data('url', delUrl); // Use data method to set the URL
        });

        // When confirm delete is clicked, redirect to the URL
        $(document).on('click', '#confirmDelete', function() {
            const delUrl = $(this).data('url'); // Use data method to get the URL
            window.location.href = delUrl;
        });

        $(document).on('click', '.editItem', function() {
            // Retrieve data attributes
            const editUrl = $(this).data('url');
            const id = $(this).data('id');
            const scheduleType = $(this).data('schedule_type');
            const startDate = $(this).data('start_date');
            const startTime = $(this).data('start_time');
            const endDate = $(this).data('end_date');
            const endTime = $(this).data('end_time');
            const gracePeriod = $(this).data('grace_period');
            const userGroups = $(this).data('user_groups');
            const status = $(this).data('status');
            const attempts = $(this).data('attempts'); // Add attempts data attribute

            // Set form action
            let form = $('#editForm');
            form.attr('action', editUrl);
            
            // Populate form fields
            form.find('select[name="scheduleType"]').val(scheduleType).trigger('change');
            form.find('input[name="startDate"]').val(startDate);
            form.find('input[name="startTime"]').val(startTime);
            form.find('input[name="endDate"]').val(endDate);
            form.find('input[name="endTime"]').val(endTime);
            form.find('input[name="schedule_id"]').val(id);
            form.find('select[name="userGroup"]').val(userGroups);

            if(scheduleType == "fixed"){
               form.find('input[name="gracePeriod"]').val(gracePeriod);
            }else if(scheduleType == "attempts"){
               form.find('input[name="numAttempts"]').val(gracePeriod);
            }
            
            // Show the fields conditionally based on schedule type
            toggleScheduleFields2(form.find('select[name="scheduleType"]')[0]);

            // Show the modal
            $('#editModal').modal('show');
        });

        // Initialize DataTables
        $('#quizz-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('exam-schedules',['id'=>$id]) }}",
                dataSrc: function(json) {
                    console.log(json); // Inspect the response structure
                    return json.data; // Return the data array
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'type', name: 'type'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        // jQuery Validation for the form
        $("#addForm").validate({
            rules: {
                scheduleType: {
                    required: true
                },
                startDate: {
                    required: true,
                    date: true
                },
                startTime: {
                    required: true
                },
                endDate: {
                    required: function() {
                        return $("#scheduleType").val() === 'flexible';
                    },
                    date: true
                },
                endTime: {
                    required: function() {
                        return $("#scheduleType").val() === 'flexible';
                    }
                },
                gracePeriod: {
                    required: function() {
                        return $("#scheduleType").val() === 'fixed';
                    },
                    number: true,
                    min: 1
                },
                userGroup: {
                    required: true
                },
                attempts: { // Validate attempts field
                    required: true,
                    number: true,
                    min: 1
                }
            },
            messages: {
                scheduleType: "Please select a schedule type",
                startDate: "Please select a valid start date",
                startTime: "Please enter a valid start time",
                endDate: "Please select a valid end date for flexible schedules",
                endTime: "Please enter a valid end time for flexible schedules",
                gracePeriod: "Please enter a valid grace period (only for fixed schedules)",
                userGroup: "Please select a user group",
                attempts: "Please enter a valid number of attempts" // Error message for attempts
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
    });

    // Handle toggle between schedule types
    $("#scheduleType").on('change', function() {
        toggleScheduleFields(this);
    });

    // Toggle schedule fields
    function toggleScheduleFields(select) {
        const scheduleType = select.value;
        const flexibleFields = $('#flexibleFields');
        const gracePeriod = $('#gracePeriod');
        const attemptsFields = $('#attemptsFields');

         flexibleFields.addClass('hidden');
         gracePeriod.addClass('hidden');
         attemptsFields.addClass('hidden');

         if (scheduleType === 'flexible') {
            flexibleFields.removeClass('hidden');
         } else if (scheduleType === 'fixed') {
            gracePeriod.removeClass('hidden');
         }else if(scheduleType === 'attempts'){
            attemptsFields.removeClass('hidden');
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
        const attemptsFields = $('#attemptsFields2');

         flexibleFields.addClass('hidden');
         gracePeriod.addClass('hidden');
         attemptsFields.addClass('hidden');

         if (scheduleType === 'flexible') {
            flexibleFields.removeClass('hidden');
         } else if (scheduleType === 'fixed') {
            gracePeriod.removeClass('hidden');
         }else if(scheduleType === 'attempts'){
            attemptsFields.removeClass('hidden');
         }
    }
</script>
@endpush
