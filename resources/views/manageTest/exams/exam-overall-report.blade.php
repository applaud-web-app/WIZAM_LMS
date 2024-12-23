@extends('layouts.master')

@section('title', 'Add Exam')

@section('content')

    <section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

        <div class="flex justify-between items-center mb-[30px]">
            <h2><b>{{ $exam->title }} - Overall Report</b></h2>
            <a href="{{ route('detailed-report', [$exam->id]) }}"
                class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Detailed
                Report</a>
        </div>

        <div class="m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <!-- Add Exam Form -->
            <div class="p-[0px]">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4">
                    <!-- Grid items -->
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Total Attempts</h3>
                        <p class="text-[18px]">{{ $totalAttempt ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Pass Attempts</h3>
                        <p class="text-[18px]">{{ $passedExam ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Fail Attempts</h3>
                        <p class="text-[18px]">{{ $failedExam ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Avg. Score</h3>
                        <p class="text-[18px]">{{ round($averageScore, 2) ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Highest Score</h3>
                        <p class="text-[18px]">{{ round($highestScore, 2) ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Lowest Score</h3>
                        <p class="text-[18px]">{{ round($lowestScore, 2) ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Avg. Percentage</h3>
                        <p class="text-[18px]">{{ round($averagePercentage, 2) ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Highest Percentage</h3>
                        <p class="text-[18px]">{{ round($highestPercentage, 2) ?? 0 }}</p>
                    </div>
                    <div
                        class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                        <h3 class="mb-2 text-xl">Lowest Percentage</h3>
                        <p class="text-[18px]">{{ round($lowestPercentage, 2) ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150,

            });

            // jQuery Validation for the Add Exam form
            $("#addExamForm").validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    duration_type: {
                        required: true,
                    },
                    exam_duration: {
                        required: function(element) {
                            return $("#duration_type").val() == 'exam_wise';
                        },
                        number: true,
                        min: 1,
                    },
                    sub_category: {
                        required: true,
                    },
                    exam_type: {
                        required: true,
                    },
                    is_free: {
                        required: true,
                    },
                    price: {
                        required: function(element) {
                            return $("input[name='is_free']:checked").val() == '0';
                        },
                        number: true,
                        min: 0,
                    },
                    download_report: {
                        required: true,
                    },
                    description: {
                        maxlength: 1000,
                    },
                    visibility: {
                        required: true,
                    },
                    favorite: {
                        required: true,
                    }
                },
                messages: {
                    title: {
                        required: "Please enter an exam title.",
                        maxlength: "The title cannot exceed 255 characters."
                    },
                    duration_type: {
                        required: "Please select a duration type.",
                    },
                    exam_duration: {
                        required: "Please enter the exam duration.",
                        number: "Please enter a valid number.",
                        min: "Duration must be at least 1 minute.",
                    },
                    sub_category: {
                        required: "Please select a subcategory.",
                    },
                    exam_type: {
                        required: "Please select an exam type.",
                    },
                    is_free: {
                        required: "Please specify if the exam is free or paid.",
                    },
                    price: {
                        required: "Please enter a price.",
                        number: "Please enter a valid price.",
                        min: "Price cannot be negative.",
                    },
                    download_report: {
                        required: "Please specify if downloading reports is allowed.",
                    },
                    description: {
                        maxlength: "Description cannot exceed 1000 characters."
                    },
                    visibility: {
                        required: "Please select the visibility option.",
                    },
                    favorite: {
                        required: "Please specify if the exam is a favorite.",
                    }
                },
                errorPlacement: function(error, element) {
                    // For radio buttons, place the error after the radio group
                    if (element.attr("type") == "radio") {
                        error.insertAfter(element.closest('.mb-[20px]'));
                    } else {
                        error.addClass('text-red-500 text-sm');
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('border-red-500'); // Highlight fields with error
                },
                unhighlight: function(element) {
                    $(element).removeClass('border-red-500'); // Remove highlight when valid
                },
                submitHandler: function(form) {
                    $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                    form.submit();
                }
            });
        });

        // Toggle Exam Duration input visibility based on Duration Type selection
        function toggleExamDurationInput(select) {
            var examDurationInput = document.getElementById('exam_duration_input');
            if (select.value === 'exam_wise') {
                examDurationInput.style.display = 'block';
                $("#exam_duration").rules("add", {
                    required: true,
                    number: true,
                    min: 1,
                    messages: {
                        required: "Please enter the exam duration.",
                        number: "Please enter a valid number.",
                        min: "Duration must be at least 1 minute.",
                    }
                });
            } else {
                examDurationInput.style.display = 'none';
                $("#exam_duration").rules("remove");
            }
        }

        // Show/hide price input based on Paid or Free selection
        function togglePriceInput(isPaid) {
            const priceInput = document.getElementById('price_input');
            if (isPaid) {
                priceInput.style.display = 'block';
                $("#price").rules("add", {
                    required: true,
                    number: true,
                    min: 0,
                    messages: {
                        required: "Please enter a price.",
                        number: "Please enter a valid price.",
                        min: "Price cannot be negative.",
                    }
                });
            } else {
                priceInput.style.display = 'none';
                $("#price").rules("remove");
            }
        }
    </script>
@endpush
