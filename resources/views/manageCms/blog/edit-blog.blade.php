@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')


<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Edit Blog</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="index.html">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Child (Current Page) -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Edit Blog</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <!-- Blog Form -->
      <div class="p-[25px]">
         <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data" autocomplete="off" id="editBlog">
            @csrf
            <!-- Title -->
            <div class="mb-[20px]">
               <label for="blogTitle" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Blog Title <span class="text-red-500">*</span></label>
               <input type="text" id="blogTitle" name="blogTitle" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($blog->title) {{$blog->title}} @endisset" placeholder="Enter blog title">
            </div>

            <!-- Category -->
            <div class="mb-[20px]">
               <label for="blogCategory" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Category <span class="text-red-500">*</span></label>
               <select id="blogCategory" name="blogCategory" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary">
                  @isset($blogCategory)
                        <option selected disabled>Select Category</option>
                        @foreach ($blogCategory as $item)
                           <option value="{{$item->id}}" {{$blog->category_id == $item->id ? "selected" : ""}}>{{$item->name}}</option>
                        @endforeach
                  @endisset
               </select>
            </div>

            <!-- Short Description -->
            <div class="mb-[20px]">
               <label for="shortDescription" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Short Description <span class="text-red-500">*</span></label>
               <textarea id="shortDescription" name="shortDescription" rows="3" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary summernote" placeholder="Write a short description...">@isset($blog->short_description) {{$blog->short_description}} @endisset</textarea>
            </div>

            <!-- Blog Image -->
           
            <div class="mb-[20px]">
               <label
                 for="blogImage"
                 class="mb-2 inline-block text-neutral-500 dark:text-neutral-400"
                 >Featured Image </label
               >
               <input
                 class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid  bg-transparent bg-clip-padding px-3 py-[0.32rem]  font-normal text-surface transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:me-3 file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-e file:border-solid file:border-inherit file:bg-transparent file:px-3  file:py-[0.32rem] file:text-surface focus:border-primary focus:text-gray-700 focus:shadow-inset focus:outline-none dark:border-white/70 dark:text-white  file:dark:text-white"
                 type="file" name="blogImage"
                 id="blogImage" />
                 @isset($blog->image) <img src="{{asset('blogs/'.$blog->image)}}" height="150px" alt="{{$blog->title}}"> @endisset
             </div>

             <div class="mb-[15px]">
                <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-wrap items-center gap-[10px]">
                    <!--First radio-->
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="1" autocompleted="" {{$blog->status == 1 ? "checked" : ""}}>
                       <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="status">Enable</label>
                    </div>
                    <!--Second radio-->
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" name="status" id="status" value="0" autocompleted="" {{$blog->status == 0 ? "checked" : ""}}>
                       <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="status">Disable</label>
                    </div>
                
                 </div>
            </div>

            <!-- Content -->
            <div class="mb-[20px]">
               <label for="blogContent" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Blog Content <span class="text-red-500">*</span></label>
               <textarea id="summernote" name="blogContent" rows="8" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary summernote" placeholder="Write your blog content...">@isset($blog->content) {{$blog->content}} @endisset</textarea>
            </div>
            

            <!-- Buttons -->
            <div class="flex gap-x-[10px]">
               <button type="submit" class="px-[14px] text-sm text-white rounded-md bg-primary border-primary h-10 gap-[6px] transition-[0.3s]">Submit</button>
               <button type="button" class="px-[14px] text-sm text-white rounded-md bg-danger border-danger h-10 gap-[6px] transition-[0.3s]">Cancel</button>
            </div>
         </form>
      </div>
   </div>

</section>

@endsection
@push('scripts')
    
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
   $(document).ready(function() {
      $('#summernote').summernote({
         height: 300,
         toolbar: [
           ['style', ['style']],
           ['font', ['bold', 'underline', 'clear']],
           ['color', ['color']],
           ['para', ['ul', 'ol', 'paragraph']],
           ['table', ['table']],
           ['insert', ['link', 'picture', 'video']],
           ['view', ['fullscreen', 'codeview', 'help']]
         ]
      });
   });
</script>
<script>
     // jQuery Validation for the Add Section form
     $("#editBlog").validate({
            rules: {
                blogCategoryName: {
                    required: true,
                },
                status: {
                    required: true
                }
            },
            messages: {
                blogCategoryName: {
                    required: "Please enter a section name"
                },
                status: {
                    required: "Please select a status"
                }
            },
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').html('Processing...').prop('disabled', true);
                // Form submission code can go here
                form.submit();
            }
        });
</script>
@endpush