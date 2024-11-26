@extends('layouts.master')

@section('title', 'Add Question Attachment')

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
                        <a href="{{route('update-question-setting',['id'=>request()->id])}}"  class="flex-1 text-center">
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
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 4 -->
                        <a href="{{route('update-question-attachment',['id'=>request()->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  4
                              </div>
                              <div class="text-primary mt-2">Attachment</div>
                          </div>
                        </a>
                  </div>
              </div>
              <!-- End of Card -->
          </div>
      </div>
  </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <!-- MCQ Question Attachment Form -->
      <div class="p-[25px]">
         <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Enable Question Attachment (Select Dropdown) -->
            @php  $enable = "no"; @endphp
            @isset($solution->attachment_source)
                @php  $enable = "yes"; @endphp
            @endisset
            <div class="mb-[20px]">
               <label for="enableAttachment" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Enable Question Attachment <span class="text-red-500">*</span></label>
               <select id="enableAttachment" name="question_attachment" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                  <option value="yes" {{$enable == "yes" ? 'selected':''}}>Yes</option>
                  <option value="no" {{$enable == "no" ? 'selected':''}}>No</option>
               </select>
            </div>

            <!-- Attachment Type (Radio Buttons) -->
            <div class="mb-[20px]">
               <label class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                  Attachment Type
               </label>
               <div class="flex flex-wrap items-center gap-[10px]">
                   <!-- Comprehension Passage -->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer  dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="attachment_type" id="attachmentComprehension" value="comprehension" {{$solution->attachment_type == "comprehension" ? 'checked':''}}>
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="attachmentComprehension">Comprehension Passage</label>
                   </div>
                   <!-- Video -->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer  dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="attachment_type" id="attachmentVideo" value="video" {{$solution->attachment_type == "video" ? 'checked':''}}>
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="attachmentVideo">Video</label>
                   </div>
                   <!-- Audio -->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer  dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="attachment_type" id="attachmentAudio" value="audio"  {{$solution->attachment_type == "audio" ? 'checked':''}}>
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="attachmentAudio">Audio</label>
                   </div>
               </div>
            </div>

            <!-- Conditional Fields for Attachment Type -->
            <div id="comprehensionField" class="mb-[20px] {{$solution->attachment_type == "comprehension" ? '':'hidden'}}">
               <label for="comprehension_type" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Choose Comprehension</label>
               <select id="comprehension_type" name="comprehension_type" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                  <option value="" disabled selected>Select Comprehension</option>
                    @isset($comprehension)
                      @foreach ($comprehension as $item)
                        <option value="{{$item->id}}" {{$solution->attachment_source == $item->id ? 'selected':''}}>{{$item->title}}</option>
                      @endforeach
                    @endisset
               </select>
            </div>

            <!-- Video Field -->
            <div id="videoField" class="mb-[20px] {{$solution->attachment_type == "video" ? '':'hidden'}}">
               <label for="videoInput" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Video Type (Supported YouTube, Vimeo & .mp4 files)</label>
               <div class="mb-[20px]" id="videoUpload">
                    <ul class="flex flex-row flex-wrap pl-0 mb-5 list-none border-b-0" role="tablist" data-te-nav-ref>
                    <li role="presentation">
                        <a href="#tabs-messages-one" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2  text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"  data-type="MP4" data-te-toggle="pill" data-te-target="#tabs-messages-one" role="tab" aria-controls="tabs-messages-one" aria-selected="{{$solution->video_type == 'MP4' ? 'true' : 'false'}}" {{$solution->video_type == 'MP4' ? 'data-te-nav-active' : ''}}>MP4 Video</a>
                    </li>
                    <li role="presentation">
                        <a href="#tabs-home-one" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-type="YouTube" data-te-toggle="pill" data-te-target="#tabs-home-one"  role="tab" aria-controls="tabs-home-one" aria-selected="{{$solution->attachment_video_type == 'YouTube' ? 'true' : 'false'}}" {{$solution->attachment_video_type == 'YouTube' ? 'data-te-nav-active' : ''}}>Youtube Video</a>
                    </li>
                    <li role="presentation">
                        <a href="#tabs-profile-one" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-type="Vimeo" data-te-toggle="pill" data-te-target="#tabs-profile-one" role="tab" aria-controls="tabs-profile-one" aria-selected="{{$solution->attachment_video_type == 'Vimeo' ? 'true' : 'false'}}" {{$solution->attachment_video_type == 'Vimeo' ? 'data-te-nav-active' : ''}}>Vimeo Video</a>
                    </li>
                    </ul>
    
                    <!--Tabs content-->
                    <div class="mb-[18px]">
                    <input type="hidden" name="video_type" value="" id="videoType">
                    <div class="hidden opacity-{{$solution->attachment_video_type == "YouTube" ? "100" : 0}} text-breadcrumbs text-14 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-home-one" role="tabpanel" aria-labelledby="tabs-home-tab" @if($solution->attachment_video_type == "YouTube") data-te-tab-active @endif>
                        <input type="text" name="source_YouTube" id="YouTube" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->attachment_source){{$solution->attachment_video_type == "YouTube" ? $solution->attachment_source : ""}}@endisset" placeholder="Enter YouTube Id">
                    </div>
                    <div class="hidden opacity-{{$solution->attachment_video_type == "Vimeo" ? "100" : 0}} transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabs-profile-one" role="tabpanel" aria-labelledby="tabs-profile-tab" @if($solution->attachment_video_type == "Vimeo") data-te-tab-active @endif>
                        <input type="text" name="source_Vimeo" id="Vimeo" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->attachment_source){{$solution->attachment_video_type == "Vimeo" ? $solution->attachment_source : ""}}@endisset" placeholder="Enter Vimeo Id">
                    </div>
                    <div class="hidden opacity-{{$solution->attachment_video_type == "MP4" ? "100" : 0}} transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabs-messages-one" role="tabpanel" aria-labelledby="tabs-profile-tab" @if($solution->attachment_video_type == "MP4") data-te-tab-active @endif >
                        <input type="url" name="source_MP4" id="MP4" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->attachment_source){{$solution->attachment_video_type == "MP4" ? $solution->attachment_source : ""}}@endisset" placeholder="Enter Video Link">
                    </div>
                    </div>
                </div>
            </div>



            <!-- Audio Field -->
            <div id="audioField" class="mb-[20px] hidden">
               <label for="audioInput" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Audio Type (Supported .mp3 & .ogg files)</label>
                <div class="mb-[20px]" id="videoUpload">
                    <ul class="flex flex-row flex-wrap pl-0 mb-5 list-none border-b-0" role="tablist" data-te-nav-ref>
                        <li role="presentation">
                            <a href="#tabsmessagesone" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2  text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400"  data-type="MP3" data-te-toggle="pill" data-te-target="#tabsmessagesone" role="tab" aria-controls="tabsmessagesone" aria-selected="{{$solution->attachment_video_type == 'MP3' ? 'true' : 'false'}}" {{$solution->attachment_video_type == 'MP3' ? 'data-te-nav-active' : ''}}>MP3 FORMAT</a>
                        </li>
                        <li role="presentation">
                            <a href="#tabshomeone" class="video_typer block border-x-0 border-b-2 border-t-0 border-transparent px-3 py-2 me-4 text-14 font-normal capitalize leading-tight text-dark hover:isolate hover:border-transparent focus:isolate focus:border-transparent data-[te-nav-active]:border-primary data-[te-nav-active]:text-primary dark:text-neutral-400 dark:hover:bg-transparent dark:data-[te-nav-active]:border-primary-400 dark:data-[te-nav-active]:text-primary-400" data-type="OGG" data-te-toggle="pill" data-te-target="#tabshomeone"  role="tab" aria-controls="tabshomeone" aria-selected="{{$solution->attachment_video_type == 'OGG' ? 'true' : 'false'}}" {{$solution->attachment_video_type == 'OGG' ? 'data-te-nav-active' : ''}}>OGG FORMAT</a>
                        </li>
                    </ul>
                    <!--Tabs content-->
                    <div class="mb-[18px]">
                        <div class="hidden opacity-{{$solution->attachment_video_type == "MP3" ? "100" : 0}} text-breadcrumbs text-14 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabsmessagesone" role="tabpanel" aria-labelledby="tabsmessagesone-tab" @if($solution->attachment_video_type == "MP3") data-te-tab-active @endif>
                            <input type="text" name="source_MP3" id="mp3" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->attachment_source){{$solution->attachment_video_type == "MP3" ? $solution->attachment_source : ""}}@endisset" placeholder="Enter MP3 Id">
                        </div>
                        <div class="hidden opacity-{{$solution->attachment_video_type == "OGG" ? "100" : 0}} transition-opacity text-breadcrumbs text-14 duration-150 ease-linear data-[te-tab-active]:block" id="tabshomeone" role="tabpanel" aria-labelledby="tabshomeone-tab" @if($solution->attachment_video_type == "OGG") data-te-tab-active @endif>
                            <input type="text" name="source_OGG" id="ogg" class="w-full px-4 py-3 rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up text-body dark:text-subtitle-dark" value="@isset($solution->attachment_source){{$solution->attachment_video_type == "OGG" ? $solution->attachment_source : ""}}@endisset" placeholder="Enter OGG Id">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Buttons -->
            <div class="flex gap-x-[10px]">
               <button type="submit" class="px-[14px] text-sm text-white rounded-md bg-primary border-primary h-10 gap-[6px] transition-[0.3s]">Submit</button>
               <button type="button" class="px-[14px] text-sm text-white rounded-md bg-danger border-danger h-10 gap-[6px] transition-[0.3s]">Cancel</button>
            </div>
         </form>
      </div>
   </div>

</section>

@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initial setup - hide all fields
            // $('#comprehensionField, #videoField, #audioField').addClass('hidden');
    
            // Listen to changes in the Enable Attachment dropdown
            $('#enableAttachment').on('change', function() {
                if ($(this).val() === 'yes') {
                    // Show attachment options based on the selected type
                    $('input[name="attachment_type"]:checked').trigger('change');
                } else {
                    // Hide all fields if 'No' is selected
                    $('#comprehensionField, #videoField, #audioField').addClass('hidden');
                }
            });
    
            // Listen to changes in attachment type (radio buttons)
            $('input[name="attachment_type"]').on('change', function() {
                if ($('#enableAttachment').val() === 'yes') {
                    // Hide all fields initially
                    $('#comprehensionField, #videoField, #audioField').addClass('hidden');
    
                    // Show the corresponding field based on the selected attachment type
                    if ($('#attachmentComprehension').is(':checked')) {
                        $('#comprehensionField').removeClass('hidden');
                    } else if ($('#attachmentVideo').is(':checked')) {
                        $('#videoField').removeClass('hidden');
                    } else if ($('#attachmentAudio').is(':checked')) {
                        $('#audioField').removeClass('hidden');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).on('click','.video_typer',function(){
            const type = $(this).data('type');
            console.log(type);
            $('#videoType').val(type);
        })
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const attachmentTypeRadios = document.querySelectorAll('input[name="attachment_type"]');
        const comprehensionField = document.getElementById('comprehensionField');
        const videoField = document.getElementById('videoField');
        const audioField = document.getElementById('audioField');

        function updateFields() {
            const selectedType = document.querySelector('input[name="attachment_type"]:checked').value;

            comprehensionField.style.display = selectedType === 'comprehension' ? 'block' : 'none';
            videoField.style.display = selectedType === 'video' ? 'block' : 'none';
            audioField.style.display = selectedType === 'audio' ? 'block' : 'none';
        }

        attachmentTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateFields);
        });

        // Initialize visibility on page load
        updateFields();
    });

    </script>
@endpush