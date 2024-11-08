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
            <div class="bg-white border-1 rounded-lg px-[30px] py-[20px]">
                <table class="w-full text-left border">
                    <tbody>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Test Taker:</th>
                            <td class="ps-[20px] p-2">{{$examResult->user->name}}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Email:</th>
                            <td class="ps-[20px] p-2">{{$examResult->user->email}}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Phone Number:</th>
                            <td class="ps-[20px] p-2">{{$examResult->user->phone_number}}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="p-2 font-bold border-e">Completion:</th>
                            <td class="ps-[20px] p-2">{{date('d/m/Y', strtotime($examResult->updated_at)) . ", " . date('H:i:s A', strtotime($examResult->updated_at))}}</td>
                        </tr>
                        <tr class="">
                            <th class="p-2 font-bold border-e">Status:</th>
                            @php $statusColor = $examResult->student_percentage >= $examResult->pass_percentage ? 'success' : 'danger';@endphp
                            <td class="ps-[20px] p-2"><span class='capitalize font-medium inline-flex items-center justify-center min-h-[24px] text-{{$statusColor}} '>{{$examResult->student_percentage >= $examResult->pass_percentage ? "PASS" : "FAIL"}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>            
            <div class="">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                    <!-- Grid items -->
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">EXAM STATUS</h3>
                    <p><span class=' capitalize font-medium inline-flex items-center justify-center min-h-[24px]  text-{{$statusColor}} '>{{$examResult->student_percentage >= $examResult->pass_percentage ? "PASS" : "FAIL"}}</span></p>
                    </div>
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">PERCENTAGE</h3>
                    <p>{{$examResult->student_percentage}}/{{$examResult->pass_percentage}}</p>
                    </div>
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">START TIME</h3>
                    <p>{{date('d/m/Y', strtotime($examResult->start_time)) . ", " . date('H:i:s A', strtotime($examResult->start_time))}}</p>
                    </div>
                    <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                    <h3 class="mb-2">END TIME</h3>
                    <p>{{date('d/m/Y', strtotime($examResult->updated_at)) . ", " . date('H:i:s A', strtotime($examResult->updated_at))}}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Exam Form -->
          <div class="p-[0px] mt-[30px]">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4">
              <!-- Grid items -->
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Total Question</h3>
                <p>{{$examResult->total_question ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Correct Question</h3>
                <p>{{$examResult->correct_answer ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Incorrect Question</h3>
                <p>{{$examResult->incorrect_answer ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Skipped Question</h3>
                <p>{{($examResult->total_question - ($examResult->correct_answer+$examResult->incorrect_answer)) ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Attemped Question</h3>
                <p>{{($examResult->correct_answer+$examResult->incorrect_answer) ?? 0}}</p>
              </div>
              <div class="bg-white border-1 rounded-lg transition duration-300 ease-in-out hover:bg-green-500 dark:bg-gray-700 dark:hover:bg-green-600 py-[30px] px-[15px] text-center">
                <h3 class="mb-2">Time Taken</h3>
                <p> @php
                    $startTime = strtotime($examResult->start_time);
                    $endTime = strtotime($examResult->updated_at);
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
<button type="button" class="hidden inline-block rounded bg-primary px-[20px] py-[8px] text-[14px] font-semibold  capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-toggle="modal" data-te-target="#exampleModal" data-te-ripple-init data-te-ripple-color="light">
    Open Modal
</button>
 <!-- Delete Modal -->
 <div data-te-modal-init class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div data-te-modal-dialog-ref class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
       <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
          <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
             <!--Modal title-->
             <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="exampleModalLabel">
                Do you Want to delete these items?
             </h5>
             <!--Close button-->
             <button type="button" class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
             </button>
          </div>
          <!--Modal body-->
          <div class="relative flex-auto p-4" data-te-modal-body-ref>
             <p class="mb-3 text-breadcrumbs dark:text-subtitle-dark">This action cannot be undone. Click "Confirm" to proceed or "Cancel" to abort.</p>
          </div>
          <!--Modal footer-->
          <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
            <button type="button" class="ml-1 inline-block rounded bg-section px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-dark  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
               Cancel
            </button>
             <button type="button" class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-ripple-init data-te-ripple-color="light" data-url="" id="confirmDelete">
                Confirm
             </button>
          </div>
       </div>
    </div>
 </div>
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
<script>
       $(document).ready(function(){
            // When delete item is clicked, store the URL in the confirm button
            $(document).on('click', '.deleteItem', function(){
                const delUrl = $(this).data('url');
                console.log(delUrl);
                $('#confirmDelete').data('url', delUrl); // Use data method to set the URL
            });
    
            // When confirm delete is clicked, redirect to the URL
            $(document).on('click', '#confirmDelete', function(){
                const delUrl = $(this).data('url'); // Use data method to get the URL
                window.location.href = delUrl;
            });
        });
</script>
@endpush