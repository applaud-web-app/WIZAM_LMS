@extends('layouts.master')

@section('title', 'Add PRACTISE')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <div class="flex justify-between items-center mb-[30px]">
        <h2><b>{{$practiceResult->pratice->title}} - Score Report</b></h2>
       <div class="ms-2">
        <a href="{{route('detailed-practice-report',[$practiceResult->pratice->id])}}" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Detailed Report</a>
        <a href="{{route('overall-practice-set-report',[$practiceResult->pratice->id])}}" class="ms-2 capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Overall Report</a>
       </div>
    </div>

    <div class="m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full mt-[30px]">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
            <div class="bg-white border-1 rounded-lg px-[30px] py-[20px] table-responsive">
                <table class="w-full text-left border">
                    <tbody>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Test Taker:</th>
                            <td class="ps-[20px] p-2">{{$practiceResult->user->name}}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Email:</th>
                            <td class="ps-[20px] p-2">{{$practiceResult->user->email}}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Phone Number:</th>
                            <td class="ps-[20px] p-2">{{$practiceResult->user->phone_number}}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Completion:</th>
                            <td class="ps-[20px] p-2">{{date('d/m/Y', strtotime($practiceResult->updated_at)) . ", " . date('H:i:s A', strtotime($practiceResult->updated_at))}}</td>
                        </tr>
                        <tr class="">
                            <th class="p-2 font-bold border-e">Status:</th>
                            @php $statusColor = (float) $practiceResult->student_percentage >= (float) $passPercentage ? 'success' : 'danger';@endphp
                            <td class="ps-[20px] p-2"><span class='capitalize font-medium inline-flex items-center justify-center min-h-[24px] text-{{$statusColor}} '>{{(float)$practiceResult->student_percentage >= (float)$passPercentage ? "PASS" : "FAIL"}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>            
            <div class="">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                    <!-- Grid items -->
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">PRACTISE STATUS</h3>
                    <p><span class=' capitalize font-medium inline-flex items-center justify-center min-h-[24px]  text-{{$statusColor}} '>{{(float)$practiceResult->student_percentage >= (float)$passPercentage ? "PASS" : "FAIL"}}</span></p>
                    </div>
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">PERCENTAGE</h3>
                    <p>{{round((float)$practiceResult->student_percentage,2)}}/{{(float)$passPercentage}}</p>
                    </div>
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">START TIME</h3>
                    <p>{{date('d/m/Y', strtotime($practiceResult->created_at)) . ", " . date('H:i:s A', strtotime($practiceResult->created_at))}}</p>
                    </div>
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">END TIME</h3>
                    <p>{{date('d/m/Y', strtotime($practiceResult->updated_at)) . ", " . date('H:i:s A', strtotime($practiceResult->updated_at))}}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add practice Form -->
          <div class="p-[0px] mt-[30px]">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4">
              <!-- Grid items -->
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Total Question</h3>
                <p>{{(float)$practiceResult->total_question ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Correct Question</h3>
                <p>{{(float)$practiceResult->correct_answer ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Incorrect Question</h3>
                <p>{{(float)$practiceResult->incorrect_answer ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Skipped Question</h3>
                <p>{{((float)$practiceResult->total_question - ((float)$practiceResult->correct_answer+(float)$practiceResult->incorrect_answer)) ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Attemped Question</h3>
                <p>{{((float)$practiceResult->correct_answer+(float)$practiceResult->incorrect_answer) ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Time Taken</h3>
                <p> @php
                    $startTime = strtotime($practiceResult->created_at);
                    $endTime = strtotime($practiceResult->updated_at);
                    $timeTaken = $endTime - $startTime;
        
                    // Calculate hours, minutes, and seconds
                    $hours = floor($timeTaken / 3600);
                    $minutes = floor(($timeTaken % 3600) / 60);
                    $seconds = $timeTaken % 60;
                @endphp
        
                {{ $hours }}h {{ $minutes }}m {{ $seconds }}s</p>
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

        // jQuery Validation for the Add practice form
        $("#addpracticeForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 255
                },
                duration_type: {
                    required: true,
                },
                practice_duration: {
                    required: function(element) {
                        return $("#duration_type").val() == 'practice_wise';
                    },
                    number: true,
                    min: 1,
                },
                sub_category: {
                    required: true,
                },
                practice_type: {
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
                    required: "Please enter an practice title.",
                    maxlength: "The title cannot exceed 255 characters."
                },
                duration_type: {
                    required: "Please select a duration type.",
                },
                practice_duration: {
                    required: "Please enter the practice duration.",
                    number: "Please enter a valid number.",
                    min: "Duration must be at least 1 minute.",
                },
                sub_category: {
                    required: "Please select a subcategory.",
                },
                practice_type: {
                    required: "Please select an practice type.",
                },
                is_free: {
                    required: "Please specify if the practice is free or paid.",
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
                    required: "Please specify if the practice is a favorite.",
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

    // Toggle practice Duration input visibility based on Duration Type selection
    function togglepracticeDurationInput(select) {
        var practiceDurationInput = document.getElementById('practice_duration_input');
        if (select.value === 'practice_wise') {
            practiceDurationInput.style.display = 'block';
            $("#practice_duration").rules("add", {
                required: true,
                number: true,
                min: 1,
                messages: {
                    required: "Please enter the practice duration.",
                    number: "Please enter a valid number.",
                    min: "Duration must be at least 1 minute.",
                }
            });
        } else {
            practiceDurationInput.style.display = 'none';
            $("#practice_duration").rules("remove");
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