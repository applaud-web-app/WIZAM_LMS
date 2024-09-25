@extends('layouts.master')

@section('title', 'Add Practice Test')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

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
                                <div class="text-primary mt-2">Details</div>
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
                                <div class="text-gray-400 mt-2">Questions</div>
                            </div>
                        </div>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-gray-300"></div>
                        <!-- Step 3 -->
                        <div class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    4
                                </div>
                                <div class="text-gray-400 mt-2">Schedule</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Card -->
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        
        <!-- Practice Test Form -->
        <div class="p-[25px]">
            <form action="{{route('save-quizzes')}}" method="POST" enctype="multipart/form-data" id="addSet">
                @csrf

                <!-- Test Title -->
                <div class="mb-[20px]">
                    <label for="testTitle" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Title <span class="text-red-500">*</span></label>
                    <input type="text" id="testTitle" name="testTitle" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter test title">
                </div>

                <!-- Sub Category -->
                <div class="mb-[20px]">
                    <label for="subCategory" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Sub Category <span class="text-red-500">*</span></label>
                    <select id="subCategory" name="subCategory" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option selected disabled>Select Sub Category</option>
                        @isset($category)
                            @foreach ($category as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <!-- Skills -->
                <div class="mb-[20px]">
                    <label for="quiz_type" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Quiz Type </label>
                    <select id="quiz_type" name="quiz_type" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option selected disabled>Select Quiz Type</option>
                        @isset($quizType)
                            @foreach ($quizType as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <!-- Free or Paid -->
                <div class="mb-[20px]">
                    <label for="isFee" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Free <span class="text-red-500">*</span></label>
                    <select id="isFee" name="isFee" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" >Yes</option>
                        <option value="0" selected>No</option>
                    </select>
                </div>

                <!-- Test Description -->
                <div class="mb-[20px]">
                    <label for="description" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Description</label>
                    <textarea id="description" name="description" rows="4" class="summernote w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Enter test description"></textarea>
                </div>

                <!-- Status -->
                <div class="mb-[20px]">
                  <label for="visibility" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Visibility <span class="text-red-500">*</span>
                 </label>
                 <div class="flex flex-wrap items-center gap-[15px]">
                     <!--First radio-->
                     <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="visibility" id="visibility" value="1" autocompleted="" >
                        <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="visibility">Public</label>
                     </div>
                     <!--Second radio-->
                     <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="visibility" id="visibility" value="0" autocompleted="" checked>
                        <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="visibility">Private</label>
                     </div>
                  </div>
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
    <script>
        // jQuery Validation for the Add Practice Test form
        $("#addSet").validate({
            rules: {
                testTitle: {
                    required: true,
                    maxlength: 255 // Example: Limiting the title to 255 characters
                },
                subCategory: {
                    required: true,
                },
                isFee: {
                    required: true,
                },
                description: {
                    maxlength: 1000, // Limiting the description to 1000 characters
                },
                visibility: {
                    required: true,
                }
            },
            messages: {
                testTitle: {
                    required: "Please enter a test title.",
                    maxlength: "The title cannot exceed 255 characters."
                },
                subCategory: {
                    required: "Please select a subcategory.",
                },
                isFee: {
                    required: "Please specify if the test is free.",
                },
                description: {
                    maxlength: "Description cannot exceed 1000 characters."
                },
                visibility: {
                    required: "Please select the visibility option.",
                }
            },
            errorPlacement: function (error, element) {
                error.addClass('text-red-500 text-sm');
                error.insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass('border-red-500'); // Highlight fields with error
            },
            unhighlight: function (element) {
                $(element).removeClass('border-red-500'); // Remove highlight when valid
            },
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                form.submit();
            }
        });
    </script>   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script> 
@endpush