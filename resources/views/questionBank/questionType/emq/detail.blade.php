@extends('layouts.master')

@section('title', 'Add Question')

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
                            <div
                                class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
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
                            <div
                                class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
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
                            <div
                                class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                4
                            </div>
                            <div class="text-gray-400 mt-2">Attachment</div>
                        </div>
                    </div>
                </div>
              </div>
              <!-- End of Card -->
          </div>
      </div>
   </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <!-- MCQ Question Form -->
      <div class="p-[25px]">
         <form action="{{route('save-emq-details')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Common Skill Level -->
            <div class="mb-[30px]">
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

            <div class="mb-[20px]">
                <label for="question"
                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question <span
                        class="text-red-500">*</span></label>
                <textarea id="question" name="question[]" rows="2" required
                    class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                    placeholder="Enter the question. Wrap the word or phrase you want as a blank with ## (e.g., The capital of France is ##Paris##)."></textarea>
            </div>

            <!-- Common Options Container -->
            <div id="commonOptionsContainer" class="mb-[30px]">
               <h3 class="mb-[10px] font-bold text-lg">Common MCQ Options</h3>
               <div id="optionsContainer">
                  <div class="optionItem mb-[20px]">
                     <label for="option1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 1 <span class="text-red-500">*</span></label>
                     <textarea id="option1" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the first option"></textarea>
                  </div>

                  <div class="optionItem mb-[20px]">
                     <label for="option2" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 2 <span class="text-red-500">*</span></label>
                     <textarea id="option2" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the second option"></textarea>
                  </div>
               </div>

               <!-- Add More Options Button -->
               <button type="button" id="addMoreOptions" class="px-[14px] text-sm text-white rounded-md bg-warning border-warning h-10 gap-[6px] transition-[0.3s]">Add More Options</button>
            </div>

            <!-- Questions Container -->
            <div id="questionsContainer">
               <!-- Single Question Block (Can be duplicated dynamically) -->
               <div class="questionItem mb-[40px] border p-4 rounded-lg" id="question_1">
                  <!-- Question Title -->
                  <div class="mb-[20px]">
                     <label for="questionTitle_1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question 1 <span class="text-red-500">*</span></label>
                     <textarea id="questionTitle_1" name="question[]" rows="2" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the question..."></textarea>
                  </div>

                  <!-- Correct Answer for this question -->
                  <div class="mb-[20px]">
                     <label for="correctOption_1" class="answer_label block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Answer <span class="text-red-500">*</span></label>
                     <select id="correctOption_1" name="answer[]" required class="question-select w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                     </select>
                  </div>

                  <!-- Remove Question Button -->
                  {{-- <button type="button" class="removeQuestion px-[14px] text-sm text-white rounded-md bg-danger border-danger h-10 gap-[6px] transition-[0.3s]">Remove Question</button> --}}
               </div>
               <!-- End of Single Question Block -->
            </div>

            <!-- Add More Questions Button -->
            <div class="mb-[20px]">
               <button type="button" id="addMoreQuestions" class="px-[14px] text-sm text-white rounded-md bg-secondary border-secondary h-10 gap-[6px] transition-[0.3s]">Add More Questions</button>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-x-[10px]">
               <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                  <i class="uil uil-check-circle mr-2"></i>
                  Submit
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

    let optionCount = 2;  // Tracks the number of options
    let questionCount = 1; // Tracks the number of questions
    // Function to renumber the options
    function renumberOptions() {
        $('#optionsContainer .optionItem').each(function(index) {
            let optionNumber = index + 1;
            $(this).find('label').attr('for', `option${optionNumber}`).text(`Option ${optionNumber}`);
            $(this).find('textarea').attr({
                id: `option${optionNumber}`,
                placeholder: `Enter option ${optionNumber}`
            });
        });
        optionCount = $('#optionsContainer .optionItem').length;
        updateDropdowns(optionCount);
    }

    // Function to renumber the questions
    function renumberQuestions() {
        $('.questionItem').each(function(index) {
            let questionNumber = index + 1;
            $(this).find('label').attr('for', `questionTitle_${questionNumber}`).text(`Question ${questionNumber}`);
            $(this).find('textarea').attr({
                id: `questionTitle_${questionNumber}`,
                placeholder: `Enter Question ${questionNumber}`
            });
            $(this).find('.answer_label').attr('for', `correctOption_${questionNumber}`).html(`Answer <span class="text-red-500">*</span>`);
        });
        questionCount = $('.questionItem').length;
        optionCount = $('#optionsContainer .optionItem').length;
        updateDropdowns(optionCount);
    }

   function updateDropdowns(commonOptions) {
      console.log(commonOptions); // eg - 3
      // Get the common options from the option items
      $('.question-select').each(function() {
          const $select = $(this);
          const selectedValue = $select.val(); // Store current selection
          $select.empty(); // Clear existing options

         for (let i = 1; i <= optionCount; i++) {
            const optionText = `Option ${i}`;
            $select.append($('<option>', {
                value: i,
                text: optionText
            }));
         }

         // Restore the previously selected option if it exists in the new options
         if ($select.find(`option[value="${selectedValue}"]`).length > 0) {
               $select.val(selectedValue);
         }
      });
   }

    // Add more options
    $('#addMoreOptions').on('click', function() {
        optionCount++;
        const newOption = `
            <div class="optionItem mb-[20px]">
                <label for="option${optionCount}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option ${optionCount}</label>
                <textarea id="option${optionCount}" name="option[]" rows="1" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter option ${optionCount}"></textarea>
                <button type="button" class="removeOption text-red-500 hover:text-red-700">Remove</button>
            </div>`;
        
        $('#optionsContainer').append(newOption);
        
        // Re-initialize summernote for the new option
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

        renumberOptions();
    });

    // Add more questions
    $('#addMoreQuestions').on('click', function() {
        questionCount++;
        const newQuestion = `
            <div class="questionItem mb-[40px] border p-4 rounded-lg" id="question_${questionCount}">
                <div class="mb-[20px]">
                    <label for="questionTitle_${questionCount}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question ${questionCount}<span class="text-red-500">*</span></label>
                    <textarea id="questionTitle_${questionCount}" name="question[]" rows="2" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the question..."></textarea>
                </div>
                <div class="mb-[20px]">
                    <label for="correctOption_${questionCount}" class="answer_label block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Answer <span class="text-red-500">*</span></label>
                    <select id="correctOption_${questionCount}" name="answer[]" required class="question-select w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                    </select>
                </div>
                <button type="button" class="removeQuestion px-[14px] text-sm text-white rounded-md bg-danger border-danger h-10 gap-[6px] transition-[0.3s]">Remove Question</button>
            </div>`;
        
        $('#questionsContainer').append(newQuestion);

        // Re-initialize summernote for the new question
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

        renumberQuestions();
    });

    // Remove a question
    $(document).on('click', '.removeQuestion', function() {
        $(this).closest('.questionItem').remove();
        renumberQuestions();
    });

    // Remove an option
    $(document).on('click', '.removeOption', function() {
        $(this).closest('.optionItem').remove();
        renumberOptions();
    });

});

</script>
@endpush