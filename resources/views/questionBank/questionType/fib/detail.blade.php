@extends('layouts.master')

@section('title', 'Add Fill-in-the-Blank Question')

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

        <div
            class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">

            <!-- Fill-in-the-Blank Question Form -->
            <div class="p-[25px]">
                <form action="{{route('save-fib-details')}}" method="POST" enctype="multipart/form-data">
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
                                        <option value="{{$item->id}}" @isset($question){{$question->skill_id == $item->id ? "selected":""}}@endisset>{{$item->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <!-- Question Title -->
                    <div class="mb-[20px]">
                        <label for="question"
                            class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question <span
                                class="text-red-500">*</span></label>
                        <textarea id="question" name="question" rows="5" required
                            class=" w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                            placeholder="Enter the question. Wrap the word or phrase you want as a blank with ## (e.g., The capital of France is ##Paris##)."></textarea>
                    </div>

                    <!-- Note for Blanks -->
                    <div class="px-[20px] py-[10px] text-[14px] rounded-lg bg-info/10 text-info border-1 border-info/10 mb-3 capitalize"
                        role="alert">
                        <div class="flex items-baseline flex-wrap gap-[8px]">
                            <span>
                                <i class="text-current uil uil-info-circle text-[18px]"></i>
                            </span>
                            <div>
                                <p class="font-normal text-[14px]">Wrap the word or words you wish to make a blank with ##
                                    (e.g., The capital of France is ##Paris##). The system will automatically convert them
                                    to blanks for users to fill in.</p>
                            </div>
                        </div>
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
            height: 300,
          
        });
    });
</script>

@endpush