@extends('layouts.master')

@section('title', 'Add Exam')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <div class="flex justify-between items-center mb-[30px]">
        <h2><b>{{$examResult->exam->title}} - Score Report</b></h2>
       <div class="ms-2">
        <a href="{{route('detailed-report',[$examResult->exam->id])}}" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Detailed Report</a>
        <a href="{{route('overall-report',[$examResult->exam->id])}}" class="ms-2 capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Overall Report</a>
       </div>
    </div>

    <div class="m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full mt-[30px]">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
            <div class="bg-white border-1 rounded-md px-[30px] py-[20px]">
                <table class="w-full text-left border">
                    <tbody>
                        <tr class="border-b hover:bg-primary hover:text-white">
                            <th class="p-2 text-xl border-e">Test Taker:</th>
                            <td class="text-[18px] ps-[20px] p-2">{{$examResult->user->name}}</td>
                        </tr>
                        <tr class="border-b hover:bg-primary hover:text-white">
                            <th class="p-2 text-xl border-e">Email:</th>
                            <td class="text-[18px] ps-[20px] p-2">{{$examResult->user->email}}</td>
                        </tr>
                        <tr class="border-b hover:bg-primary hover:text-white">
                            <th class="p-2 text-xl border-e">Phone Number:</th>
                            <td class="text-[18px] ps-[20px] p-2">{{$examResult->user->phone_number}}</td>
                        </tr>
                        <tr class="border-b hover:bg-primary hover:text-white">
                            <th class="p-2 text-xl border-e">Completion:</th>
                            <td class="text-[18px] ps-[20px] p-2">{{date('d/m/Y', strtotime($examResult->updated_at)) . ", " . date('H:i:s A', strtotime($examResult->updated_at))}}</td>
                        </tr>
                        <tr class="hover:bg-primary hover:text-white">
                            <th class="p-2 text-xl border-e">Status:</th>
                            @php $statusColor = (float) $examResult->student_percentage >= (float) $examResult->pass_percentage ? 'success' : 'danger';@endphp
                            <td class="text-[18px] ps-[20px] p-2"><span class='hover:text-white capitalize font-medium inline-flex items-center justify-center min-h-[24px]'>{{(float)$examResult->student_percentage >= (float)$examResult->pass_percentage ? "PASS" : "FAIL"}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>  
            @php
                $startTime = strtotime($examResult->created_at);
                $endTime = strtotime($examResult->updated_at);
                $timeTaken = $endTime - $startTime;
    
                // Calculate hours, minutes, and seconds
                $hours = floor($timeTaken / 3600);
                $minutes = floor(($timeTaken % 3600) / 60);
                $seconds = $timeTaken % 60;
            @endphp          
            <div class="">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                    <!-- Grid items -->
                    <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                    <h3 class="mb-2 text-xl">EXAM STATUS</h3>
                    <p class="text-[18px]"><span class='capitalize font-medium inline-flex items-center justify-center min-h-[24px] hover:text-white'>{{(float)$examResult->student_percentage >= (float)$examResult->pass_percentage ? "PASS" : "FAIL"}}</span></p>
                    </div>
                    <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                    <h3 class="mb-2 text-xl">SCORE</h3>
                    <p class="text-[18px]">{{round((float)$examResult->score,2)}}/{{(float)$examResult->point}}</p>
                    </div>
                    <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                    <h3 class="mb-2 text-xl">PERCENTAGE</h3>
                    <p class="text-[18px]">{{round((float)$examResult->student_percentage,2)}}/{{(float)$examResult->pass_percentage}}</p>
                    </div>
                    <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                    <h3 class="mb-2 text-xl">TIME TAKEN</h3>
                    <p class="text-[18px]">{{ $hours }}h {{ $minutes }}m {{ $seconds }}s</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4 mt-[30px]">
            <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
                <div
                    class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto">
                    <h2
                        class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                        Exam Overview
                    </h2>
                    <div class="sm:py-[16px] flex items-center gap-[15px] max-xs:flex-wrap max-xs:justify-center "
                        data-te-dropdown-ref>
                        <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                            id="salesReport" data-te-dropdown-toggle-ref aria-expanded="false">
                            <i class="uil uil-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
                <div class="px-[25px] md:pb-[25px]">
                    <div class="performanceOver2"></div>
                </div>
            </div>
            <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
                <div
                    class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto">
                    <h2
                        class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                        Percentage Overview
                    </h2>
                    <div class="sm:py-[16px] flex items-center gap-[15px] max-xs:flex-wrap max-xs:justify-center "
                        data-te-dropdown-ref>
                        <button class="text-[18px] text-light dark:text-subtitle-dark" type="button"
                            id="salesReport" data-te-dropdown-toggle-ref aria-expanded="false">
                            <i class="uil uil-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
                <div class="px-[25px] md:pb-[25px]">
                    <div class="score-overview-chart"></div>
                </div>
            </div>
        </div>
        <!-- Add Exam Form -->
          <div class="p-[0px] mt-[30px]">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4">
              <!-- Grid items -->
              <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                <h3 class="mb-2 text-xl">Total Question</h3>
                <p class="text-[18px]">{{(float)$examResult->total_question ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                <h3 class="mb-2 text-xl">Correct Question</h3>
                <p class="text-[18px]">{{(float)$examResult->correct_answer ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                <h3 class="mb-2 text-xl">Incorrect Question</h3>
                <p class="text-[18px]">{{(float)$examResult->incorrect_answer ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                <h3 class="mb-2 text-xl">Skipped Question</h3>
                <p class="text-[18px]">{{((float)$examResult->total_question - ((float)$examResult->correct_answer+(float)$examResult->incorrect_answer)) ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                <h3 class="mb-2 text-xl">Attemped Question</h3>
                <p class="text-[18px]">{{((float)$examResult->correct_answer+(float)$examResult->incorrect_answer) ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-sm transition duration-300 ease-in-out hover:bg-primary dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center hover:text-white">
                <h3 class="mb-2 text-xl">Time Taken</h3>
                <p class="text-[18px]">{{ $hours }}h {{ $minutes }}m {{ $seconds }}s</p>
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
    // Function to render the donut chart
    function donutChart2(selector) {
        // Check if the element exists
        if (document.querySelectorAll(selector).length > 0) {
            // Create a new ApexChart
            const options = {
                series: [{{(float)$examResult->correct_answer ?? 0}}, {{(float)$examResult->incorrect_answer ?? 0}}, {{((float)$examResult->total_question - ((float)$examResult->correct_answer+(float)$examResult->incorrect_answer)) ?? 0}}], // Values for the chart
                labels: ["Correct", "Incorrect", "Skipped"], // Labels for each value
                chart: {
                    type: 'donut', // Chart type
                    height: 350 // Chart height
                },
                colors: ["#49eb02", "#ff0000", "#ffd211"], // Custom colors
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            // Initialize and render the chart
            const chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }
    }

    // Call the function after the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        donutChart2(".performanceOver2"); // Pass the CSS selector for the donut chart container
    });
</script>
<script>
    function scoreOverview(selector, score, totalScore) {
        // Ensure the container exists
        if (document.querySelectorAll(selector).length > 0) {
            const options = {
                chart: {
                    type: 'bar', // Type of chart: bar
                    height: 350, // Chart height
                },
                series: [{
                    name: 'Percentage',
                    data: [{{round((float)$examResult->student_percentage,2)}}, {{round((float)$examResult->pass_percentage,2)}}] // Data for the bars
                }],
                xaxis: {
                    categories: ['Percentage', 'Total Percentage'], // Labels for the x-axis
                    title: {
                        text: 'Category'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Points'
                    },
                    min: 0, // Start from 0
                    max: Math.max(score, totalScore) + 10 // Add some padding above the highest value
                },
                colors: ['#00AAFF', '#FA8B0C'], // Bar colors
                dataLabels: {
                    enabled: true // Show values on the bars
                },
                title: {
                    text: 'Score Overview',
                    align: 'center'
                },
            };

            // Render the chart
            const chart = new ApexCharts(document.querySelector(selector), options);
            chart.render();
        }
    }

    // Call the function after the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function () {
        // Example scores: replace with dynamic data as needed
        const actualScore = 85; // Example actual score
        const totalPossibleScore = 100; // Example total score

        // Call the function with the selector, actual score, and total score
        scoreOverview(".score-overview-chart", actualScore, totalPossibleScore);
    });
</script>
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
            errorPlacement: function (error, element) {
                // For radio buttons, place the error after the radio group
                if (element.attr("type") == "radio") {
                    error.insertAfter(element.closest('.mb-[20px]'));
                } else {
                    error.addClass('text-red-500 text-sm');
                    error.insertAfter(element);
                }
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