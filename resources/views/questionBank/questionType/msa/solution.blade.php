@extends('layouts.master')

@section('title', 'Add Question Solution')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

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
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 3 -->
                       <a href="{{route('update-question-solution',['id'=>request()->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  3
                              </div>
                              <div class="text-primary mt-2">Solution</div>
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
      
      <!-- MCQ Question Solution Form -->
      <div class="p-[25px]">
         <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Solution (Summernote) -->
            <div class="mb-[20px]">
               <label for="solution" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Solution </label>
               <textarea id="solution" name="solution" rows="4" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the solution...">@isset($solution->solution){{$solution->solution}}@endisset</textarea>
            </div>

            <!-- Enable Solution Video (Radio) -->
            <div class="mb-[20px]">
               <label for="solutionvideo" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Enable Solution Video
               </label>
               <div class="flex flex-wrap items-center gap-[15px]">
                   <!--First radio-->
                     @php $enable = 0; @endphp
                     @if (isset($solution->video_enable) && $solution->video_enable == 1)
                        @php $enable = 1; @endphp
                     @endif
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="video_solution" id="video_solution" value="1" autocompleted="" {{$enable == 1 ? 'checked' : ''}} />
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="video_solution">Enable</label>
                   </div>
                   <!--Second radio-->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="video_solution" id="video_solution" value="0" autocompleted="" {{$enable == 0 ? 'checked' : ''}}/>
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="video_solution">Disable</label>
                   </div>
               </div>
            </div>
            
            <div class="mb-[20px] {{$enable == 0 ? 'hidden' : ''}}" id="videoUpload">
               <ul class="flex flex-row flex-wrap pl-0 mb-5 list-none border-b-0" role="tablist" data-te-nav-ref>
                  <li role="presentation">
                     <a href="#tabs-messages-one" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2  text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"  data-type="MP4" data-te-toggle="pill" data-te-target="#tabs-messages-one" role="tab" aria-controls="tabs-messages-one" aria-selected=" @isset($solution->video_type){{$solution->video_type == 'MP4' ? 'true' : 'false'}}@endisset"  @isset($solution->video_type){{$solution->video_type == 'MP4' ? 'data-te-nav-active' : ''}}@endisset>MP4 Video</a>
                  </li>
                  <li role="presentation">
                     <a href="#tabs-home-one" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-type="YouTube" data-te-toggle="pill" data-te-target="#tabs-home-one"  role="tab" aria-controls="tabs-home-one" aria-selected="@isset($solution->video_type){{$solution->video_type == 'YouTube' ? 'true' : 'false'}}@endisset" @isset($solution->video_type){{$solution->video_type == 'YouTube' ? 'data-te-nav-active' : ''}}@endisset>Youtube Video</a>
                  </li>
                  
                  <li role="presentation">
                     <a href="#tabs-profile-one" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-type="Vimeo" data-te-toggle="pill" data-te-target="#tabs-profile-one" role="tab" aria-controls="tabs-profile-one" aria-selected="@isset($solution->video_type){{$solution->video_type == 'Vimeo' ? 'true' : 'false'}}@endisset" @isset($solution->video_type){{$solution->video_type == 'Vimeo' ? 'data-te-nav-active' : ''}}@endisset>Vimeo Video</a>
                  </li>
               </ul>
               <!--Tabs content-->
               <div class="mb-[18px]">
                  <input type="hidden" name="video_type" value="" id="videoType">
                  <div class="hidden opacity-@isset($solution->video_type){{$solution->video_type == "YouTube" ? "100" : 0}}@endisset text-breadcrumbs text-14 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-home-one" role="tabpanel" aria-labelledby="tabs-home-tab" @if(isset($solution->video_type) && $solution->video_type == "YouTube") data-te-tab-active @endif>
                     <input type="text" name="youtube_id" id="youtube_id" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->video_source){{$solution->video_type == "YouTube" ? $solution->video_source : ""}}@endisset" placeholder="Enter YouTube Id">
                  </div>
                  <div class="hidden opacity-@isset($solution->video_type){{$solution->video_type == "Vimeo" ? "100" : 0}}@endisset transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabs-profile-one" role="tabpanel" aria-labelledby="tabs-profile-tab" @if(isset($solution->video_type) && $solution->video_type == "Vimeo") data-te-tab-active @endif>
                     <input type="text" name="vimeo_id" id="vimeo_id" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->video_source){{$solution->video_type == "Vimeo" ? $solution->video_source : ""}}@endisset" placeholder="Enter Vimeo Id">
                  </div>
                  <div class="hidden opacity-@isset($solution->video_type){{$solution->video_type == "MP4" ? "100" : 0}}@endisset transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabs-messages-one" role="tabpanel" aria-labelledby="tabs-profile-tab" @if(isset($solution->video_type) &&$solution->video_type == "MP4") data-te-tab-active @endif >
                     <input type="url" name="video_url" id="video_url" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->video_source){{$solution->video_type == "MP4" ? $solution->video_source : ""}}@endisset" placeholder="Enter Video Link">
                  </div>
               </div>
            </div>
            <!-- hint (Summernote) -->
            <div class="mb-[20px]">
               <label for="hint" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Hint</label>
               <textarea id="hint" name="hint" rows="4" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the solution...">@isset($solution->hint){{$solution->hint}}@endisset</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-x-[10px]">
                <!-- Submit Button with Unicons Icon -->
                <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-check-circle mr-2"></i> <!-- Submit Icon (Unicons) -->
                    Submit
                </button>
                
                <!-- Reset Button with Unicons Icon -->
                <button type="button" class="capitalize bg-danger/10 border-none text-danger text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-redo mr-2"></i> <!-- Reset Icon (Unicons) -->
                    Reset
                </button>
            </div>
         </form>
         
      </div>
   </div>

</section>

@endsection
@push('scripts')
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
   $(document).ready(function() {
      // Initialize Summernote
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

    
    
   });
</script>

<script>
   $(document).ready(function() {
      // Show/hide video upload based on Enable/Disable radio button
      $('input[name="video_solution"]').on('change', function() {
         if ($('#video_solution').is(':checked')) {
            $('#videoUpload').removeClass('hidden');
         } else {
            $('#videoUpload').addClass('hidden');
         }
      });

      $(document).on('click','.video_typer',function(){
         const type = $(this).data('type');
         console.log(type);
         $('#videoType').val(type);
      })
   });
</script>
@endpush