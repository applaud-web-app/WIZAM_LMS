@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

    <section class=" mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">


        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">

                <!-- Breadcrumb Section -->
                <div
                    class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
                    <!-- Title -->
                    <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Contactpage Settings</h4>
                    <!-- Breadcrumb Navigation -->
                    <div class="flex flex-wrap justify-center">
                        <nav>
                            <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                                <!-- Parent Link -->
                                <li class="inline-flex items-center">
                                    <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group"
                                        href="{{ route('admin-dashboard') }}">
                                        <i
                                            class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                                </li>
                                <!-- Middle (Conditional) -->

                                <li
                                    class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 transition duration-300 capitalize">Setting</span>
                                </li>

                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]"
                                    aria-current="page">
                                    <span
                                        class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Contactpage
                                        Settings</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            {{-- Contact Section --}}
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('update-contact') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="addContact" >
                            @csrf
                            <h1 class="mb-4 text-xl font-bold">Contact Section</h1>
                    
                            <!-- Title -->
                            <div class="mb-5">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                                <input type="text" id="title" name="title" 
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary mt-2" 
                                    placeholder="Your Title" value="{{ old('title', $contactPage->title ?? '') }}">
                            </div>
                    
                            <!-- Address -->
                            <div class="mb-5">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address <span class="text-red-500">*</span></label>
                                <input type="text" id="address" name="address" 
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary mt-2" 
                                    placeholder="Your Address" value="{{ old('address', $contactPage->description ?? '') }}">
                            </div>
                    
                            <!-- Phone -->
                            <div class="mb-5">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-red-500">*</span></label>
                                <input type="text" id="phone" name="phone" 
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary mt-2" 
                                    placeholder="Enter Contact Phone Number" value="{{ old('phone', optional(json_decode($contactPage->extra ?? '{}'))->phone) }}">
                            </div>
                    
                            <!-- Email -->
                            <div class="mb-5">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" 
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary mt-2" 
                                    placeholder="Enter Contact Email" value="{{ old('email', optional(json_decode($contactPage->extra ?? '{}'))->email) }}">
                            </div>
                    
                            <h1 class="mb-4 text-xl font-bold">Directions Section</h1>
                    
                            <div class="mb-5">
                                <label for="direction_title" class="block text-sm font-medium text-gray-700">Direction Title <span class="text-red-500">*</span></label>
                                <input type="text" id="direction_title" name="direction_title" 
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary mt-2" 
                                    placeholder="Your Direction Title" value="{{ old('direction_title', $contactPage->button_text ?? '') }}">
                            </div>
                            <!-- Directions -->
                            <div id="directions-container" class="space-y-4">
                                @php $directions = optional(json_decode($contactPage->extra ?? '{}'))->directions ?? []; @endphp
                                @if (isset($directions) && count($directions))
                                    @foreach ($directions as $direction)
                                        <div class="direction-item flex items-center space-x-2 mt-2">
                                            <textarea name="directions[]" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                placeholder="Add your direction here...">{{ $direction }}</textarea>
                                            @if ($loop->index > 0)
                                                <button type="button" onclick="removeDirection(this)" 
                                                    class="btn bg-red-500 rounded-xl px-3 py-2 text-white">Remove</button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="direction-item flex items-start space-x-2">
                                        <textarea name="directions[]" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary mt-2"
                                            placeholder="Add your direction here..."></textarea>
                                    </div>
                                @endif
                            </div>
                    
                            <button type="button" onclick="addDirection()" 
                                class="mt-4 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Add More Directions</button>
                    
                            <!-- Submit -->
                            <div class="mt-6">
                                <button type="submit" 
                                    class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-[25px] mb-[30px]">
            {{-- Contact Section --}}
            <div class="col-span-12 md:col-span-12">
                <div
                    class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                    <div class="p-[25px]">
                        <form action="{{ route('enquiry-form') }}" method="POST" autocomplete="off" enctype="multipart/form-data" id="updateValues">
                            @csrf
                            <h1 class="mb-4 text-xl"><b>Inquiry Form</b></h1>
                            <div class="mb-[15px]">
                                <label for="title" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                   Form Title <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="title" name="title"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Your Title" value="{{ old('title', $formData['title'] ?? '') }}">
                                </div>
                            </div>
                            <h5 class="mb-[15px]"><b>- - - Form Feilds - - -</b></h5>
                            <div class="mb-[15px]">
                                <label for="name_placeholer" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Name Place Holder <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="name_placeholer" name="name_placeholer"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Enter Value" value="{{ old('name_placeholer', $formData['name_placeholder'] ?? '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="email_placeholder" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Email Placeholder <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="email_placeholder" name="email_placeholder"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Enter Value" value="{{ old('email_placeholder', $formData['email_placeholder'] ?? '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="phone_placeholer" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Phone Placeholder <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="phone_placeholer" name="phone_placeholer"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Enter Value" value="{{ old('phone_placeholer', $formData['phone_placeholder'] ?? '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]" id="study-mode-section">
                                <label for="study_mode" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Study Mode Values <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Container for dynamic options -->
                                <div id="study-mode-options">
                                    

                                    @if(!empty($formData['study_mode']))
                                        @foreach($formData['study_mode'] as $studyMode)
                                            <div class="study-mode-option mb-[10px] flex items-center">
                                                <input type="text" name="study_mode[]" placeholder="Enter Option"
                                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                    value="{{ $studyMode }}">
                                                @if ($loop->index > 0)
                                                    <button type="button" class="remove-option-btn btn bg-red-500 rounded-xl px-3 py-2 text-white ml-2">Remove</button>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else 
                                        <div class="study-mode-option mb-[10px] flex items-center">
                                            <input type="text" name="study_mode[]" placeholder="Enter Option" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Button to add more options -->
                                <button type="button" id="add-study-mode" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">
                                    Add More Option
                                </button>
                            </div>
                        
                            <!-- Courses Section (Editable dynamic options) -->
                            <div class="mb-[15px]" id="courses-section">
                                <label for="courses" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Courses Values <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Container for dynamic options -->
                                <div id="courses-options">
                                    @if(!empty($formData['courses']))
                                        @foreach($formData['courses'] as $course)
                                            <div class="study-mode-option mb-[10px] flex items-center">
                                                    <input type="text" name="courses[]" placeholder="Enter Course" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="{{ $course}}">
                                                @if ($loop->index > 0)
                                                    <button type="button" class="remove-option-btn btn bg-red-500 rounded-xl px-3 py-2 text-white ml-2">Remove</button>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else 
                                        <div class="courses-option mb-[10px] flex items-center">
                                            <input type="text" name="courses[]" placeholder="Enter Course" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Button to add more options -->
                                <button type="button" id="add-courses" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">
                                    Add More Option
                                </button>
                            </div>
                        
                            <!-- Hear By Section (Editable dynamic options) -->
                            <div class="mb-[15px]" id="hear-by-section">
                                <label for="hear_by" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Hear By <span class="text-red-500">*</span>
                                </label>
                                
                                <!-- Container for dynamic options -->
                                <div id="hear-by-options">
                                    @if(!empty($formData['hear_by']))
                                        @foreach($formData['hear_by'] as $Option)
                                            <div class="study-mode-option mb-[10px] flex items-center">
                                                    <input type="text" name="hear_by[]" placeholder="Enter Option" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" value="{{ $Option}}">
                                                @if ($loop->index > 0)
                                                    <button type="button" class="remove-option-btn btn bg-red-500 rounded-xl px-3 py-2 text-white ml-2">Remove</button>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else 
                                        <div class="hear-by-option mb-[10px] flex items-center">
                                            <input type="text" name="hear_by[]" placeholder="Enter Option" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Button to add more options -->
                                <button type="button" id="add-hear-by" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">
                                    Add More Option
                                </button>
                            </div>
                            <div class="mb-[15px]">
                                <label for="message_placeholder" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Message Placeholder <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="message_placeholder" name="message_placeholder"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Enter Value" value="{{ old('message_placeholder', $formData['message_placeholder'] ?? '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <label for="checkbox_placeholder" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                                    Policy Checkbox Text <span class="text-red-500">*</span>
                                </label>
                                <div class="flex flex-col flex-1">
                                    <input type="text" id="checkbox_placeholder" name="checkbox_placeholder"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                        placeholder="Enter Value" value="{{ old('checkbox_placeholder', $formData['checkbox_placeholder'] ?? '') }}">
                                </div>
                            </div>
                            <div class="mb-[15px]">
                                <button type="submit" class="mt-3 bg-primary text-white py-[12px] px-[20px] rounded-4 border-none cursor-pointer hover:bg-primary-dark focus:ring-primary focus:border-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection
@push('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300,

            });
        });
    </script>
    <script>
        // Function to add a new direction field
        function addDirection() {
            const container = document.getElementById('directions-container');
            const directionItem = document.createElement('div');
            directionItem.classList.add('direction-item', 'flex', 'items-center', 'space-x-2','mt-2');
            directionItem.innerHTML = `
                <textarea name="directions[]" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary "
                    placeholder="Add your direction here..."></textarea>
                <button type="button" onclick="removeDirection(this)" 
                    class="btn bg-red-500 rounded-xl px-3 py-2 text-white">Remove</button>
            `;
            container.appendChild(directionItem);
        }

        // Function to remove a direction field
        function removeDirection(button) {
            button.closest('.direction-item').remove();
        }
    </script>
    <script>
        // Add dynamic options to Study Mode
        document.getElementById('add-study-mode').addEventListener('click', function() {
            const newOptionHTML = `
                <div class="study-mode-option mb-[10px] flex items-center">
                    <input type="text" name="study_mode[]" placeholder="Enter Option" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                    <button type="button" class="remove-option-btn btn bg-red-500 rounded-xl px-3 py-2 text-white  ml-2">Remove</button>
                </div>
            `;
            document.getElementById('study-mode-options').insertAdjacentHTML('beforeend', newOptionHTML);
            attachRemoveOptionEvent(); // Attach remove functionality to newly added options
        });
    
        // Add dynamic options to Courses
        document.getElementById('add-courses').addEventListener('click', function() {
            const newOptionHTML = `
                <div class="courses-option mb-[10px] flex items-center">
                    <input type="text" name="courses[]" placeholder="Enter Course" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                    <button type="button" class="remove-option-btn btn bg-red-500 rounded-xl px-3 py-2 text-white  ml-2">Remove</button>
                </div>
            `;
            document.getElementById('courses-options').insertAdjacentHTML('beforeend', newOptionHTML);
            attachRemoveOptionEvent(); // Attach remove functionality to newly added options
        });
    
        // Add dynamic options to Hear By
        document.getElementById('add-hear-by').addEventListener('click', function() {
            const newOptionHTML = `
                <div class="hear-by-option mb-[10px] flex items-center">
                    <input type="text" name="hear_by[]" placeholder="Enter Option" class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary">
                    <button type="button" class="remove-option-btn btn bg-red-500 rounded-xl px-3 py-2 text-white ml-2">Remove</button>
                </div>
            `;
            document.getElementById('hear-by-options').insertAdjacentHTML('beforeend', newOptionHTML);
            attachRemoveOptionEvent(); // Attach remove functionality to newly added options
        });
    
        // Function to attach the "Remove" button event
        function attachRemoveOptionEvent() {
            const removeButtons = document.querySelectorAll('.remove-option-btn');
            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    this.closest('.flex').remove();
                });
            });
        }
    
        // Initial call to attach remove functionality to existing options
        attachRemoveOptionEvent();
    </script>
@endpush
