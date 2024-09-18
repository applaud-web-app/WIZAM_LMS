@extends('layouts.master')

@section('title', 'Add Video')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Add Video</h4>
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
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Update Video</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>

   <!-- Form Section -->
   <div class="grid grid-cols-12 gap-[20px]">
      <div class="col-span-12">
         <!-- Form Card -->
         <div class="bg-white dark:bg-box-dark p-7 shadow-pricing dark:shadow-none rounded-10">
            <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-[25px]" id="videoForm">
               @csrf
               <!-- Video Title -->
               <div class="col-span-12">
                  <label for="video_title" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Video Title<span class="text-red-500">*</span></label>
                  <input type="text" name="video_title" value="@isset($video->title){{$video->title}}@endisset" id="video_title" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter video title" required>
               </div>
               <!-- Video Type Tabs (YouTube, Vimeo, MP4) -->
               <div class="col-span-12">
                  <ul class="flex flex-row flex-wrap pl-0 mb-5 list-none border-b-0" role="tablist" data-te-nav-ref>
                     <li role="presentation">
                        <a href="#tabs-messages-one" class="block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2  text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-te-toggle="pill" data-te-target="#tabs-messages-one" role="tab" aria-controls="tabs-messages-one" aria-selected="{{$video->type == 'MP4' ? 'true' : 'false'}}" {{$video->type == 'MP4' ? 'data-te-nav-active' : ''}}>MP4 Video</a>
                     </li>
                     <li role="presentation">
                        <a href="#tabs-home-one" class="block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-te-toggle="pill" data-te-target="#tabs-home-one"  role="tab" aria-controls="tabs-home-one" aria-selected="{{$video->type == 'YouTube' ? 'true' : 'false'}}" {{$video->type == 'YouTube' ? 'data-te-nav-active' : ''}}>YouTube Video</a>
                     </li>
                     <li role="presentation">
                        <a href="#tabs-profile-one" class="block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-te-toggle="pill" data-te-target="#tabs-profile-one" role="tab" aria-controls="tabs-profile-one" aria-selected="{{$video->type == 'Vimeo' ? 'true' : 'false'}}" {{$video->type == 'Vimeo' ? 'data-te-nav-active' : ''}}>Vimeo Video</a>
                     </li>
                  </ul>

                  <!--Tabs content-->
                  <div class="mb-[18px]">
                     <div class="hidden opacity-{{$video->type == "YouTube" ? "100" : 0}} text-breadcrumbs text-14 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-home-one" role="tabpanel" aria-labelledby="tabs-home-tab" @if($video->type == "YouTube") data-te-tab-active @endif>
                        <input type="text" name="youtube_id" value="@isset($video->source){{$video->type == "YouTube" ? $video->source : ""}}@endisset" id="youtube_id" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter YouTube Id">
                     </div>
                     <div class="hidden opacity-{{$video->type == "Vimeo" ? "100" : 0}} transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabs-profile-one" role="tabpanel" aria-labelledby="tabs-profile-tab" @if($video->type == "Vimeo") data-te-tab-active @endif>
                        <input type="text" name="vimeo_id" value="@isset($video->source){{$video->type == "Vimeo" ? $video->source : ""}}@endisset" id="vimeo_id" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter Vimeo Id">
                     </div>
                     <div class="hidden opacity-{{$video->type == "MP4" ? "100" : 0}} transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabs-messages-one"  role="tabpanel" aria-labelledby="tabs-profile-tab" @if($video->type == "MP4") data-te-tab-active @endif>
                        <input type="url" name="video_url" value="@isset($video->source){{$video->type == "MP4" ? $video->source : ""}}@endisset" id="video_url" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter Video Link">
                     </div>
                  </div>
               </div>

               <!-- Video Thumbnail -->
               <div class="col-span-12">
                  <label for="video_thumbnail" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Video Thumbnail</label>
                  <input class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white" type="file" id="video_thumbnail" name="video_thumbnail" />
                  @isset($video->thumbnail)
                  <img src="{{asset('fileManager/thumbnail/'.$video->thumbnail.'')}}" height="100px" width="100px" alt="">
                  @endisset
               </div>

               <!-- Description -->
               <div class="col-span-12">
                  <label for="description" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Description</label>
                  <textarea name="description" id="description" rows="4" class="summernote w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter a short description">@isset($video->description){{$video->description}}@endisset</textarea>
               </div>

               <!-- Skill -->
               <div class="col-span-12 md:col-span-6">
                  <label for="skill" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Skill<span class="text-red-500">*</span></label>
                  <select name="skill" id="skill" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" required>
                     <option value="" disabled selected>Select Skill</option>
                     @isset($skill)
                         @foreach ($skill as $item)
                           <option value="{{$item->id}}" {{$video->skill_id == $item->id ? "selected":""}}>{{$item->name}}</option>
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
                           <option value="{{$item->id}}" {{$video->topic_id == $item->id ? "selected":""}}>{{$item->name}}</option>
                         @endforeach
                     @endisset
                  </select>
               </div>

               <!-- Tags -->
               <div class="col-span-12">
                  <label for="tags" class="block text-sm font-medium text-body dark:text-title-dark mb-2"> Tags</label>
                  <input type="text" name="tags" value="@isset($video->tags){{$video->tags}}@endisset" id="tags" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="tags" >
               </div>

               <!-- Difficulty Level -->
               <div class="col-span-12 md:col-span-6">
                  <label for="difficulty_level" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Difficulty Level<span class="text-red-500">*</span></label>
                  <select name="difficulty_level" id="difficulty_level" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" required>
                     <option value="" disabled selected>Select Difficulty Level</option>
                     <option value="very_easy" {{$video->level == "very_easy" ? "selected" : ""}}>Very Easy</option>
                  <option value="easy" {{$video->level == "easy" ? "selected" : ""}}>Easy</option>
                  <option value="medium" {{$video->level == "medium" ? "selected" : ""}}>Medium</option>
                  <option value="hard" {{$video->level == "hard" ? "selected" : ""}}>Hard</option>
                  <option value="very_hard" {{$video->level == "very_hard" ? "selected" : ""}}>Very Hard</option>
                  </select>
               </div>

               <!-- Watch Time (Minutes) -->
               <div class="col-span-12 md:col-span-6">
                  <label for="watch_time" class="block text-sm font-medium text-body dark:text-title-dark mb-2">Watch Time (Minutes)<span class="text-red-500">*</span></label>
                  <input type="number" name="watch_time" id="watch_time" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" placeholder="Enter watch time in minutes" value="@isset($video->watch_time){{$video->watch_time}}@endisset" min="1" required>
               </div>

               <!-- Paid Radio -->
               <div class="col-span-12 md:col-span-6">
                  <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                      Paid <span class="text-red-500">*</span>
                  </label>
                  <div class="flex flex-wrap items-center gap-[15px]">
                      <!--First radio-->
                      <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="paid" id="paid" value="1" autocompleted="" {{$video->is_free == 0 ? "checked" : ""}}>
                         <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="paid">Yes</label>
                      </div>
                      <!--Second radio-->
                      <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="paid" id="paid" value="0" autocompleted="" {{$video->is_free == 1 ? "checked" : ""}}>
                         <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="paid">No</label>
                      </div>
                  
                   </div>
              </div>

               <!-- Active Radio -->
               <div class="col-span-12 md:col-span-6">
                  <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                      Status <span class="text-red-500">*</span>
                  </label>
                  <div class="flex flex-wrap items-center gap-[15px]">
                      <!--First radio-->
                      <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="1" autocompleted="" {{$video->status == 1 ? "checked" : ""}}>
                         <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="status">Enable</label>
                      </div>
                      <!--Second radio-->
                      <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                         <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="0" autocompleted="" {{$video->status == 0 ? "checked" : ""}}>
                         <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="status">Disable</label>
                      </div>
                  
                   </div>
              </div>

               <!-- Submit Button -->
               <div class="col-span-12">
                  <button type="submit" class="px-[30px] py-[10px] rounded bg-primary text-white capitalize hover:bg-primary-dark focus:ring-primary focus:outline-none">
                     Update Video
                  </button>
               </div>
            </form>
         </div>
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
   document.addEventListener('DOMContentLoaded', function () {
      // Add logic for tab switching
      const tabs = document.querySelectorAll('.tabs ul li a');
      const tabContents = document.querySelectorAll('.tab-content');
      
      tabs.forEach(tab => {
         tab.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Remove active class from all tabs and contents
            tabs.forEach(item => item.classList.remove('active'));
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Add active class to clicked tab and display corresponding content
            tab.classList.add('active');
            document.querySelector(tab.getAttribute('href')).classList.remove('hidden');
         });
      });
   });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
<script>
   $(document).ready(function() {
      $('.summernote').summernote({
         height: 300,
         toolbar: [
           ['style', ['style']],
           ['font', ['bold', 'underline', 'clear']],
           ['color', ['color']],
           ['para', ['ul', 'ol', 'paragraph']],
           ['table', ['table']],
           ['insert', ['link', 'picture', 'video']],
           ['view', ['fullscreen', 'codeview', 'help']]
         ]
      });
   });
</script>
<script>
   $(document).ready(function() {
      // Initialize the form validation
      $('#videoForm').validate({
         rules: {
            video_title: {
               required: true,
               minlength: 2
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
            video_title: {
               required: "Please enter a title",
               minlength: "Title must be at least 2 characters long"
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