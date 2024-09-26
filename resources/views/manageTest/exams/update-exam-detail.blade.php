@extends('layouts.master')

@section('title', 'Add Exam')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
          <!-- Stepper Section with Card -->
          <div class="mb-[30px]">
              <!-- Card Container -->
              <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                  <div class="flex items-center justify-between">
                      <!-- Step 1 (Active) -->
                       <a href="{{route('exam-detail',['id'=>$exam->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  1
                              </div>
                              <div class="text-primary mt-2">Details</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      
                      <!-- Step 2 (Inactive) -->
                      <a href="{{route('exam-setting',['id'=>$exam->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  2
                              </div>
                              <div class="text-gray-400 mt-2">Settings</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      
                      <!-- Step 3 (Inactive) -->
                      <a href="{{route('exam-section',['id'=>$exam->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  3
                              </div>
                              <div class="text-gray-400 mt-2">Sections</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
    
                      <!-- Step 4 (Inactive) -->
                      <a href="{{route('exam-questions',['id'=>$exam->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  4
                              </div>
                              <div class="text-gray-400 mt-2">Questions</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
    
                      <!-- Step 5 (Inactive) -->
                      <a href="{{route('exam-schedules',['id'=>$exam->id])}}" class="flex-1 text-center">
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
      
      <!-- Add Exam Form -->
      <div class="p-[25px]">
         <form action="{{route('update-exam-detail',['id'=>$exam->id])}}" method="POST" enctype="multipart/form-data" id="addExamForm">
            @csrf
            <!-- Exam Title -->
            <div class="mb-[20px]">
               <label for="title" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Exam Title <span class="text-red-500">*</span></label>
               <input id="title" name="title" type="text" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($exam){{$exam->title}}@endisset" placeholder="Enter exam title" />
            </div>

            <div class="mb-[20px]">
                <label for="img_url" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Image Url </label>
                <input type="url" id="img_url" name="img_url" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($exam){{$exam->img_url}}@endisset" placeholder="Enter Img url" />
            </div>

            <!-- Duration Type -->
            <div class="mb-[20px]">
               <label for="duration_type" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Duration Type <span class="text-red-500">*</span></label>
               <select id="duration_type" name="duration_type" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" onchange="toggleExamDurationInput(this)">
                    <option selected disabled>Select Duration Type</option>
                    <option value="exam_wise" {{$exam->duration_type == "exam_wise" ? "selected" : ""}}>Exam Wise</option>
                    <option value="ques_wise" {{$exam->duration_type == "ques_wise" ? "selected" : ""}}>Question Wise</option>
               </select>
            </div>

            <!-- Exam Duration (only if Exam Wise is selected) -->
            <div id="exam_duration_input " class="mb-[20px] {{$exam->duration_type == "ques_wise" ? "hidden" : ""}}">
               <label for="exam_duration" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Exam Duration (Minutes) <span class="text-red-500">*</span></label>
               <input id="exam_duration" name="exam_duration" type="number" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($exam){{$exam->exam_duration}}@endisset" placeholder="Enter exam duration in minutes" />
            </div>

            <!-- Sub Category -->
            <div class="mb-[20px]">
               <label for="sub_category" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Sub Category <span class="text-red-500">*</span></label>
               <select id="sub_category" name="sub_category" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                <option selected disabled>Select Sub Category</option>
                @isset($category)
                    @foreach ($category as $item)
                        <option value="{{$item->id}}" {{$exam->subcategory_id == $item->id ? "selected" : ""}}>{{$item->name}}</option>
                    @endforeach
                @endisset
               </select>
            </div>

             <!-- Exam Type -->
             <div class="mb-[20px]">
                <label for="exam_type" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Exam Type <span class="text-red-500">*</span></label>
                <select id="exam_type" name="exam_type" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                 <option selected disabled>Select Exam Type</option>
                 @isset($examType)
                     @foreach ($examType as $item)
                         <option value="{{$item->id}}" {{$exam->exam_type_id == $item->id ? "selected" : ""}}>{{$item->name}}</option>
                     @endforeach
                 @endisset
                </select>
             </div>

            <!-- Free or Paid (Updated Radio Design) -->
            <div class="mb-[20px]">
               <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Free or Paid <span class="text-red-500">*</span></label>
               <div class="flex flex-wrap items-center gap-[15px]">
                   <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="free" name="is_free" value="1" required onclick="togglePriceInput(false)" {{$exam->is_free == 1 ? "checked" : ""}}>
                      <label for="free" class="inline-block pl-[0.15rem] hover:cursor-pointer">Free</label>
                   </div>
                   <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="paid" name="is_free" value="0" onclick="togglePriceInput(true)" {{$exam->is_free == 0 ? "checked" : ""}}>
                      <label for="paid" class="inline-block pl-[0.15rem] hover:cursor-pointer">Paid</label>
                   </div>
               </div>
            </div>

            <!-- Price Input (if Paid is selected) -->
            <div id="price_input" class="mb-[20px] {{$exam->is_free == 1 ? "hidden" : ""}}">
               <label for="price" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Price (USD) <span class="text-red-500">*</span></label>
               <input id="price" name="price" type="number" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($exam){{$exam->price}}@endisset" placeholder="Enter price" />
            </div>

            <!-- Allow Download Report (Updated Radio Design) -->
            <div class="mb-[20px]">
               <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Allow Download Report <span class="text-red-500">*</span></label>
               <div class="flex flex-wrap items-center gap-[15px]">
                    <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="download_report" name="download_report" value="1" {{$exam->download_report == 1 ? "checked" : ""}}>
                      <label for="download_report" class="inline-block pl-[0.15rem] hover:cursor-pointer">Yes</label>
                    </div>
                    <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="download_report" name="download_report" value="0" {{$exam->download_report == 0 ? "checked" : ""}}>
                      <label for="download_report" class="inline-block pl-[0.15rem] hover:cursor-pointer">No</label>
                    </div>
               </div>
            </div>

            <!-- Description (Summernote Editor) -->
            <div class="mb-[20px]">
               <label for="description" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Description</label>
               <textarea id="description" name="description" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark summernote">@isset($exam){{$exam->description}}@endisset</textarea>
            </div>

            <!-- Visibility (Updated Radio Design) -->
            <div class="mb-[20px]">
               <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Visibility <span class="text-red-500">*</span></label>
               <div class="flex flex-wrap items-center gap-[15px]">
                   <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="visibility" name="visibility" value="1" required {{$exam->is_public == 1 ? "checked" : ""}}>
                      <label for="visibility" class="inline-block pl-[0.15rem] hover:cursor-pointer">Public</label>
                   </div>
                   <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="visibility_private" name="visibility" value="0" {{$exam->is_public == 0 ? "checked" : ""}}>
                      <label for="visibility_private" class="inline-block pl-[0.15rem] hover:cursor-pointer">Private</label>
                   </div>
               </div>
            </div>

            <!-- Favorite (Updated Radio Design) -->
            <div class="mb-[20px]">
               <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Favorite <span class="text-red-500">*</span></label>
               <div class="flex flex-wrap items-center gap-[15px]">
                   <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="favorite" name="favorite" value="1" required {{$exam->favourite == 1 ? "checked" : ""}}>
                      <label for="favorite" class="inline-block pl-[0.15rem] hover:cursor-pointer">Yes</label>
                   </div>
                   <div class="inline-block min-h-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="favorite" name="favorite" value="0" {{$exam->favourite == 0 ? "checked" : ""}}>
                      <label for="favorite" class="inline-block pl-[0.15rem] hover:cursor-pointer">No</label>
                   </div>
               </div>
            </div>

            <!-- Status (Updated Radio Design) -->
            <div class="mb-[20px]">
                <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Status <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap items-center gap-[15px]">
                    <div class="inline-block min-h-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="status" name="status" value="1" required {{$exam->status == 1 ? "checked" : ""}}>
                       <label for="status" class="inline-block pl-[0.15rem] hover:cursor-pointer">Yes</label>
                    </div>
                    <div class="inline-block min-h-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right  me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer" type="radio" id="status" name="status" value="0" {{$exam->status == 0 ? "checked" : ""}}>
                       <label for="status" class="inline-block pl-[0.15rem] hover:cursor-pointer">No</label>
                    </div>
                </div>
             </div>

            <!-- Buttons -->
            <div class="flex gap-x-[10px]">
                <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-check-circle mr-2"></i> Submit
                </button>
                <button type="reset" class="capitalize bg-danger/10 border-none text-danger text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-redo mr-2"></i> Reset
                </button>
            </div>
         </form>
      </div>
   </div>

</section>

@endsection
@push('scripts')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // jQuery Validation for the Add Exam form
        $("#addExamForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255
                },
                duration_type: {
                    required: true,
                },
                exam_duration: {
                    required: function(element) {
                        return $("#duration_type").val() == 'exam_wise';
                    },
                    number: true,
                    min: 1,
                },
                sub_category: {
                    required: true,
                },
                exam_type: {
                    required: true,
                },
                is_free: {
                    required: true,
                },
                price: {
                    required: function(element) {
                        return $("input[name='is_free']:checked").val() == '0';
                    },
                    number: true,
                    min: 0,
                },
                download_report: {
                    required: true,
                },
                description: {
                    maxlength: 1000,
                },
                visibility: {
                    required: true,
                },
                favorite: {
                    required: true,
                }
            },
            messages: {
                title: {
                    required: "Please enter an exam title.",
                    maxlength: "The title cannot exceed 255 characters."
                },
                duration_type: {
                    required: "Please select a duration type.",
                },
                exam_duration: {
                    required: "Please enter the exam duration.",
                    number: "Please enter a valid number.",
                    min: "Duration must be at least 1 minute.",
                },
                sub_category: {
                    required: "Please select a subcategory.",
                },
                exam_type: {
                    required: "Please select an exam type.",
                },
                is_free: {
                    required: "Please specify if the exam is free or paid.",
                },
                price: {
                    required: "Please enter a price.",
                    number: "Please enter a valid price.",
                    min: "Price cannot be negative.",
                },
                download_report: {
                    required: "Please specify if downloading reports is allowed.",
                },
                description: {
                    maxlength: "Description cannot exceed 1000 characters."
                },
                visibility: {
                    required: "Please select the visibility option.",
                },
                favorite: {
                    required: "Please specify if the exam is a favorite.",
                }
            },
            errorPlacement: function (error, element) {
                // For radio buttons, place the error after the radio group
                if (element.attr("type") == "radio") {
                    error.insertAfter(element.closest('.mb-[20px]'));
                } else {
                    error.addClass('text-red-500 text-sm');
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('border-red-500'); // Highlight fields with error
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-500'); // Remove highlight when valid
            },
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                form.submit();
            }
        });
    });

    // Toggle Exam Duration input visibility based on Duration Type selection
    function toggleExamDurationInput(select) {
        var examDurationInput = document.getElementById('exam_duration_input');
        if (select.value === 'exam_wise') {
            examDurationInput.style.display = 'block';
            $("#exam_duration").rules("add", {
                required: true,
                number: true,
                min: 1,
                messages: {
                    required: "Please enter the exam duration.",
                    number: "Please enter a valid number.",
                    min: "Duration must be at least 1 minute.",
                }
            });
        } else {
            examDurationInput.style.display = 'none';
            $("#exam_duration").rules("remove");
        }
    }

    // Show/hide price input based on Paid or Free selection
    function togglePriceInput(isPaid) {
        const priceInput = document.getElementById('price_input');
        if (isPaid) {
            priceInput.style.display = 'block';
            $("#price").rules("add", {
                required: true,
                number: true,
                min: 0,
                messages: {
                    required: "Please enter a price.",
                    number: "Please enter a valid price.",
                    min: "Price cannot be negative.",
                }
            });
        } else {
            priceInput.style.display = 'none';
            $("#price").rules("remove");
        }
    }

</script>
@endpush