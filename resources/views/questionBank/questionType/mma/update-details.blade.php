@extends('layouts.master')

@section('title', 'Add Multiple Answer Question')

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

        <div
            class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">

            <!-- Multiple Answer Question Form -->
            <div class="p-[25px]">
                <form action="{{ route('update-mma-details',['id'=>$question->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Skill Level -->
                    <div class="mb-[20px]">
                        <label for="skill"
                            class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Skill Level <span
                                class="text-red-500">*</span></label>
                        <select id="skill" name="skill" required
                            class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                            <option selected disabled>Select Skill Level</option>
                            @isset($skill)
                                @foreach ($skill as $item)
                                    <option value="{{ $item->id }}"
                                        @isset($question){{ $question->skill_id == $item->id ? 'selected' : '' }}@endisset>
                                        {{ $item->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <!-- Question Title -->
                    <div class="mb-[20px]">
                        <label for="question"
                            class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question <span
                                class="text-red-500">*</span></label>
                        <textarea id="question" name="question" rows="2" required
                            class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                            placeholder="Enter the question...">
@isset($question)
{{ $question->question }}
@endisset
</textarea>
                    </div>

                    @if (isset($question) && isset($question->options))

                        <!-- MCQ Options (Only two options shown by default) -->
                        <div class="mb-[20px]" id="optionsContainer">
                            @php $options = json_decode($question->options,true); @endphp
                            @php $answers = json_decode($question->answer,true); @endphp
                            @foreach ($options as $item)
                                <div class="optionItem mb-[20px]">
                                    <label for="option{{ $loop->index + 1 }}"
                                        class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option
                                        {{ $loop->index + 1 }}
                                        <span class="text-red-500">*</span></label>
                                    <textarea id="option{{ $loop->index + 1 }}" name="option[]" rows="{{ $loop->index + 1 }}" required
                                        class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                                        placeholder="Enter the option {{ $loop->index + 1 }}">{{ $item }}</textarea>
                                    <div class="mt-[10px]">
                                        <input
                                            class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                                            type="checkbox" name="correctOption[]" value="{{ $loop->index + 1 }}"
                                            id="correctOption{{ $loop->index + 1 }}"
                                            @isset($answers) @if (in_array($loop->index + 1, $answers)){{ 'checked' }} @endif @endisset />

                                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer"
                                            for="correctOption1">Correct</label>
                                    </div>
                                    @if ($loop->index > 1)
                                        <button type="button" class="removeOption mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- MCQ Options (Only two options shown by default) -->
                        <div class="mb-[20px]" id="optionsContainer">
                            <div class="optionItem mb-[20px]">
                                <label for="option1"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 1 <span
                                        class="text-red-500">*</span></label>
                                <textarea id="option1" name="option[]" rows="1" required
                                    class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                                    placeholder="Enter the first option"></textarea>
                                <div class="mt-[10px]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                                        type="checkbox" name="correctOption[]" value="1" id="correctOption1" />
                                    <label class="inline-block ps-[0.15rem] hover:cursor-pointer"
                                        for="correctOption1">Correct</label>
                                </div>
                            </div>

                            <div class="optionItem mb-[20px]">
                                <label for="option2"
                                    class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option 2 <span
                                        class="text-red-500">*</span></label>
                                <textarea id="option2" name="option[]" rows="1" required
                                    class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                                    placeholder="Enter the second option"></textarea>
                                <div class="mt-[10px]">
                                    <input
                                        class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                                        type="checkbox" name="correctOption[]" value="2" id="correctOption2" />
                                    <label class="inline-block ps-[0.15rem] hover:cursor-pointer"
                                        for="correctOption2">Correct</label>
                                </div>
                            </div>
                        </div>

                    @endif

                    <!-- Add More Button -->
                    <div class="mb-[20px]">
                        <button type="button" id="addMoreOptions"
                            class="px-[14px] text-sm text-white rounded-md bg-secondary border-secondary h-10 gap-[6px] transition-[0.3s]">Add
                            More</button>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-x-[10px]">
                        <!-- Submit Button with Unicons Icon -->
                        <button type="submit"
                            class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                            <i class="uil uil-check-circle mr-2"></i> <!-- Submit Icon (Unicons) -->
                            Submit
                        </button>

                        <!-- Reset Button with Unicons Icon -->
                        <button type="button"
                            class="capitalize bg-danger/10 border-none text-danger text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
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

            let optionCount = 2; // Tracks the number of options

            // Function to renumber the options
            function renumberOptions() {
                $('#optionsContainer .optionItem').each(function(index) {
                    let optionNumber = index + 1;
                    $(this).find('label').attr('for', `option${optionNumber}`).text(
                        `Option ${optionNumber}`);
                    $(this).find('textarea').attr({
                        id: `option${optionNumber}`,
                        placeholder: `Enter option ${optionNumber}`
                    });
                    $(this).find('input[type="checkbox"]').attr({
                        id: `correctOption${optionNumber}`,
                        value: `${optionNumber}`
                    });
                    $(this).find('label[for^="correctOption"]').attr('for', `correctOption${optionNumber}`);
                });
                optionCount = $('#optionsContainer .optionItem').length;
            }

            // Add more options dynamically
            $('#addMoreOptions').on('click', function() {
                optionCount++;
                const newOption = `
                 <div class="optionItem mb-[20px]">
                    <label for="option${optionCount}" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Option ${optionCount}</label>
                    <textarea id="option${optionCount}" name="option[]" rows="1" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter option ${optionCount}"></textarea>
                    <div class="mt-[10px]">
                       <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="correctOption[]" value="${optionCount}" id="correctOption${optionCount}" />
                       <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="correctOption${optionCount}">Correct</label>
                    </div>
                    <button type="button" class="removeOption mt-[5px] px-[10px] text-sm text-white rounded-md bg-danger border-danger h-8 gap-[6px] transition-[0.3s]">Remove</button>
                 </div>`;

                $('#optionsContainer').append(newOption);

                // Re-initialize summernote for new fields
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

                // Renumber options after adding
                renumberOptions();
            });

            // Remove option functionality
            $(document).on('click', '.removeOption', function() {
                $(this).closest('.optionItem').remove();
                renumberOptions();
            });
        });
    </script>
@endpush
