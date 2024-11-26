@extends('layouts.master')

@section('title', 'Add Practice Question')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <!-- Stepper Section -->
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <!-- Stepper Section with Card -->
            <div class="mb-[30px]">
                <!-- Card Container -->
                <div class="bg-white dark:bg-gray-800 rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <!-- Step 1 -->
                        <a href="{{route('configure-videos')}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    1
                                </div>
                                <div class="text-gray-400 mt-2">Choose SKill</div>
                            </div>
                        </a>
                        <!-- Divider -->
                        <div class="w-[40px] h-[2px] bg-primary"></div>
                        <!-- Step 2 -->
                        <a href="{{url()->full()}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    2
                                </div>
                                <div class="text-primary mt-2">Add/Remove Video</div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- End of Card -->
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg p-5 mb-[30px]">
        <form class="grid grid-cols-12 gap-5" id="filterForm">
            @csrf
            <!-- Topic Filter -->
            <div class="col-span-12 sm:col-span-6 md:col-span-6">
                <label for="topic" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Topic</label>
                <select id="topic" name="topic" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option selected disabled>Select Topic</option>
                    @isset($topic)
                        @foreach ($topic as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>

            <!-- tags Filter -->
            <div class="col-span-12 sm:col-span-6 md:col-span-6">
                <label for="tags" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Tags</label>
                <select id="tags" name="tags" class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                    <option selected disabled>Select Tags</option>
                    @isset($tags)
                        @foreach ($tags as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
            <input type="hidden" name="skill_id" value="{{$skill->id}}" id="skillId">
            <input type="hidden" name="subcategory_id" value="{{$subcategory->id}}" id="catgegotyId">

            <!-- Difficulty Level Checkbox with Custom Style -->
            <div class="col-span-12 sm:col-span-6 md:col-span-6">
                <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Difficulty Level</label>
                <div class="flex flex-col">
                    <div class="mb-[0.125rem] block min-h-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="difficultyLevel[]" value="very_easy" id="difficultyLevel1" autocompleted="">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="difficultyLevel1">Very Easy</label>
                    </div>
                    <div class="mb-[0.125rem] block min-h-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="difficultyLevel[]" value="easy" id="difficultyLevel2" autocompleted="">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="difficultyLevel2">Easy</label>
                    </div>
                    <div class="mb-[0.125rem] block min-h-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="difficultyLevel[]" value="medium" id="difficultyLevel3" autocompleted="">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="difficultyLevel3">Medium</label>
                    </div>
                    <div class="mb-[0.125rem] block min-h-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="difficultyLevel[]" value="hard" id="difficultyLevel4" autocompleted="">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="difficultyLevel4">Hard</label>
                    </div>
                    <div class="mb-[0.125rem] block min-h-[1.5rem]">
                        <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="difficultyLevel[]" value="very_hard" id="difficultyLevel5" autocompleted="">
                        <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="difficultyLevel5">Very Hard</label>
                    </div>
                </div>
            </div>

            <!-- Submit and Reset Buttons -->
            <div class="col-span-12 flex gap-x-[10px] mt-[10px]">
                <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-check-circle mr-2"></i> Submit
                </button>
                <button type="reset" class="capitalize bg-danger/10 border-none text-danger text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                    <i class="uil uil-redo mr-2"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Question Card List -->
    <form action="{{route('update-practice-videos',['category'=>$subcategory->id,'skill'=>$skill->id])}}" method="POST" autocomplete="off" class="bg-white dark:bg-box-dark m-0 p-[20px] text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        @csrf
        <div class="">
            <h3>Currently Viewing Videos</h3>
            <div class="flex mt-2"><button type="button" data-type="all" data-category="{{$subcategory->id}}" data-skill="{{$skill->id}}" class="fetch_question text-gray-500">View Videos</button> <span class="mx-3">|</span> <button type="button" data-type="new" data-category="{{$subcategory->id}}" data-skill="{{$skill->id}}" class="fetch_question text-gray-500">Add Videos</button></div>
        </div>
        <div  id="videosContainer">
        </div>
        <!-- Submit All Questions -->
        <div class="flex justify-end p-[10px]">
            <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                Submit All Videos
            </button>
        </div>
    </form>

</section>

@endsection
@push('scripts')
    <script>
        function toggleOptions(id) {
            var options = document.getElementById(id);
            options.classList.toggle('hidden');
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#filterForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                // Serialize form data
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{route('filter-practice-videos')}}", // Your route
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response.videos);
                        let i = 0;
                        // Clear previous video
                        $('#videosContainer').empty();
                        let content = ``;
                        // Loop through questions and append them
                        response.videos.forEach(function(video) {

                            let topicDisplay = '';
                            if (video.skill && video.skill.name) {
                                skillDisplay = video.skill.name;
                            }
                            
                            content= `<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 mb-4">
                                        <div class="flex justify-between">
                                            <div class="flex items-center">
                                                <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="videos[]" value="${video.id}" autocompleted="">
                                                <h4 class="ml-1 bg-primary/10 text-primary text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${skillDisplay}</h4>
                                            </div>
                                        </div>
                                        <h4 class="mt-2">${video.title}</h4>
                                        <div class="text-body dark:text-subtitle-dark mt-2 flex">
                                        ${video.description}
                                        </div>
                                        <div class="space-y-3 mt-4">
                                            <div>
                                                <strong>Watch Time:</strong> ${video.watch_time}
                                            </div>
                                            <div>
                                                <strong>Difficulty Level:</strong> ${video.level}
                                            </div>
                                        </div>
                                    </div>`;
                            $('#videosContainer').append(content);
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script>
    $(document).ready(function() {
        $('.fetch_question').on('click', function() {
            const actionType = $(this).data('type');
            const category = $(this).data('category');
            const skill = $(this).data('skill');

            // Make AJAX request
            $.ajax({
                url: "{{ route('fetch-practice-videos') }}", // Ensure the route is correctly defined in Laravel
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    actionType: actionType,
                    category: category,
                    skill: skill
                },
                success: function(response) {
                        console.log(response.videos);
                        let i = 0;
                        // Clear previous video
                        $('#videosContainer').empty();
                        let content = ``;
                        // Loop through questions and append them
                        response.videos.forEach(function(video) {

                            let topicDisplay = '';
                            if (video.skill && video.skill.name) {
                                skillDisplay = video.skill.name;
                            }

                            let remvebtn = ``;
                            let checkBox = ``;
                            if(actionType == "all"){
                                remvebtn = `<button type="button" class="bg-danger hover:bg-primary-hbr border-solid border-1 border-danger text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] py-1 px-3  transition duration-300 ease-in-out" onclick="removeQuestion('${video.id}')">Remove</button>`; 
                            }else{
                                checkBox = `<input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="videos[]" value="${video.id}" autocompleted="">`;
                            }
                            
                            content= `<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 mb-4" id="video-${video.id}">
                                        <div class="flex justify-between">
                                            <div class="flex items-center">
                                                ${checkBox}
                                                <h4 class="ml-1 bg-primary/10 text-primary text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${skillDisplay}</h4>
                                            </div>
                                            ${remvebtn}
                                        </div>
                                        <h4 class="mt-2">${video.title}</h4>
                                        <div class="text-body dark:text-subtitle-dark mt-2 flex">
                                        ${video.description}
                                        </div>
                                        <div class="space-y-3 mt-4">
                                            <div>
                                                <strong>Watch Time:</strong> ${video.watch_time}
                                            </div>
                                            <div>
                                                <strong>Difficulty Level:</strong> ${video.level}
                                            </div>
                                        </div>
                                    </div>`;
                            $('#videosContainer').append(content);
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
            });
        });
    });
    </script>
    <script>
        function removeQuestion($question){
            const videoId = $question;
            const skillId = $('#skillId').val();
            const subcategoryId = $('#catgegotyId').val();

            // Make AJAX request to delete the question
            $.ajax({
                url: "{{route('remove-practice-videos')}}", // Adjust this URL to your actual endpoint
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                    videoId: videoId,
                    skillId: skillId,
                    subcategoryId: subcategoryId
                },
                success: function(response) {
                    $(`#video-${videoId}`).remove();
                    // Show success message with iziToast
                    iziToast.success({
                        title: 'Success',
                        message: 'video removed successfully!',
                        position: 'topRight'
                    });
                },
                error: function(xhr) {
                    // Show error message with iziToast
                    iziToast.error({
                        title: 'Error',
                        message: 'Failed to remove the video. Please try again.',
                        position: 'topRight'
                    });
                }
            });
        }
    </script>
    
@endpush