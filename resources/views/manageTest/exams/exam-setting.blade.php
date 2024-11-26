@extends('layouts.master')

@section('title', 'Add Exam Settings')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
          <!-- Stepper Section with Card -->
          <div class="mb-[30px]">
              <!-- Card Container -->
              <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                  <div class="flex items-center justify-between">
                      <!-- Step 1 -->
                        <a href="{{route('exam-detail',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  1
                              </div>
                              <div class="text-primary mt-2">Details</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-primary"></div>
                      <!-- Step 2 (Active) -->
                      <a href="{{route('exam-setting',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                  2
                              </div>
                              <div class="text-primary mt-2">Settings</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 3 -->
                      <a href="{{route('exam-section',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  3
                              </div>
                              <div class="text-gray-400 mt-2">Sections</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 4 -->
                       <a href="{{route('exam-questions',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  4
                              </div>
                              <div class="text-gray-400 mt-2">Questions</div>
                          </div>
                        </a>
                      <!-- Divider -->
                      <div class="w-[40px] h-[2px] bg-gray-300"></div>
                      <!-- Step 5 -->
                      <a href="{{route('exam-schedules',['id'=>$examSetting->id])}}" class="flex-1 text-center">
                          <div class="relative flex flex-col items-center">
                              <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                  5
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

   <!-- Settings Form -->
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="p-[25px]">
         <form action="{{route('update-exam-setting',['id'=>$examSetting->id])}}" method="POST" autocomplete="off" id="form">
            @csrf
            <div class="grid grid-cols-12 gap-5">
              <!-- Duration Mode -->
              {{-- <div class="col-span-12 md:col-span-6">
                <label for="duration_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Duration Mode</label>
                <select id="duration_mode" name="duration_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="automatic" {{$examSetting->duration_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                    <option value="manual" {{$examSetting->duration_mode == "manual" ? "selected" : ""}}>Manual</option>
                </select>
              </div> --}}

              <!-- Duration Mode -->
              <div class="col-span-12 md:col-span-6 mb-[20px]">
                <label for="duration_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Duration Mode <span class="text-red-500">*</span></label>
                <select id="duration_mode" name="duration_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option value="automatic" {{$examSetting->duration_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                    <option value="manual" {{$examSetting->duration_mode == "manual" ? "selected" : ""}}>Manual</option>
                </select>
            </div>
            <div class="col-span-12 md:col-span-6 mb-[20px] {{$examSetting->duration_mode == "manual" ? "" : "hidden"}}" id="durationBox">
                <label for="duration" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Duration (Minutes) <span class="text-red-500">*</span></label>
                <input type="text" id="duration" name="duration" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($examSetting->exam_duration){{$examSetting->exam_duration}}@endisset" placeholder="Enter Duration" min="1" required>
            </div>

              <!-- Marks/Points Mode -->
              {{-- <div class="col-span-12 md:col-span-6">
                <label for="point_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Marks/Points Mode</label>
                <select id="point_mode" name="point_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="automatic" {{$examSetting->point_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                    <option value="manual" {{$examSetting->point_mode == "manual" ? "selected" : ""}}>Manual</option>
                </select>
              </div> --}}


              <div class="col-span-12 md:col-span-6 mb-[20px]">
                <label for="point_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Marks/Points Mode</label>
                <select id="point_mode" name="point_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option value="automatic" {{$examSetting->point_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                    <option value="manual" {{$examSetting->point_mode == "manual" ? "selected" : ""}}>Manual</option>
                </select>
            </div>
            <div class="col-span-12 md:col-span-6 mb-[20px] {{$examSetting->point_mode == "manual" ? "" : "hidden"}}" id="PointBox">
                <label for="points" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Marks for Correct Answer <span class="text-red-500">*</span></label>
                <input type="text" id="points" name="points" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($examSetting->point){{$examSetting->point}}@endisset" placeholder="Enter Point" min="1" required>
            </div>

              <!-- Negative Marking -->
              {{-- <div class="col-span-12 md:col-span-6">
                <label for="negative_marking" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marking</label>
                <select id="negative_marking" name="negative_marking" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->negative_marking == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->negative_marking == 0 ? "selected" : ""}}>No</option>
                </select>
              </div> --}}

              <div class="col-span-12 md:col-span-6 mb-[20px]">
                <label for="negative_marking" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marking</label>
                <select id="negative_marking" name="negative_marking" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option value="1" {{$examSetting->negative_marking == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->negative_marking == 0 ? "selected" : ""}}>No</option>
                </select>
            </div>
            <div class="col-span-12 md:col-span-6 mb-[20px] {{$examSetting->negative_marking == "1" ? "" : "hidden"}}" id="negativeMarkingBox">
                 <!-- Allow Reward Points -->
                <div class="mb-[20px]">
                    <label for="negative_marking_type" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marking Type</label>
                    <select id="negative_marking_type" name="negative_marking_type" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="fixed" {{$examSetting->negative_marking_type == "fixed" ? "selected" : ""}}>Fixed</option>
                        <option value="percentage" {{$examSetting->negative_marking_type == "percentage" ? "selected" : ""}}>Percentage</option>
                    </select>
                </div>
                
                <label for="negative_marks" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Negative Marks <span class="text-red-500">*</span></label>
                <input type="text" id="negative_marks" name="negative_marks" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($examSetting->negative_marks){{$examSetting->negative_marks}}@endisset" placeholder="Enter Negative Marks" min="1" required>
            </div>

              <!-- Overall Pass Percentage -->
              <div class="col-span-12 md:col-span-6">
                <label for="pass_percentage" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Overall Pass Percentage <span class="text-red-500">*</span></label>
                <input id="pass_percentage" name="pass_percentage" type="number" value="@isset($examSetting->pass_percentage){{$examSetting->pass_percentage}}@endisset" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]" placeholder="Enter pass percentage"/>
              </div>

              <!-- Enable Section Cutoff/Percentage -->
              <div class="col-span-12 md:col-span-6">
                <label for="cutoff" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Enable Section Cutoff/Percentage</label>
                <select id="cutoff" name="cutoff" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->cutoff == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->cutoff == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Shuffle Questions -->
              <div class="col-span-12 md:col-span-6">
                <label for="shuffle_questions" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Shuffle Questions</label>
                <select id="shuffle_questions" name="shuffle_questions" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->shuffle_questions == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->shuffle_questions == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Restrict Attempts -->
              <div class="col-span-12 md:col-span-6">
                <label for="restrict_attempts" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Restrict Attempts</label>
                <select id="restrict_attempts" name="restrict_attempts" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]" onchange="toggleAttemptsInput(this)">
                    <option value="1" {{$examSetting->restrict_attempts == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->restrict_attempts == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Number of Attempts (Only visible if "Yes" is selected) -->
              <div id="attempts_input" class="col-span-12 md:col-span-6 {{$examSetting->restrict_attempts == "1" ? "" : "hidden"}}">
                <label for="total_attempts" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Number of Attempts <span class="text-danger">*</span></label>
                <input id="total_attempts" name="total_attempts" type="number" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]" value="@if($examSetting->restrict_attempts == 1){{$examSetting->total_attempts}}@endif" placeholder="Enter number of attempts" />
              </div>

              <!-- Disable Section Navigation -->
              <div class="col-span-12 md:col-span-6">
                <label for="disable_navigation" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Disable Section Navigation</label>
                <select id="disable_navigation" name="disable_navigation" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->disable_navigation == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->disable_navigation == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Disable Finish Button -->
              <div class="col-span-12 md:col-span-6">
                <label for="disable_finish_button" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Disable Finish Button</label>
                <select id="disable_finish_button" name="disable_finish_button" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->disable_finish_button == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->disable_finish_button == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Enable Question List View -->
              <div class="col-span-12 md:col-span-6">
                <label for="question_view" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Enable Question List View</label>
                <select id="question_view" name="question_view" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->question_view == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->question_view == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Hide Solutions -->
              <div class="col-span-12 md:col-span-6">
                <label for="hide_solutions" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Hide Solutions</label>
                <select id="hide_solutions" name="hide_solutions" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->hide_solutions == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->hide_solutions == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Show Leaderboard -->
              <div class="col-span-12 md:col-span-6">
                <label for="leaderboard" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Show Leaderboard</label>
                <select id="leaderboard" name="leaderboard" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px]">
                    <option value="1" {{$examSetting->leaderboard == 1 ? "selected" : ""}}>Yes</option>
                    <option value="0" {{$examSetting->leaderboard == 0 ? "selected" : ""}}>No</option>
                </select>
              </div>

              <!-- Submit Button -->
              <div class="col-span-12 mb-[20px]">
                <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                  Submit
                </button>
              </div>
            </div>
         </form>
      </div>
   </div>
</section>

@endsection
@push('scripts')
<script>
    // Toggle visibility of number of attempts input
    function toggleAttemptsInput(select) {
        const attemptsInput = document.getElementById('attempts_input');
        attemptsInput.style.display = select.value === "1" ? 'block' : 'none';
    }

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

    $(document).ready(function() {
        $("#form").validate({
            rules: {
                duration_mode: {
                    required: true
                },
                marks_mode: {
                    required: true
                },
                negative_marking: {
                    required: true
                },
                pass_percentage: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 100
                },
                section_cutoff: {
                    required: true
                },
                shuffle_questions: {
                    required: true
                },
                restrict_attempts: {
                    required: true
                },
                num_attempts: {
                    required: {
                        depends: function(element) {
                            return $("#restrict_attempts").val() === "1";
                        }
                    },
                    number: true,
                    min: 1
                },
                disable_navigation: {
                    required: true
                },
                disable_finish: {
                    required: true
                },
                question_list_view: {
                    required: true
                },
                hide_solutions: {
                    required: true
                },
                show_leaderboard: {
                    required: true
                }
            },
            messages: {
                pass_percentage: {
                    required: "Pass percentage is required.",
                    number: "Please enter a valid number.",
                    min: "Percentage must be at least 0.",
                    max: "Percentage must not exceed 100."
                },
                num_attempts: {
                    required: "Number of attempts is required.",
                    number: "Please enter a valid number.",
                    min: "Number of attempts must be at least 1."
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>

  
@endpush