@extends('layouts.master')

@section('title', 'Add Short Answer Question')

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
      
      <!-- Short Answer Question Form -->
      <div class="p-[25px]">
         <form action="{{route('update-soq-details',['id'=>$question->id])}}" method="POST" enctype="multipart/form-data">
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
               <textarea id="question" name="question" rows="2" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the short answer question...">@isset($question){{ $question->question }}@endisset</textarea>
            </div>

            <!-- Options Section -->
            @php $count = 1; @endphp
            @if (isset($question) && isset($question->options))    
            <div id="optionsContainer" class="mb-[20px] ">   
                @php $options = json_decode($question->options,true); $count = count($options); @endphp
                @foreach ($options as $key => $item)
                    <div class="optionItem bg-gray-50 p-2 mb-[20px]">
                        <label for="option{{$key+1}}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option {{$key+1}} <span class="text-red-500">*</span></label>
                        <textarea id="option{{$key+1}}" name="option[]" rows="1" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the option {{$key+1}}">{{$item}}</textarea>
                        <!-- Custom Radio Button for Exact Match -->
                        <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem] mt-3">
                            <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="answer" id="correctOption{{$key+1}}" value="{{$key+1}}" {{$key+1 == $question->answer ? "checked" : "" }}>
                            <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="correctOption{{$key+1}}">Acceptable Answer</label>
                        </div>
                        @if ($key > 0)
                            <button type="button" class="removeOption mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
                        @endif
                    </div>
                @endforeach
            </div>
            @else
            <div id="optionsContainer" class="mb-[20px] ">
                <div class="optionItem bg-gray-50 p-2 mb-[20px]">
                   <label for="option1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 1 <span class="text-red-500">*</span></label>
                   <textarea id="option1" name="option[]" rows="1" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the first option"></textarea>
                   <!-- Custom Radio Button for Exact Match -->
                   <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem] mt-3">
                      <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="answer" id="correctOption1" value="1">
                      <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="correctOption1">Acceptable Answer</label>
                   </div>
                </div>
             </div>
            @endif
            

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
                        onpaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

                        e.preventDefault();

                        setTimeout( function(){
                            document.execCommand( 'insertText', false, bufferText );
                        }, 10 );
                    }
    });

    let optionCount = {{$count}}; // Tracks the number of options

    // Add more options dynamically
    $('#addMoreOptions').on('click', function() {
        optionCount++;
        const newOption = `
            <div class="optionItem bg-gray-50 p-2 mb-[20px]">
               <label for="option${optionCount}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option ${optionCount}</label>
               <textarea id="option${optionCount}" name="option[]" rows="1" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter option ${optionCount}"></textarea>
               <!-- Custom Radio Button for Exact Match -->
               <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem] mt-3">
                  <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="answer" id="correctOption${optionCount}" value="${optionCount}">
                  <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="correctOption${optionCount}">Acceptable Answer</label>
                </div>
                <button type="button" class="removeOption mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
            </div>`;
        $('#optionsContainer').append(newOption);
    });

    // Remove option functionality
    $(document).on('click', '.removeOption', function() {
        $(this).closest('.optionItem').remove();
        renumberOptions(); // Re-sequence the options after removal
    });

    // Function to renumber options after adding/removing options
    function renumberOptions() {
        $('#optionsContainer .optionItem').each(function(index, element) {
            const newNumber = index + 1; // Start from 1
            // Update label
            $(element).find('label').first().text(`Option ${newNumber}`);
            // Update textarea id and placeholder
            $(element).find('textarea').first().attr('id', `option${newNumber}`).attr('placeholder', `Enter option ${newNumber}`);
            // Update radio input id and value
            $(element).find('input[type="radio"]').attr('id', `correctOption${newNumber}`).val(newNumber);
            // Update the label for the radio button
            $(element).find('label[for^="correctOption"]').attr('for', `correctOption${newNumber}`);
        });
    }

    // Remove option functionality
    $(document).on('click', '.removeOption', function() {
        $(this).closest('.optionItem').remove();
        renumberOptions(); // Re-sequence the options after removal
    });

    // Function to renumber options after adding/removing options
    function renumberOptions() {
        optionCount = 0; // Reset optionCount

        $('#optionsContainer .optionItem').each(function(index, element) {
            const newNumber = index + 1; // Start numbering from 1
            optionCount = newNumber; // Update optionCount to the new highest number

            // Update label
            $(element).find('label').first().text(`Option ${newNumber}`);
            // Update textarea id and placeholder
            $(element).find('textarea').first().attr('id', `option${newNumber}`).attr('placeholder', `Enter option ${newNumber}`);
            // Update radio input id and value
            $(element).find('input[type="radio"]').attr('id', `correctOption${newNumber}`).val(newNumber);
            // Update the label for the radio button
            $(element).find('label[for^="correctOption"]').attr('for', `correctOption${newNumber}`);
        });
    }
});

</script>
@endpush