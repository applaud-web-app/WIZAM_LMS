@extends('layouts.master')

@section('title', 'Add Question Settings')

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
                      <a href="{{route('update-question-details',['id'=>request()->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  1
                              </div>
                              <div class="text-primary mt-2">Question</div>
                          </div>
                      </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 2 -->
                      <a href="{{route('update-question-setting',['id'=>request()->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  2
                              </div>
                              <div class="text-primary mt-2">Settings</div>
                          </div>
                       </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 3 -->
                       <a href="{{route('update-question-solution',['id'=>request()->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  3
                              </div>
                              <div class="text-gray-400 mt-2">Solution</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 4 -->
                       <a href="{{route('update-question-attachment',['id'=>request()->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  4
                              </div>
                              <div class="text-gray-400 mt-2">Attachment</div>
                          </div>
                        </a>
                  </div>
              </div>
              <!-- End of Card -->
          </div>
      </div>
  </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <!-- MCQ Question Settings Form -->
      <div class="p-[25px]">
         <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data" id="addSetting">
            @csrf
            <!-- Skill (Dropdown) -->
            <div class="mb-[20px]">
               <label for="skill" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Skill <span class="text-red-500">*</span></label>
               <select id="skill" name="skill" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                <option selected disabled>Select Skill Level</option>
                @isset($skill)
                    @foreach ($skill as $item)
                        <option value="{{$item->id}}" @isset($question->skill_id) {{$question->skill_id == $item->id ? "selected" : ""}}@endisset>{{$item->name}}</option>
                    @endforeach
                @endisset
               </select>
            </div>

            <!-- Topic (Dropdown) -->
            <div class="mb-[20px]">
               <label for="topic" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Topic <span class="text-red-500">*</span></label>
               <select id="topic" name="topic" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option selected disabled>Select Topic</option>
                    @isset($topic)
                        @foreach ($topic as $item)
                                <option value="{{$item->id}}" @isset($question->topic_id) {{$question->topic_id == $item->id ? "selected" : ""}}@endisset>{{$item->name}}</option>
                        @endforeach
                    @endisset
               </select>
            </div>

            <!-- Tags (Dropdown, multiple select) -->
            <div class="col-span-12 mb-[20px]">
               <label for="tags" class="block text-sm font-medium text-body dark:text-title-dark mb-2"> Tags</label>
               <input type="text" name="tags" id="tags" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($question->tags){{$question->tags}}@endisset" placeholder="tags">
            </div>

            <!-- Difficulty Level -->
            <div class="mb-[20px]">
               <label for="difficulty" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Difficulty Level <span class="text-red-500">*</span></label>
               <select id="difficulty" name="difficulty" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option disabled selected>Select Difficulty</option>
                     @if ($question->level == null)
                        @php $question->level = "easy"; @endphp
                     @endif
                    <option value="very_easy" {{$question->level == "very_easy" ? 'selected' : ''}}>Very Easy</option>
                    <option value="easy" {{$question->level == "easy" ? 'selected' : ''}}>Easy</option>
                    <option value="medium" {{$question->level == "medium" ? 'selected' : ''}}>Medium</option>
                    <option value="hard" {{$question->level == "hard" ? 'selected' : ''}}>Hard</option>
                    <option value="very_hard" {{$question->level == "very_hard" ? 'selected' : ''}}>Very Hard</option>
               </select>
            </div>

            <!-- Default Marks/Grade Points -->
            <div class="mb-[20px]">
               <label for="marks" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Default Marks/Grade Points <span class="text-red-500">*</span></label>
               @php $defaultMark = 1 @endphp
               @if (isset($question->default_marks))
                  @php $defaultMark = $question->default_marks @endphp
               @endif
               {{-- @isset($question->default_marks){{$question->default_marks}}@endisset --}}
               <input id="marks" name="default_grade" type="number" value="{{$defaultMark}}" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter default marks/points" />
            </div>

            <!-- Default Time To Solve (Seconds) -->
            <div class="mb-[20px]">
               <label for="time_to_solve" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Default Time To Solve (Seconds) <span class="text-red-500">*</span></label>
               @php $defaultTime = 60 @endphp
               @if (isset($question->watch_time))
                  @php $defaultTime = $question->watch_time @endphp
               @endif
               <input id="time_to_solve" value="{{$defaultTime}}" name="solve_time" type="number" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter default time in seconds" />
            </div>

            <!-- Status (Radio buttons) -->
            <div class="mb-[20px]">
               <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Status <span class="text-red-500">*</span></label>
               <div class="flex flex-wrap items-center gap-[15px]">
                   <!--First radio-->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="1"  {{$question->status == 1 ? 'checked' : ''}}>
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="status">Enable</label>
                   </div>
                   <!--Second radio-->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="0" {{$question->status == 0 ? 'checked' : ''}}>
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="status">Disable</label>
                   </div>
                </div>
           </div>

            <!-- Buttons -->
            <div class="flex gap-x-[10px]">
                <!-- Submit Button with Unicons Icon -->
                <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-check-circle mr-2"></i> <!-- Submit Icon (Unicons) -->
                    Submit
                </button>
            </div>
         </form>
      </div>
   </div>

</section>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    <script>
    var input = document.querySelector('#tags'),
        // init Tagify script on the above inputs
        tagify = new Tagify(input, {
            whitelist: [],
            maxTags: 10,
            dropdown: {
                maxItems: 20,           // <- mixumum allowed rendered suggestions
                classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        })
    </script>
    <script>
        $(document).ready(function() {
           // Initialize the form validation
           $('#addSetting').validate({
              rules: {
                 skill: {
                    required: true
                 },
                 topic: {
                    required: true
                 },
                 difficulty_level: {
                    required: true
                 },
                 default_grade: {
                    required: true
                 },
                 solve_time: {
                    required: true,
                    number: true,
                    min: 1
                 },
                 status: {
                    required: true
                 }
              },
              messages: {
                 skill: {
                    required: "Please select a skill"
                 },
                 topic: {
                    required: "Please select a Topic"
                 },
                 difficulty_level: {
                    required: "Please select a difficulty level"
                 },
                 solve_time: {
                    required: "Please enter read time",
                    number: "Please enter a valid number",
                    min: "Read time must be at least 1 minute"
                 },
                 default_grade: {
                    required: "Please select whether the lesson is paid or not"
                 },
                 status: {
                    required: "Please select the status of the lesson"
                 }
              },
              submitHandler: function(form) {
                 $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                 form.submit();
              }
           });
        });
     </script>
@endpush