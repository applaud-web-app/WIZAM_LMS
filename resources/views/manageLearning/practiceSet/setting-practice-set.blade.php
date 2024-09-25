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
                        <a href="{{route('practice-set-detail',['id'=>request()->id])}}" class="flex-1 text-center">
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
                        <a href="{{route('practice-set-setting',['id'=>request()->id])}}" class="flex-1 text-center">
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
                        <a href="{{route('practice-set-question',['id'=>request()->id])}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    3
                                </div>
                                <div class="text-gray-400 mt-2">Questions</div>
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
            <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data" id="practise-test-form">
                @csrf
                <!-- Allow Reward Points -->
                <div class="mb-[20px]">
                    <label for="allow_reward" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Allow Reward Points</label>
                    <select id="allow_reward" name="allow_reward" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$praticeSet->allow_reward == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$praticeSet->allow_reward == 0 ? "selected" : ""}}>No</option>
                    </select>
                </div>
                <!-- Point Mode -->
                <div class="mb-[20px]">
                    <label for="point_mode" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Point Mode</label>
                    <select id="point_mode" name="point_mode" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="automatic" {{$praticeSet->point_mode == "automatic" ? "selected" : ""}}>Automatic</option>
                        <option value="manual" {{$praticeSet->point_mode == "manual" ? "selected" : ""}}>Manual</option>
                    </select>
                </div>
                <div class="mb-[20px] {{$praticeSet->point_mode == "manual" ? "" : "hidden"}}" id="PointBox">
                    <label for="points" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Points for Correct Answer <span class="text-red-500">*</span></label>
                    <input type="text" id="points" name="points" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($praticeSet->points){{$praticeSet->points}}@endisset" placeholder="Enter Point" min="1">
                </div>
                <!-- Show Award Popup -->
                <div class="mb-[20px]">
                    <label for="reward_popup" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Show Award Popup</label>
                    <select id="reward_popup" name="reward_popup" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                        <option value="1" {{$praticeSet->reward_popup == 1 ? "selected" : ""}}>Yes</option>
                        <option value="0" {{$praticeSet->reward_popup == 0 ? "selected" : ""}}>No</option>
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
        $(document).on('change', '#point_mode', function(){
            const value = $(this).val();
            if (value === "manual") {  // Check for lowercase 'manual'
                $('#PointBox').show();  // Show the points input box
            } else {
                $('#PointBox').hide();  // Hide the points input box
            }
        });

        // Trigger change event on page load to handle pre-selected value
        $('#point_mode').trigger('change');

        // jQuery Validation for the form
        $('#practise-test-form').validate({
            rules: {
                allow_reward: {
                    required: true
                },
                point_mode: {
                    required: true
                },
                points: {
                    required: function() {
                        return $('#point_mode').val() === "manual";
                    },
                    number: true,
                    min: 1
                },
                reward_popup: {
                    required: true
                }
            },
            messages: {
                points: {
                    required: "Please enter points for manual mode",
                    number: "Points must be a number",
                    min: "Points must be at least 1"
                }
            }
        });
    });

</script>
@endpush
