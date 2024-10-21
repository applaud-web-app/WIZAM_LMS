@extends('layouts.master')

@section('title', 'Add Lesson')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Add Lesson</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Current Page -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Add lesson</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <!-- Blog Form -->
      <div class="p-[25px]">
         <form id="addLessonForm" action="{{route('store-lesson')}}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-[25px]">
            @csrf

            <!-- lesson Title -->
            <div class="col-span-12">
               <label for="lesson_title" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Title<span class="text-red-500">*</span></label>
               <input type="text" name="lesson_title" id="lesson_title" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter lesson title" required>
            </div>

            <!-- Description -->
            <div class="col-span-12">
               <label for="description" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Description<span class="text-red-500">*</span></label>
               <textarea name="description" id="description" rows="4" required class="summernote w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter a short description"></textarea>
            </div>

            <!-- Skill -->
            <div class="col-span-12 md:col-span-6">
               <label for="skill" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Skill<span class="text-red-500">*</span></label>
               <select name="skill" id="skill" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" required>
                   <option value="" disabled selected>Select Skill</option>
                    @isset($skill)
                        @foreach ($skill as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @endisset
               </select>
            </div>

            <!-- Topic -->
            <div class="col-span-12 md:col-span-6">
               <label for="topic" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Topic</label>
               <select name="topic" id="topic" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark">
                  <option value="" disabled selected>Select Topic</option>
                    @isset($topic)
                        @foreach ($topic as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @endisset
               </select>
            </div>

            <!-- Tags -->
            {{-- <div class="col-span-12">
               <label for="tags" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Tags</label>
               <select name="tags[]" id="tags" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark">
                  <option value="tag1">Tag 1</option>
                  <option value="tag2">Tag 2</option>
                  <option value="tag3">Tag 3</option>
               </select>
            </div> --}}
            <div class="col-span-12">
               <label for="tags" class="block text-sm font-medium text-body dark:text-title-dark mb-2"> Tags</label>
               <input type="text" name="tags" id="tags" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="tags">
            </div>

            <!-- Difficulty Level -->
            <div class="col-span-12 md:col-span-6">
               <label for="difficulty_level" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Difficulty Level<span class="text-red-500">*</span></label>
               <select name="difficulty_level" id="difficulty_level" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" required>
                  <option value="" disabled selected>Select Difficulty Level</option>
                  <option value="very_easy">Very Easy</option>
                  <option value="easy">Easy</option>
                  <option value="medium">Medium</option>
                  <option value="hard">Hard</option>
                  <option value="very_hard">Very Hard</option>
               </select>
            </div>

            <!-- Read Time (Minutes) -->
            <div class="col-span-12 md:col-span-6">
               <label for="read_time" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Read Time (Minutes)<span class="text-red-500">*</span></label>
               <input type="number" name="read_time" id="read_time" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="1" min="1" placeholder="Enter read time in minutes" required>
            </div>

            

            <!-- Paid Radio -->
            <div class="col-span-12 md:col-span-6">
                <label for="paid" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Paid<span class="text-red-500">*</span>
                </label>
                <div class="flex flex-wrap items-center gap-[15px]">
                    <!--First radio-->
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" id="paid_yes" name="paid" value="1" checked>
                       <label for="paid_yes" class="inline-block text-[15px] font-normal leading-[20px] text-body dark:text-title-dark cursor-pointer">Yes</label>
                    </div>
                    <!--Second radio-->
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" id="paid_no" name="paid" value="0">
                       <label for="paid_no" class="inline-block text-[15px] font-normal leading-[20px] text-body dark:text-title-dark cursor-pointer">No</label>
                    </div>
                </div>
             </div>

            <!-- Status Radio -->
            <div class="col-span-12 md:col-span-6">
               <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                   Status<span class="text-red-500">*</span>
               </label>
               <div class="flex flex-wrap items-center gap-[15px]">
                   <!--First radio-->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" id="status_active" name="status" value="1" checked>
                      <label for="status_active" class="inline-block text-[15px] font-normal leading-[20px] text-body dark:text-title-dark cursor-pointer">Active</label>
                   </div>
                   <!--Second radio-->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" id="status_inactive" name="status" value="0">
                      <label for="status_inactive" class="inline-block text-[15px] font-normal leading-[20px] text-body dark:text-title-dark cursor-pointer">Inactive</label>
                   </div>
               </div>
            </div>
            <!-- Submit Button -->
            <div class="col-span-12 text-right mt-4">
               <button type="submit" class="px-4 py-2 bg-primary text-white rounded">Submit</button>
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

<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
<script>
   $(document).ready(function() {
      $('.summernote').summernote({
         height: 300,
       
      });
   });
</script>
<script>
   $(document).ready(function() {
      // Initialize the form validation
      $('#addLessonForm').validate({
         rules: {
            lesson_title: {
               required: true,
               minlength: 2
            },
            description: {
               required: true,
               minlength: 10
            },
            skill: {
               required: true
            },
            difficulty_level: {
               required: true
            },
            read_time: {
               required: true,
               number: true,
               min: 1
            },
            paid: {
               required: true
            },
            status: {
               required: true
            }
         },
         messages: {
            lesson_title: {
               required: "Please enter a title",
               minlength: "Title must be at least 2 characters long"
            },
            description: {
               required: "Please enter a description",
               minlength: "Description must be at least 10 characters long"
            },
            skill: {
               required: "Please select a skill"
            },
            difficulty_level: {
               required: "Please select a difficulty level"
            },
            read_time: {
               required: "Please enter read time",
               number: "Please enter a valid number",
               min: "Read time must be at least 1 minute"
            },
            paid: {
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
