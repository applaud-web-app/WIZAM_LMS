@extends('layouts.master')

@section('title', 'Add Order Sequence Question')

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
      
      <!-- Order Sequence Question Form -->
      <div class="p-[25px]">
         <form action="{{route('update-ord-details',['id'=>$question->id])}}" method="POST" enctype="multipart/form-data">
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

            <!-- Note for Correct Order of Sequence -->
            <div class="px-[20px] py-[10px] text-[14px] rounded-lg bg-info/10 text-info border-1 border-info/10 mb-3 capitalize" role="alert">
                <div class="flex items-baseline flex-wrap gap-[8px]">
                   <span>
                      <i class="text-current uil uil-layer-group text-[18px]"></i>
                   </span>
                   <div>
                      <p class="font-normal text-[14px]">Enter items in the correct order. Items will be shuffled while displaying to users.</p>
                   </div>
                </div>
             </div>

             @if(isset($question) && isset($question->options))
                <!-- Matching Pairs Section -->
                @php $options = json_decode($question->options,true); @endphp
                <div id="sequenceContainer" class="mb-[20px]">
                    @foreach ($options as $key => $item)
                        <div class="sequenceItem grid grid-cols-1 gap-5 mb-[20px]">
                            <div>
                                <label for="item{{$key+1}}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Item {{$key+1}} <span class="text-red-500">*</span></label>
                                <textarea id="item{{$key+1}}" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the item {{$key+1}}...">{{$item}}</textarea>
                            </div>
                            @if ($loop->index > 0)
                                <button type="button" class="removeItem mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else 
                <!-- Sequence Items Section -->
                <div id="sequenceContainer" class="mb-[20px]">
                    <div class="sequenceItem grid grid-cols-1 gap-5 mb-[20px]">
                        <div>
                            <label for="item1" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Item 1 <span class="text-red-500">*</span></label>
                            <textarea id="item1" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter the first item..."></textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add More Button -->
            <div class="mb-[20px]">
               <button type="button" id="addMoreItems" class="px-[14px] text-sm text-white rounded-md bg-secondary border-secondary h-10 gap-[6px] transition-[0.3s]">Add More</button>
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
       let itemCount = 1; // Tracks the number of sequence items
 
       // Function to update the sequence of items
       function updateSequenceItems() {
          $('#sequenceContainer .sequenceItem').each(function(index) {
             const newIndex = index + 1;
             $(this).find('label').attr('for', `item${newIndex}`).html(`Item ${newIndex} <span class="text-red-500">*</span>`);
             $(this).find('textarea').attr('id', `item${newIndex}`).attr('placeholder', `Enter item ${newIndex}...`);
          });
       }
 
       // Initialize Summernote on page load
       $('.summernote').summernote({
          height: 100,
          toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
          ]
       });
 
       // Add more items dynamically
       $('#addMoreItems').on('click', function() {
          itemCount++;
          const newItem = `
             <div class="sequenceItem grid grid-cols-1 gap-5 mb-[20px]">
                <div>
                   <label for="item${itemCount}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Item ${itemCount} <span class="text-red-500">*</span></label>
                   <textarea id="item${itemCount}" name="option[]" rows="1" required class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter item ${itemCount}..."></textarea>
                </div>
                <button type="button" class="removeItem mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
             </div>`;
 
          $('#sequenceContainer').append(newItem);
 
          // Re-initialize summernote for new fields
          $('.summernote').summernote({
            height: 100,
                        onpaste: function (e) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');

                        e.preventDefault();

                        setTimeout( function(){
                            document.execCommand( 'insertText', false, bufferText );
                        }, 10 );
                    }
         
          });
 
          updateSequenceItems(); // Update the sequence after adding an item
       });
 
       // Remove item functionality
       $(document).on('click', '.removeItem', function() {
          $(this).closest('.sequenceItem').remove();
          updateSequenceItems(); // Update the sequence after removing an item
       });
    });
 </script>
 
@endpush
