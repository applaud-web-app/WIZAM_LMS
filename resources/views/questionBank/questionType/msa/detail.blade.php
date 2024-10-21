@extends('layouts.master')

@section('title', 'Add Question')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
          <!-- Stepper Section with Card -->
          <div class="mb-[30px]">
              <!-- Card Container -->
              <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                @if (isset($question))
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
                @else
                    <div class="flex items-center justify-between">
                        <!-- Step 1 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    1
                                </div>
                                <div class="text-primary mt-2">Question</div>
                            </div>
                        </div>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-primary"></div>
                        <!-- Step 2 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    2
                                </div>
                                <div class="text-gray-400 mt-2">Settings</div>
                            </div>
                        </div>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-gray-300"></div>
                        <!-- Step 3 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    3
                                </div>
                                <div class="text-gray-400 mt-2">Solution</div>
                            </div>
                        </div>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-gray-300"></div>
                        <!-- Step 4 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    4
                                </div>
                                <div class="text-gray-400 mt-2">Attachment</div>
                            </div>
                        </div>
                    </div>
                @endif
              </div>
              <!-- End of Card -->
          </div>
      </div>
   </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <!-- MCQ Question Form -->
      <div class="p-[25px]">
        @php
            $action_url = url()->full();
            if(isset($question)){
                $action_url = route('save-question-details',['id'=>$question->id]);
            }
        @endphp
         <form action="{{$action_url}}" method="POST" enctype="multipart/form-data" id="addQuestion">
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
               <textarea id="question" name="question" rows="2" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the question...">@isset($question){{$question->question}}@endisset</textarea>
            </div>
            
            <!-- MCQ Options (Dynamic) -->
            <div class="mb-[20px]" id="optionsContainer">
            @if(isset($question) && isset($question->options))
                @php $options = json_decode($question->options,true); @endphp
                @foreach ($options as $item)
                    <!-- Initial Options -->
                    <div class="mb-4 optionItem" data-index="{{$loop->index}}">
                        <div class="text-sm font-semibold mb-2">Option {{$loop->index+1}}</div>
                        <textarea id="option_text_{{$loop->index}}" name="option[]" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark" placeholder="Enter option {{$loop->index+1}}">{{$item}}</textarea>
                        <div class="flex flex-col sm:flex-row bg-gray-50 border-b border-l border-r border-gray-300 sm:justify-between  px-4 py-2">
                            <div class="flex gap-1 items-center">
                                <input type="radio" id="option-{{$loop->index}}" name="correctOption" class="custom-control-input" value="{{$loop->index+1}}" {{$loop->index+1 == $question->answer ? 'checked':''}}>
                                <label for="option-{{$loop->index}}" class="custom-control-label">Correct Answer</label>
                            </div>
                            @if ($loop->index > 1)
                                <div class="flex items-center sm:justify-end gap-2">
                                    <button type="button" class="removeOption text-red-500 hover:text-red-700">Remove</button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Initial Options -->
                <div class="mb-4 optionItem" data-index="0">
                    <div class="text-sm font-semibold mb-2">Option 1</div>
                    <textarea id="option_text_0" name="option[]" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark" placeholder="Enter option 1"></textarea>
                    <div class="flex flex-col sm:flex-row bg-gray-50 border-b border-l border-r border-gray-300 sm:justify-between  px-4 py-2">
                        <div class="flex gap-1 items-center">
                            <input type="radio" id="option-0" name="correctOption" class="custom-control-input" value="1">
                            <label for="option-0" class="custom-control-label">Correct Answer</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 optionItem" data-index="1">
                    <div class="text-sm font-semibold mb-2">Option 2</div>
                    <textarea id="option_text_1" name="option[]" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark" placeholder="Enter option 2"></textarea>
                    <div class="flex flex-col sm:flex-row bg-gray-50 border-b border-l border-r border-gray-300 sm:justify-between  px-4 py-2">
                        <div class="flex gap-1 items-center">
                            <input type="radio" id="option-1" name="correctOption" class="custom-control-input" value="2">
                            <label for="option-1" class="custom-control-label">Correct Answer</label>
                        </div>
                    </div>
                </div>
            @endif
            </div>

            <!-- Add More Button -->
            <div class="mb-[20px]">
               <button type="button" id="addMoreOptions" class="px-[14px] text-sm text-white rounded-md bg-secondary border-secondary h-10 gap-[6px] transition-[0.3s]">Add More</button>
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

<script>
    // jQuery Validation for the Add Question form
    $("#addQuestion").validate({
        rules: {
            skill: {
                required: true,
            },
            correctOption: {
                required: true
            }
        },
        messages: {
            skill: {
                required: "Please enter a Skill",
            },
            correctOption: {
                required: "Please select a status"
            }
        },
        submitHandler: function(form) {
            $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
            // Form submission code can go here
            form.submit();
        }
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
   $(document).ready(function() {
       // Initialize Summernote
       $('.summernote').summernote({
            height: 150,
            onpaste: function (e) {
    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

    e.preventDefault();

    setTimeout( function(){
        document.execCommand( 'insertText', false, bufferText );
    }, 10 );
}

           
         });

       // Counter for options
       let optionCount = 2;

       // Add More Options
       $('#addMoreOptions').click(function() {
           if (optionCount < 10) {
               const newOption = `
                   <div class="mb-4 optionItem" data-index="${optionCount}">
                       <div class="text-sm font-semibold mb-2">Option ${optionCount + 1}</div>
                       <textarea id="option_text_${optionCount}" name="option[]" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark" placeholder="Enter option ${optionCount + 1}"></textarea>
                       <div class="flex flex-col sm:flex-row bg-gray-50 border-b border-l border-r border-gray-300 sm:justify-between sm:items-center px-4 py-2">
                           <div class="flex gap-1 items-center">
                               <input type="radio" id="option-${optionCount}" name="correctOption" class="custom-control-input" value="${optionCount + 1}">
                               <label for="option-${optionCount}" class="custom-control-label">Correct Answer</label>
                           </div>
                           <div class="flex items-center sm:justify-end gap-2">
                               <button type="button" class="removeOption text-red-500 hover:text-red-700">Remove</button>
                           </div>
                       </div>
                   </div>
               `;
               $('#optionsContainer').append(newOption);
               $('.summernote').summernote({
                    height: 150,
                
                });
               optionCount++;
           } else {
               alert('You can only add up to 10 options.');
           }
       });

       // Remove Option
       $(document).on('click', '.removeOption', function() {
           $(this).closest('.optionItem').remove();
           optionCount--;
       });
   });
</script>
@endpush
