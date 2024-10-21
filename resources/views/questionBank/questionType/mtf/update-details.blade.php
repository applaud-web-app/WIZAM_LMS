@extends('layouts.master')

@section('title', 'Add Match the Following Question')

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
      
      <!-- Match the Following Question Form -->
      <div class="p-[25px]">
         <form action="{{route('update-mtf-details',['id'=>$question->id])}}" method="POST" enctype="multipart/form-data">
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
               <textarea id="question" name="question" rows="2" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the question...">@isset($question){{ $question->question }}@endisset</textarea>
            </div>

            <!-- Note for Correct Order of Pairs -->
           
            <div class="px-[20px] py-[10px] text-[14px] rounded-lg bg-info/10 text-info border-1 border-info/10 mb-3 capitalize" role="alert">
                <div class="flex items-baseline flex-wrap gap-[8px]">
                   <span>
                      <i class="text-current uil uil-layer-group text-[18px]"></i>
                   </span>
                   <div>
                      <p class="font-normal text-[14px]">Enter pairs in correct order. Pairs will automatically shuffle while showing to users. </p>
                   </div>
                </div>
             </div>

            @if(isset($question) && isset($question->options))
                <!-- Matching Pairs Section -->
                @php $options = json_decode($question->options,true); @endphp
                @php $answers = json_decode($question->answer,true); @endphp
                <div id="pairsContainer" class="mb-[20px]">
                    @foreach ($options as $item)
                        <div class="pairItem grid grid-cols-2 gap-5 mb-[20px]">
                            <div>
                                <label for="term{{$loop->index+1}}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Term {{$loop->index+1}} <span class="text-red-500">*</span></label>
                                <textarea id="term{{$loop->index+1}}" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the term">{{$item}}</textarea>
                            </div>
                            <div>
                                <label for="definition{{$loop->index+1}}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Definition {{$loop->index+1}} <span class="text-red-500">*</span></label>
                                <textarea id="definition1" name="answer[{{$loop->index+1}}]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the definition" >@isset($answers[$loop->index+1]){{$answers[$loop->index+1]}}@endisset</textarea>
                            </div>
                            @if ($loop->index > 1)
                                <button type="button" class="removePair mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Matching Pairs Section -->
                <div id="pairsContainer" class="mb-[20px]">
                    <div class="pairItem grid grid-cols-2 gap-5 mb-[20px]">
                        <div>
                            <label for="term1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Term 1 <span class="text-red-500">*</span></label>
                            <textarea id="term1" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the term"></textarea>
                        </div>
                        <div>
                            <label for="definition1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Definition 1 <span class="text-red-500">*</span></label>
                            <textarea id="definition1" name="answer[1]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the definition"></textarea>
                        </div>
                    </div>
                    <div class="pairItem grid grid-cols-2 gap-5 mb-[20px]">
                        <div>
                            <label for="term2" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Term 2 <span class="text-red-500">*</span></label>
                            <textarea id="term2" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the term"></textarea>
                        </div>
                        <div>
                            <label for="definition2" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Definition 2 <span class="text-red-500">*</span></label>
                            <textarea id="definition2" name="answer[2]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the definition"></textarea>
                        </div>
                    </div>
                </div>
            @endif


            <!-- Add More Button -->
            <div class="mb-[20px]">
               <button type="button" id="addMorePairs" class="px-[14px] text-sm text-white rounded-md bg-secondary border-secondary h-10 gap-[6px] transition-[0.3s]">Add More</button>
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
        // Initialize Summernote for existing fields
        $('.summernote').summernote({
            height: 100,
          
        });

        // Function to update the term and definition labels in sequence
        function updatePairLabels() {
            $('.pairItem').each(function(index) {
                const pairNumber = index + 1; // Start count from 1
                $(this).find('label[for^="term"]').attr('for', `term${pairNumber}`).text(`Term ${pairNumber}`);
                $(this).find('textarea[id^="term"]').attr('id', `term${pairNumber}`);
                $(this).find('label[for^="definition"]').attr('for', `definition${pairNumber}`).text(`Definition ${pairNumber}`);
                $(this).find('textarea[id^="definition"]').attr('id', `definition${pairNumber}`);
                $(this).find('textarea[id^="definition"]').attr('name', `answer[${pairNumber}]`);
            });
        }

        // Add more pairs dynamically
        $('#addMorePairs').on('click', function() {
            const newPair = `
                <div class="pairItem grid grid-cols-2 gap-5 mb-[20px]">
                    <div>
                        <label for="term" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Term</label>
                        <textarea name="option[]" rows="1" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the term"></textarea>
                    </div>
                    <div>
                        <label for="definition" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Definition</label>
                        <textarea name="answer[]" rows="1" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the definition"></textarea>
                    </div>
                    <button type="button" class="removePair mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
                </div>`;

            $('#pairsContainer').append(newPair);

            // Re-initialize Summernote for new fields (the newly added pair only)
            $('#pairsContainer').find('.summernote').each(function() {
                if (!$(this).data('initialized')) {
                    $(this).summernote({
                        height: 100,
                    
                    }).data('initialized', true);
                }
            });

            updatePairLabels(); // Update labels to maintain sequence
        });

        // Remove pair functionality
        $(document).on('click', '.removePair', function() {
            $(this).closest('.pairItem').remove();
            updatePairLabels(); // Update labels to maintain sequence after removing
        });

        // Initialize pair labels on load
        updatePairLabels();
    });
</script>

@endpush
