@extends('layouts.master')

@section('title', 'Practise Test Setting')

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
                        <a href="{{route('quizzes-detail',['id'=>$quizSetting->id])}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    1
                                </div>
                                <div class="text-gray-400 mt-2">Details</div>
                            </div>
                        </a>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-gray-300"></div>
                        <!-- Step 2 -->
                        <a href="{{route('quizzes-setting',['id'=>$quizSetting->id])}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    2
                                </div>
                                <div class="text-primary mt-2">Settings</div>
                                
                            </div>
                        </a>
                        <!-- Divider -->
                       
                        <div class="w-[40px] h-[2px] bg-primary"></div>
                        
                        <!-- Step 3 -->
                        <a href="{{route('quizzes-question',['id'=>$quizSetting->id])}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    3
                                </div>
                                <div class="text-gray-400 mt-2">Questions</div>
                            </div>
                        </a>
                        <!-- Divider -->
                       
                        <div class="w-[40px] h-[2px] bg-primary"></div>
                        
                        <!-- Step 3 -->
                        <a href="{{route('quizzes-schedules',['id'=>$quizSetting->id])}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    4
                                </div>
                                <div class="text-gray-400 mt-2">Schedule</div>
                            </div>
                        </a>
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
            <form action="{{route('update-quizzes-setting',['id'=>$quizSetting->id])}}" method="POST" enctype="multipart/form-data" id="practise-test-form">
                @csrf
                <!-- Duration Mode -->
                <div class="mb-[20px]">
                    <label for="duration_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Duration Mode</label>
                    <select id="duration_mode" name="duration_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="automatic" {{$quizSetting->duration_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                        <option value="manual" {{$quizSetting->duration_mode == "manual" ? "selected" : ""}}>Manual</option>
                    </select>
                </div>
                <div class="mb-[20px] {{$quizSetting->point_mode == "manual" ? "" : "hidden"}}" id="durationBox">
                    <label for="duration" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Duration (Minutes) <span class="text-red-500">*</span></label>
                    <input type="text" id="duration" name="duration" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($quizSetting->duration){{$quizSetting->duration}}@endisset" placeholder="Enter Duration" min="1">
                </div>
                <!-- Point Mode -->
                <div class="mb-[20px]">
                    <label for="point_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Marks/Points Mode</label>
                    <select id="point_mode" name="point_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="automatic" {{$quizSetting->point_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                        <option value="manual" {{$quizSetting->point_mode == "manual" ? "selected" : ""}}>Manual</option>
                    </select>
                </div>
                <div class="mb-[20px] {{$quizSetting->point_mode == "manual" ? "" : "hidden"}}" id="PointBox">
                    <label for="points" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Marks for Correct Answer <span class="text-red-500">*</span></label>
                    <input type="text" id="points" name="points" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($quizSetting->point){{$quizSetting->point}}@endisset" placeholder="Enter Point" min="1">
                </div>
                <!-- Show Award Popup -->
                <div class="mb-[20px]">
                    <label for="negative_marking" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marking</label>
                    <select id="negative_marking" name="negative_marking" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->negative_marking == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->negative_marking == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>
                <div class="mb-[20px] {{$quizSetting->negative_marking == "1" ? "" : "hidden"}}" id="negativeMarkingBox">
                     <!-- Allow Reward Points -->
                    <div class="mb-[20px]">
                        <label for="negative_marking_type" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marking Type</label>
                        <select id="negative_marking_type" name="negative_marking_type" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                            <option value="fixed" {{$quizSetting->negative_marking_type == "fixed" ? "checked" : ""}}>Fixed</option>
                            <option value="percentage" {{$quizSetting->negative_marking_type == "percentage" ? "checked" : ""}}>Percentage</option>
                        </select>
                    </div>
                    
                    <label for="negative_marks" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marks <span class="text-red-500">*</span></label>
                    <input type="text" id="negative_marks" name="negative_marks" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($quizSetting->negative_marks){{$quizSetting->negative_marks}}@endisset" placeholder="Enter Negative Marks" min="1">
                </div>

                <div class="mb-[20px]">
                    <label for="pass_percentage" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Pass Percentage (%)<span class="text-red-500">*</span></label>
                    <input type="number" id="pass_percentage" name="pass_percentage" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($quizSetting->pass_percentage){{$quizSetting->pass_percentage}}@endisset" placeholder="Enter Pass Percentage">
                </div>

                <div class="mb-[20px]">
                    <label for="shuffle_questions" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Shuffle Questions</label>
                    <select id="shuffle_questions" name="shuffle_questions" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->shuffle_questions == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->shuffle_questions == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>
                <div class="mb-[20px]">
                    <label for="restrict_attempts" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Restrict Attempts</label>
                    <select id="restrict_attempts" name="restrict_attempts" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->restrict_attempts == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->restrict_attempts == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>
                <div class="mb-[20px] {{$quizSetting->restrict_attempts == "1" ? "" : "hidden"}}" id="restrictAttemptsBox">
                    <label for="total_attempts" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Number of Attempts<span class="text-red-500">*</span></label>
                    <input type="number" id="total_attempts" name="total_attempts" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($quizSetting->total_attempts){{$quizSetting->total_attempts}}@endisset" placeholder="Enter Number of Attempts">
                </div>
                
                <div class="mb-[20px]">
                    <label for="disable_finish_button" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Disable Finish Button</label>
                    <select id="disable_finish_button" name="disable_finish_button" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->disable_finish_button == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->disable_finish_button == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>

                <div class="mb-[20px]">
                    <label for="question_view" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Enable Question List View</label>
                    <select id="question_view" name="question_view" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->question_view == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->question_view == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>

                <div class="mb-[20px]">
                    <label for="hide_solutions" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Hide Solutions</label>
                    <select id="hide_solutions" name="hide_solutions" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->hide_solutions == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->hide_solutions == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>

                <div class="mb-[20px]">
                    <label for="leaderboard" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Show Leaderboard</label>
                    <select id="leaderboard" name="leaderboard" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$quizSetting->leaderboard == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$quizSetting->leaderboard == 0 ? "selected" : ""}}>No</option>
                    </select>
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
   $(document).ready(function(){

        // FOR DURATION
        // $(document).on('change', '#duration_mode', function(){
        //     const value = $(this).val();
        //     if (value === "manual") {  // Check for lowercase 'manual'
        //         $('#durationBox').show();  // Show the points input box
        //     } else {
        //         $('#durationBox').hide();  // Hide the points input box
        //     }
        // });

        // // Trigger change event on page load to handle pre-selected value
        // $('#duration_mode').trigger('change');

        // // MARKS POINTS
        // $(document).on('change', '#point_mode', function(){
        //     const value = $(this).val();
        //     if (value === "manual") {  // Check for lowercase 'manual'
        //         $('#PointBox').show();  // Show the points input box
        //     } else {
        //         $('#PointBox').hide();  // Hide the points input box
        //     }
        // });
        // // Trigger change event on page load to handle pre-selected value
        // $('#point_mode').trigger('change');

        // // NEGATIVE MARKS POINTS
        // $(document).on('change', '#negative_marking', function(){
        //     const value = $(this).val();
        //     if (value === "1") {  // Check for lowercase 'manual'
        //         $('#negativeMarkingBox').show();  // Show the points input box
        //     } else {
        //         $('#negativeMarkingBox').hide();  // Hide the points input box
        //     }
        // });
        // // Trigger change event on page load to handle pre-selected value
        // $('#negative_marking').trigger('change');

        // // NEGATIVE MARKS POINTS
        // $(document).on('change', '#restrict_attempts', function(){
        //     const value = $(this).val();
        //     if (value === "1") {  // Check for lowercase 'manual'
        //         $('#restrictAttemptsBox').show();  // Show the points input box
        //     } else {
        //         $('#restrictAttemptsBox').hide();  // Hide the points input box
        //     }
        // });
        // // Trigger change event on page load to handle pre-selected value
        // $('#restrict_attempts').trigger('change');

        // Form validation rules
        $("#practise-test-form").validate({
            rules: {
                duration: {
                    required: function() {
                        return $("#duration_mode").val() == "manual";
                    },
                    number: true,
                    min: 1
                },
                points: {
                    required: function() {
                        return $("#point_mode").val() == "manual";
                    },
                    number: true,
                    min: 1
                },
                negative_marks: {
                    required: function() {
                        return $("#negative_marking").val() == "1";
                    },
                    number: true,
                    min: 0
                },
                pass_percentage: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                },
                total_attempts: {
                    required: function() {
                        return $("#restrict_attempts").val() == "1";
                    },
                    number: true,
                    min: 1
                }
            },
            messages: {
                duration: {
                    required: "Please enter a duration",
                    number: "Please enter a valid number",
                    min: "Duration must be at least 1 minute"
                },
                points: {
                    required: "Please enter points for a correct answer",
                    number: "Please enter a valid number",
                    min: "Points must be at least 1"
                },
                negative_marks: {
                    required: "Please enter negative marks",
                    number: "Please enter a valid number",
                    min: "Negative marks must be 0 or higher"
                },
                pass_percentage: {
                    required: "Please enter the pass percentage",
                    number: "Please enter a valid percentage",
                    min: "Pass percentage must be at least 0",
                    max: "Pass percentage cannot exceed 100"
                },
                total_attempts: {
                    required: "Please enter the number of attempts",
                    number: "Please enter a valid number",
                    min: "Attempts must be at least 1"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        // Dynamic visibility for conditional fields
        $("#duration_mode").change(function() {
            if ($(this).val() === "manual") {
                $("#durationBox").removeClass("hidden");
            } else {
                $("#durationBox").addClass("hidden");
            }
        });

        $("#point_mode").change(function() {
            if ($(this).val() === "manual") {
                $("#PointBox").removeClass("hidden");
            } else {
                $("#PointBox").addClass("hidden");
            }
        });

        $("#negative_marking").change(function() {
            if ($(this).val() === "1") {
                $("#negativeMarkingBox").removeClass("hidden");
            } else {
                $("#negativeMarkingBox").addClass("hidden");
            }
        });

        $("#restrict_attempts").change(function() {
            if ($(this).val() === "1") {
                $("#restrictAttemptsBox").removeClass("hidden");
            } else {
                $("#restrictAttemptsBox").addClass("hidden");
            }
        });
    });
</script>
@endpush
