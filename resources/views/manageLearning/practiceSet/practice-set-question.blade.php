@extends('layouts.master')

@section('title', 'Add Practice Question')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <!-- Stepper Section -->
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="mb-[30px]">
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
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center">
                                    2
                                </div>
                                <div class="text-gray-400 mt-2">Settings</div>
                                
                            </div>
                        </a>
                        <!-- Divider -->
                       
                        <div class="w-[40px] h-[2px] bg-primary"></div>
                        
                        <!-- Step 3 -->
                        <a href="{{route('practice-set-question',['id'=>request()->id])}}" class="flex-1 text-center">
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    3
                                </div>
                                <div class="text-primary mt-2">Questions</div>
                            </div>
                        </a>
                    </div>
                </div>
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
            <input type="hidden" name="pratice_set" value="{{$praticeSet->id}}" id="practiceSetId">
            <!-- Question Type Checkbox -->
            <div class="col-span-12 sm:col-span-6 md:col-span-6">
                <label class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Question Type</label>
                <div class="flex flex-col">
                    @isset($questionType)
                        @foreach ($questionType as $item)
                            <div class="mb-[0.125rem] block min-h-[1.5rem]">
                                <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="questionType[]" value="{{$item->type}}" id="questionType1" autocompleted="">
                                <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="questionType1">{{$item->name}}</label>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>

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
    <form action="{{route('update-practice-set-question',['id'=>$praticeSet->id])}}" method="POST" autocomplete="off" class="bg-white dark:bg-box-dark m-0 p-[20px] text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        @csrf
        <div class="">
            <h3>Currently Viewing Questions</h3>
            <div class="flex mt-2"><button type="button" data-type="all" data-id="{{$praticeSet->id}}" class="fetch_question text-gray-500">View Questions</button> <span class="mx-3">|</span> <button type="button" data-type="new" data-id="{{$praticeSet->id}}" class="fetch_question text-gray-500">Add Questions</button></div>
        </div>
        <div  id="questionsContainer">
        </div>
        <!-- Submit All Questions -->
        <div class="flex justify-end p-[10px]">
            <button type="submit" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">
                Submit All Questions
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
                    url: "{{route('filter-practice-set-question')}}", // Your route
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle the response and update your page with the filtered questions
                        console.log(response.questions);
                        let i = 0;
                        // Clear previous questions
                        $('#questionsContainer').empty();
                        let content = ``;
                        // Loop through questions and append them
                        response.questions.forEach(function(question) {
                            console.log(question);
                            let options = [];
                            try {
                                options = JSON.parse(question.options);
                            } catch (e) {
                                console.error('Error parsing options:', e);
                            }

                            // <li class="text-green-600 border-b py-2">134°C (Correct Answer)</li>
                            // let optionsList = '';
                            // options.forEach(function(option, index) {
                            //     console.log(option);
                            //     optionsList += `<li class="border-b py-2 ${index+1 == question.answer ? "bg-green-100 px-3 text-green-600" : "text-gray-600"}">${option}</li>`;
                            // });

                            let optionsList = '';
                            if (question.type === "MSA" || question.type === "TOF"  || question.type === "SAQ") {
                                // Single answer (MSA)
                                options.forEach(function(option, index) {
                                    // Ensure both values being compared are numbers
                                    const selectedAnswer = parseInt(question.answer);
                                    optionsList += `<li class="border-b py-2 ${index + 1 === selectedAnswer ? 'bg-green-100 px-3 text-green-600' : 'text-gray-600'}">${option}</li>`;
                                });
                            } else if (question.type === "MMA") {
                                // Multiple answers (MMA)
                                try {
                                    const answer = JSON.parse(question.answer); // Convert the answer to an array
                                    console.log(answer); // Check the parsed answer

                                    options.forEach(function(option, index) {
                                        // Ensure that the answer array contains numbers, and compare accordingly
                                        optionsList += `<li class="border-b py-2 ${answer.map(Number).includes(index + 1) ? 'bg-green-100 px-3 text-green-600' : 'text-gray-600'}">${option}</li>`;
                                    });
                                } catch (e) {
                                    console.error('Error parsing answer:', e);
                                }
                            } else if (question.type === "MTF") {
                                const answers = JSON.parse(question.answer); // Assuming the answer is a key-value pair object
                                options.forEach(function(option, index) {
                                    // Retrieve the matching answer by the current index + 1
                                    let answer = answers[index + 1]; // Adjusted for 1-based indexing
                                    optionsList += `
                                        <li class="border-b py-2">
                                            <div class="flex">
                                                <div class="w-1/2 text-gray-600">${option}</div>
                                                <div class="w-1/2 bg-green-100 px-3 text-green-600">${answer}</div>
                                            </div>
                                        </li>`;
                                });
                            }else if(question.type === "ORD"){
                                try {
                                    const order = JSON.parse(question.answer); // Parse the ordering sequence

                                    order.forEach(function(index, orderNumber) {
                                        // Append the option in the order specified by the order array, with the order number
                                        optionsList += `<li class="border-b py-2 bg-green-100 mb-2 px-3 text-green-600 flex">
                                                            <span class="font-bold me-2">${orderNumber + 1}.</span> ${options[index]}
                                                        </li>`;
                                    });
                                } catch (e) {
                                    console.error('Error parsing order:', e);
                                }
                            }else if(question.type === "FIB"){
                                try {
                                    const answer = JSON.parse(question.answer); // Convert the answer to an array
                                    console.log(answer); // Check the parsed answer

                                    answer.forEach(function(ans, index) {
                                        // Ensure that the answer array contains numbers, and compare accordingly
                                        optionsList += `<li class="border-b py-2 bg-green-100 px-3 text-green-600">${ans}</li>`;
                                    });
                                } catch (e) {
                                    console.error('Error parsing answer:', e);
                                }
                            }else if (question.type === "EMQ") {
                                // Generate the options list
                                options.forEach(function(option, index) {
                                    optionsList += `<li class="border-b py-2 text-gray-600">${option}</li>`;
                                });

                                try {
                                    // Parse the answer and question data
                                    const answers = JSON.parse(question.answer); 
                                    const questionData = JSON.parse(question.question); 
                                    
                                    // Iterate through the question data
                                    questionData.forEach(function(quest, index) {
                                        if (index > 0) {
                                            // Display question and corresponding answer
                                            optionsList += `
                                                <div class="mt-3">
                                                    <div class="flex">Q${index}. ${quest}</div>
                                                    <div class="py-2 mt-2 bg-green-100 px-3 text-green-600">${options[answers[index - 1]-1]}</div>
                                                </div>`;
                                        }
                                    });
                                } catch (e) {
                                    console.error('Error parsing answer:', e);
                                }
                            }

                            let topicDisplay = '';
                            if (question.topic && question.topic.name) {
                                topicDisplay = question.topic.name;
                            }

                            let questionContent = question.question;
                            if (question.type === "FIB") {
                                questionContent = questionContent.replace(/##(.*?)##/g, '<span style="border-bottom: 1px solid black; display: inline-block; width: 100px; text-align: center;"></span>');
                            }else if(question.type === "EMQ"){
                                questData = JSON.parse(question.question); // Convert the answer to an array
                                questionContent = questData[0];
                            }

                            content= `<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 mb-3">
                                        <div class="flex justify-between">
                                            <div class="flex items-center">
                                                <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="question[]" value="${question.id}" autocompleted="">
                                                <h4 class="ml-1 bg-primary/10 text-primary text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${topicDisplay}</h4>
                                            </div>
                                            <button type="button" class="bg-secondary hover:bg-primary-hbr border-solid border-1 border-secondary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] py-1 px-3  transition duration-300 ease-in-out" onclick="toggleOptions('options${question.id}')">
                                                View Options
                                            </button>
                                        </div>
                                        <div class="text-body dark:text-subtitle-dark mt-2 flex">
                                        <span class="me-2">${++i}</span> ${questionContent}
                                        </div>
                                        <div id="options${question.id}" class="hidden mt-4">
                                            <ul class="list-disc pl-6">
                                                ${optionsList}
                                            </ul>
                                        </div>
                                        <div class="space-y-3 mt-4">
                                            <div>
                                                <strong>Question Type:</strong> ${question.type}
                                            </div>
                                            <div>
                                                <strong>Difficulty Level:</strong> ${question.level}
                                            </div>
                                            <div>
                                                <strong>Watch Time:</strong> ${question.watch_time}
                                            </div>
                                            <div>
                                                <strong>Default Marks:</strong> ${question.default_marks}
                                            </div>
                                        </div>
                                    </div>`;

                            $('#questionsContainer').append(content);
                            // Parse options from JSON string to array
                            // let options = [];
                            // try {
                            //     options = JSON.parse(question.options);
                            // } catch (e) {
                            //     console.error('Error parsing options:', e);
                            // }
    
                            // let optionsList = '';
                            // options.forEach(function(option) {
                            //     optionsList += `
                            //         <li>
                            //             ${option}
                            //         </li>
                            //     `;
                            // });
    
                            // $('#questionsContainer').append(`
                            //     <div class="question mb-4 p-4 border border-gray-300 rounded-lg">
                            //         <h3 class="text-xl font-semibold">${question.question}</h3>
                            //         <ul class="list-disc pl-5 mt-2">
                            //             ${optionsList}
                            //         </ul>
                            //         <p><strong>Level:</strong> ${question.level}</p>
                            //         <p><strong>Marks:</strong> ${question.default_marks}</p>
                            //         <p><strong>Type:</strong> ${question.type}</p>
                            //         <p><strong>Created At:</strong> ${question.created_at}</p>
                            //     </div>
                            // `);
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
    //    $(document).ready(function() {
    //         $('.fetch_question').on('click', function() {
    //             const actionType = $(this).data('type');
    //             const practiceSet = $(this).data('id');

    //             // Make AJAX request
    //             $.ajax({
    //                 url: "{{'fetch-practice-set-question'}}",
    //                 type: 'POST', // Change to POST
    //                 data: {
    //                     _token: '{{ csrf_token() }}', // Include CSRF token
    //                     actionType: actionType, // Send action type
    //                     practiceSet: practiceSet // Send practice set ID
    //                 },
    //                 success: function(response) {
    //                     // Handle the response and update your page with the filtered questions
    //                     console.log(response.questions);
    //                     let i = 0;
    //                     // Clear previous questions
    //                     $('#questionsContainer').empty();
    //                     let content = ``;
    //                     // Loop through questions and append them
    //                     response.questions.forEach(function(question) {
    //                         console.log(question);
    //                         let options = [];
    //                         try {
    //                             options = JSON.parse(question.options);
    //                         } catch (e) {
    //                             console.error('Error parsing options:', e);
    //                         }

    //                         // <li class="text-green-600 border-b py-2">134°C (Correct Answer)</li>
    //                         // let optionsList = '';
    //                         // options.forEach(function(option, index) {
    //                         //     console.log(option);
    //                         //     optionsList += `<li class="border-b py-2 ${index+1 == question.answer ? "bg-green-100 px-3 text-green-600" : "text-gray-600"}">${option}</li>`;
    //                         // });

    //                         let optionsList = '';
    //                         if (question.type === "MSA" || question.type === "TOF"  || question.type === "SAQ") {
    //                             // Single answer (MSA)
    //                             options.forEach(function(option, index) {
    //                                 // Ensure both values being compared are numbers
    //                                 const selectedAnswer = parseInt(question.answer);
    //                                 optionsList += `<li class="border-b py-2 ${index + 1 === selectedAnswer ? 'bg-green-100 px-3 text-green-600' : 'text-gray-600'}">${option}</li>`;
    //                             });
    //                         } else if (question.type === "MMA") {
    //                             // Multiple answers (MMA)
    //                             try {
    //                                 const answer = JSON.parse(question.answer); // Convert the answer to an array
    //                                 console.log(answer); // Check the parsed answer

    //                                 options.forEach(function(option, index) {
    //                                     // Ensure that the answer array contains numbers, and compare accordingly
    //                                     optionsList += `<li class="border-b py-2 ${answer.map(Number).includes(index + 1) ? 'bg-green-100 px-3 text-green-600' : 'text-gray-600'}">${option}</li>`;
    //                                 });
    //                             } catch (e) {
    //                                 console.error('Error parsing answer:', e);
    //                             }
    //                         } else if (question.type === "MTF") {
    //                             const answers = JSON.parse(question.answer); // Assuming the answer is a key-value pair object
    //                             options.forEach(function(option, index) {
    //                                 // Retrieve the matching answer by the current index + 1
    //                                 let answer = answers[index + 1]; // Adjusted for 1-based indexing
    //                                 optionsList += `
    //                                     <li class="border-b py-2">
    //                                         <div class="flex">
    //                                             <div class="w-1/2 text-gray-600">${option}</div>
    //                                             <div class="w-1/2 bg-green-100 px-3 text-green-600">${answer}</div>
    //                                         </div>
    //                                     </li>`;
    //                             });
    //                         }else if(question.type === "ORD"){
    //                             try {
    //                                 const order = JSON.parse(question.answer); // Parse the ordering sequence

    //                                 order.forEach(function(index, orderNumber) {
    //                                     // Append the option in the order specified by the order array, with the order number
    //                                     optionsList += `<li class="border-b py-2 bg-green-100 mb-2 px-3 text-green-600 flex">
    //                                                         <span class="font-bold me-2">${orderNumber + 1}.</span> ${options[index]}
    //                                                     </li>`;
    //                                 });
    //                             } catch (e) {
    //                                 console.error('Error parsing order:', e);
    //                             }
    //                         }else if(question.type === "FIB"){
    //                             try {
    //                                 const answer = JSON.parse(question.answer); // Convert the answer to an array
    //                                 console.log(answer); // Check the parsed answer

    //                                 answer.forEach(function(ans, index) {
    //                                     // Ensure that the answer array contains numbers, and compare accordingly
    //                                     optionsList += `<li class="border-b py-2 bg-green-100 px-3 text-green-600">${ans}</li>`;
    //                                 });
    //                             } catch (e) {
    //                                 console.error('Error parsing answer:', e);
    //                             }
    //                         }else if (question.type === "EMQ") {
    //                             // Generate the options list
    //                             options.forEach(function(option, index) {
    //                                 optionsList += `<li class="border-b py-2 text-gray-600">${option}</li>`;
    //                             });

    //                             try {
    //                                 // Parse the answer and question data
    //                                 const answers = JSON.parse(question.answer); 
    //                                 const questionData = JSON.parse(question.question); 
                                    
    //                                 // Iterate through the question data
    //                                 questionData.forEach(function(quest, index) {
    //                                     if (index > 0) {
    //                                         // Display question and corresponding answer
    //                                         optionsList += `
    //                                             <div class="mt-3">
    //                                                 <div class="flex">Q${index}. ${quest}</div>
    //                                                 <div class="py-2 mt-2 bg-green-100 px-3 text-green-600">${options[answers[index - 1]-1]}</div>
    //                                             </div>`;
    //                                     }
    //                                 });
    //                             } catch (e) {
    //                                 console.error('Error parsing answer:', e);
    //                             }
    //                         }

    //                         let topicDisplay = '';
    //                         if (question.topic && question.topic.name) {
    //                             topicDisplay = question.topic.name;
    //                         }

    //                         let questionContent = question.question;
    //                         if (question.type === "FIB") {
    //                             questionContent = questionContent.replace(/##(.*?)##/g, '<span style="border-bottom: 1px solid black; display: inline-block; width: 100px; text-align: center;"></span>');
    //                         }else if(question.type === "EMQ"){
    //                             questData = JSON.parse(question.question); // Convert the answer to an array
    //                             questionContent = questData[0];
    //                         }

    //                         content= `<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 mb-3">
    //                                     <div class="flex justify-between">
    //                                         <div class="flex items-center">
    //                                             <input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="question[]" value="${question.id}" autocompleted="">
    //                                             <h4 class="ml-1 bg-primary/10 text-primary text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${topicDisplay}</h4>
    //                                         </div>
    //                                         <button type="button" class="bg-secondary hover:bg-primary-hbr border-solid border-1 border-secondary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] py-1 px-3  transition duration-300 ease-in-out" onclick="toggleOptions('options${question.id}')">
    //                                             View Options
    //                                         </button>
    //                                     </div>
    //                                     <div class="text-body dark:text-subtitle-dark mt-2 flex">
    //                                     <span class="me-2">${++i}</span> ${questionContent}
    //                                     </div>
    //                                     <div id="options${question.id}" class="hidden mt-4">
    //                                         <ul class="list-disc pl-6">
    //                                             ${optionsList}
    //                                         </ul>
    //                                     </div>
    //                                     <div class="space-y-3 mt-4">
    //                                         <div>
    //                                             <strong>Question Type:</strong> ${question.type}
    //                                         </div>
    //                                         <div>
    //                                             <strong>Difficulty Level:</strong> ${question.level}
    //                                         </div>
    //                                         <div>
    //                                             <strong>Watch Time:</strong> ${question.watch_time}
    //                                         </div>
    //                                         <div>
    //                                             <strong>Default Marks:</strong> ${question.default_marks}
    //                                         </div>
    //                                     </div>
    //                                 </div>`;

    //                         $('#questionsContainer').append(content);
    //                         // Parse options from JSON string to array
    //                         // let options = [];
    //                         // try {
    //                         //     options = JSON.parse(question.options);
    //                         // } catch (e) {
    //                         //     console.error('Error parsing options:', e);
    //                         // }
    
    //                         // let optionsList = '';
    //                         // options.forEach(function(option) {
    //                         //     optionsList += `
    //                         //         <li>
    //                         //             ${option}
    //                         //         </li>
    //                         //     `;
    //                         // });
    
    //                         // $('#questionsContainer').append(`
    //                         //     <div class="question mb-4 p-4 border border-gray-300 rounded-lg">
    //                         //         <h3 class="text-xl font-semibold">${question.question}</h3>
    //                         //         <ul class="list-disc pl-5 mt-2">
    //                         //             ${optionsList}
    //                         //         </ul>
    //                         //         <p><strong>Level:</strong> ${question.level}</p>
    //                         //         <p><strong>Marks:</strong> ${question.default_marks}</p>
    //                         //         <p><strong>Type:</strong> ${question.type}</p>
    //                         //         <p><strong>Created At:</strong> ${question.created_at}</p>
    //                         //     </div>
    //                         // `);
    //                     });
    //                 },
    //                 error: function(xhr) {
    //                     console.error(xhr.responseText);
    //                 }
    //             });
    //         });
    //     });

    $(document).ready(function() {
        $('.fetch_question').on('click', function() {
            const actionType = $(this).data('type');
            const practiceSet = $(this).data('id');

            // Make AJAX request
            $.ajax({
                url: "{{ route('fetch-practice-set-question') }}", // Ensure the route is correctly defined in Laravel
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    actionType: actionType,
                    practiceSet: practiceSet
                },
                success: function(response) {
                    // Handle the response and update your page with the filtered questions
                    console.log(response.questions);
                    let i = 0;
                    // Clear previous questions
                    $('#questionsContainer').empty();
                    let content = ``;
                    // Loop through questions and append them
                    response.questions.forEach(function(question) {
                        console.log(question);
                        let options = [];
                        try {
                            options = JSON.parse(question.options);
                        } catch (e) {
                            console.error('Error parsing options:', e);
                        }

                        // <li class="text-green-600 border-b py-2">134°C (Correct Answer)</li>
                        // let optionsList = '';
                        // options.forEach(function(option, index) {
                        //     console.log(option);
                        //     optionsList += `<li class="border-b py-2 ${index+1 == question.answer ? "bg-green-100 px-3 text-green-600" : "text-gray-600"}">${option}</li>`;
                        // });

                        let optionsList = '';
                        if (question.type === "MSA" || question.type === "TOF"  || question.type === "SAQ") {
                            // Single answer (MSA)
                            options.forEach(function(option, index) {
                                // Ensure both values being compared are numbers
                                const selectedAnswer = parseInt(question.answer);
                                optionsList += `<li class="border-b py-2 ${index + 1 === selectedAnswer ? 'bg-green-100 px-3 text-green-600' : 'text-gray-600'}">${option}</li>`;
                            });
                        } else if (question.type === "MMA") {
                            // Multiple answers (MMA)
                            try {
                                const answer = JSON.parse(question.answer); // Convert the answer to an array
                                console.log(answer); // Check the parsed answer

                                options.forEach(function(option, index) {
                                    // Ensure that the answer array contains numbers, and compare accordingly
                                    optionsList += `<li class="border-b py-2 ${answer.map(Number).includes(index + 1) ? 'bg-green-100 px-3 text-green-600' : 'text-gray-600'}">${option}</li>`;
                                });
                            } catch (e) {
                                console.error('Error parsing answer:', e);
                            }
                        } else if (question.type === "MTF") {
                            const answers = JSON.parse(question.answer); // Assuming the answer is a key-value pair object
                            options.forEach(function(option, index) {
                                // Retrieve the matching answer by the current index + 1
                                let answer = answers[index + 1]; // Adjusted for 1-based indexing
                                optionsList += `
                                    <li class="border-b py-2">
                                        <div class="flex">
                                            <div class="w-1/2 text-gray-600">${option}</div>
                                            <div class="w-1/2 bg-green-100 px-3 text-green-600">${answer}</div>
                                        </div>
                                    </li>`;
                            });
                        }else if(question.type === "ORD"){
                            try {
                                const order = JSON.parse(question.answer); // Parse the ordering sequence

                                order.forEach(function(index, orderNumber) {
                                    // Append the option in the order specified by the order array, with the order number
                                    optionsList += `<li class="border-b py-2 bg-green-100 mb-2 px-3 text-green-600 flex">
                                                        <span class="font-bold me-2">${orderNumber + 1}.</span> ${options[index]}
                                                    </li>`;
                                });
                            } catch (e) {
                                console.error('Error parsing order:', e);
                            }
                        }else if(question.type === "FIB"){
                            try {
                                const answer = JSON.parse(question.answer); // Convert the answer to an array
                                console.log(answer); // Check the parsed answer

                                answer.forEach(function(ans, index) {
                                    // Ensure that the answer array contains numbers, and compare accordingly
                                    optionsList += `<li class="border-b py-2 bg-green-100 px-3 text-green-600">${ans}</li>`;
                                });
                            } catch (e) {
                                console.error('Error parsing answer:', e);
                            }
                        }else if (question.type === "EMQ") {
                            // Generate the options list
                            options.forEach(function(option, index) {
                                optionsList += `<li class="border-b py-2 text-gray-600">${option}</li>`;
                            });

                            try {
                                // Parse the answer and question data
                                const answers = JSON.parse(question.answer); 
                                const questionData = JSON.parse(question.question); 
                                
                                // Iterate through the question data
                                questionData.forEach(function(quest, index) {
                                    if (index > 0) {
                                        // Display question and corresponding answer
                                        optionsList += `
                                            <div class="mt-3">
                                                <div class="flex">Q${index}. ${quest}</div>
                                                <div class="py-2 mt-2 bg-green-100 px-3 text-green-600">${options[answers[index - 1]-1]}</div>
                                            </div>`;
                                    }
                                });
                            } catch (e) {
                                console.error('Error parsing answer:', e);
                            }
                        }

                        let topicDisplay = '';
                        if (question.topic && question.topic.name) {
                            topicDisplay = question.topic.name;
                        }

                        let questionContent = question.question;
                        if (question.type === "FIB") {
                            questionContent = questionContent.replace(/##(.*?)##/g, '<span style="border-bottom: 1px solid black; display: inline-block; width: 100px; text-align: center;"></span>');
                        }else if(question.type === "EMQ"){
                            questData = JSON.parse(question.question); // Convert the answer to an array
                            questionContent = questData[0];
                        }

                        let remvebtn = ``;
                        let checkBox = ``;
                        if(actionType == "all"){
                           remvebtn = `<button type="button" class="bg-danger hover:bg-primary-hbr border-solid border-1 border-danger text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] py-1 px-3  transition duration-300 ease-in-out" onclick="removeQuestion('${question.id}')">Remove</button>`; 
                        }else{
                            checkBox = `<input class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]" type="checkbox" name="question[]" value="${question.id}" autocompleted="">`;
                        }

                        content= `<div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 mb-3" id="question-${question.id}">
                                    <div class="flex justify-between">
                                        <div class="flex items-center">
                                            ${checkBox}
                                            <h4 class="ml-1 bg-primary/10 text-primary text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">${topicDisplay}</h4>
                                        </div>
                                        <div>
                                            <button type="button" class="bg-secondary hover:bg-primary-hbr border-solid border-1 border-secondary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] py-1 px-3  transition duration-300 ease-in-out" onclick="toggleOptions('options${question.id}')">View Options</button>
                                            ${remvebtn}
                                        </div>
                                    </div>
                                    <div class="text-body dark:text-subtitle-dark mt-2 flex">
                                    <span class="me-2">${++i}</span> ${questionContent}
                                    </div>
                                    <div id="options${question.id}" class="hidden mt-4">
                                        <ul class="list-disc pl-6">
                                            ${optionsList}
                                        </ul>
                                    </div>
                                    <div class="space-y-3 mt-4">
                                        <div>
                                            <strong>Question Type:</strong> ${question.type}
                                        </div>
                                        <div>
                                            <strong>Difficulty Level:</strong> ${question.level}
                                        </div>
                                        <div>
                                            <strong>Watch Time:</strong> ${question.watch_time}
                                        </div>
                                        <div>
                                            <strong>Default Marks:</strong> ${question.default_marks}
                                        </div>
                                    </div>
                                </div>`;
                        $('#questionsContainer').append(content);
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
            const questionId = $question;
            const practiceId = $('#practiceSetId').val();

            // Make AJAX request to delete the question
            $.ajax({
                url: "{{route('remove-practice-set-question')}}", // Adjust this URL to your actual endpoint
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token
                    questionId: questionId,
                    practiceId: practiceId
                },
                success: function(response) {
                    $(`#question-${questionId}`).remove();
                    // Show success message with iziToast
                    iziToast.success({
                        title: 'Success',
                        message: 'Question removed successfully!',
                        position: 'topRight'
                    });
                },
                error: function(xhr) {
                    // Show error message with iziToast
                    iziToast.error({
                        title: 'Error',
                        message: 'Failed to remove the question. Please try again.',
                        position: 'topRight'
                    });
                }
            });
        }
    </script>
    
@endpush