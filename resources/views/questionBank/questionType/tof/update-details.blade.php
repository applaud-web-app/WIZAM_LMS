@extends('layouts.master')

@section('title', 'Add True/False/Custom Question')

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
                    <div class="w-[40px] h-[2px] bg-gray-300"></div>
                    <!-- Step 2 -->
                    <a href="{{route('update-question-setting',['id'=>request()->id])}}" class="flex-1 text-center">
                        <div class="relative flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                2
                            </div>
                            <div class="text-gray-400 mt-2">Settings</div>
                        </div>
                    </a>
                    <!-- Divider -->
                    <div class="w-[40px] h-[2px] bg-gray-300"></div>
                    <!-- Step 3 -->
                    <a href="{{route('update-question-solution',['id'=>request()->id])}}" class="flex-1 text-center">
                        <div class="relative flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500  flex items-center justify-center">
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
      
      <!-- True/False/Custom Question Form -->
      <div class="p-[25px]">
         <form action="{{route('update-tof-details',['id'=>$question->id])}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Skill Level -->
            <div class="mb-[20px]">
               <label for="skill" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Skill Level <span class="text-red-500">*</span></label>
               <select id="skill" name="skill" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                <option selected disabled>Select Skill Level</option>
                @isset($skill)
                    @foreach ($skill as $item)
                            <option value="{{$item->id}}" @isset($question){{$question->skill_id == $item->id ? "selected":""}}@endisset>{{$item->name}}</option>
                    @endforeach
                @endisset
               </select>
            </div>

            <!-- Question Title -->
            <div class="mb-[20px]">
               <label for="question" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question <span class="text-red-500">*</span></label>
               <textarea id="question" name="question" rows="2" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the true/false question...">@isset($question){{ $question->question }}@endisset</textarea>
            </div>

            <!-- True/False/Custom Options -->
            @if(isset($question) && isset($question->options))
                <!-- Matching Pairs Section -->
                @php $options = json_decode($question->options,true); @endphp
                <div class="mb-[20px]" id="optionsContainer">
                    @foreach ($options as $item)
                        <div class="optionItem mb-[20px]">
                            <label for="option{{$loop->index+1}}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option {{$loop->index+1}}  <span class="text-red-500">*</span></label>
                            <textarea id="option{{$loop->index+1}}" name="option[]" rows="1" required class=" w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">{{$item}}</textarea>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mb-[20px]" id="optionsContainer">
                    <div class="optionItem mb-[20px]">
                        <label for="option1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 1  <span class="text-red-500">*</span></label>
                        <textarea id="option1" name="option[]" rows="1" required class=" w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">True</textarea>
                    </div>
                    <div class="optionItem mb-[20px]">
                        <label for="option2" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 2  <span class="text-red-500">*</span></label>
                        <textarea id="option2" name="option[]" rows="1" required class=" w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">False</textarea>
                    </div>
                </div>
            @endif

            <!-- Correct Answer -->
            <div class="mb-[20px]">
               <label for="correct" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Correct Answer <span class="text-red-500">*</span></label>
               <select id="correct" name="answer" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                  <option value="1" @isset($question){{$question->answer == "1" ? 'selected':''}}@endisset>Option 1</option>
                  <option value="2" @isset($question){{$question->answer == "2" ? 'selected':''}}@endisset>Option 2</option>
               </select>
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

@endpush