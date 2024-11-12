@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')


<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Edit Page</h4>
            <!-- Breadcrumb Navigation -->
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <!-- Parent Link -->
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="{{route('admin-dashboard')}}">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <!-- Child (Current Page) -->
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Edit Page</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      
      <!-- Page Form -->
      <div class="p-[25px]">
         <form action="{{url()->full()}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <!-- Title -->
            <div class="mb-[20px]">
               <label for="page_title" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Page Title <span class="text-red-500">*</span></label>
               <input type="text" id="page_title" name="page_title" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($page){{$page->title}}@endisset" placeholder="Enter Page title">
            </div>

            <!-- Content -->
            <div class="mb-[20px]">
               <label for="description" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Description <span class="text-red-500">*</span></label>
               <textarea id="summernote" name="description" rows="8" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary summernote" placeholder="Write your page content...">@isset($page){{$page->description}}@endisset</textarea>
            </div>

            <div class="mb-[20px]">
                <label for="meta_title" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Meta Title </label>
                <input type="text" id="meta_title" name="meta_title"  class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" value="@isset($page){{$page->meta_title}}@endisset" placeholder="Enter Meta Title">
            </div>

            <div class="mb-[20px]">
                <label for="meta_keywords" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Meta Keywords</label>
                <input type="text" id="meta_keywords" name="meta_keywords"  class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"  value="@isset($page){{$page->meta_keywords}}@endisset" placeholder="Enter Meta Keywords">
            </div>

            <!-- Short Description -->
            <div class="mb-[20px]">
               <label for="meta_description" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Meta Description </label>
               <textarea id="meta_description" name="meta_description" rows="3"  class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary summernote" placeholder="Meta description...">@isset($page){{$page->meta_description}}@endisset</textarea>
            </div>

            <!-- Status Radio Buttons -->
            <div class="mb-[15px]">
                <label for="status" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="flex flex-wrap items-center gap-[10px]">
                    <!--First radio-->
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio"  id="statusRadio1" value="1" name="status" autocompleted="" {{$page->status == 1 ? 'checked' : ''}}>
                       <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio1">Enable</label>
                    </div>
                    <!--Second radio-->
                    <div class="mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                       <input class="relative ltr:float-left rtl:float-right -ms-[1.5rem] me-1 mt-0.5 h-[18px] w-[18px] appearance-none rounded-full border-1 border-solid border-normal before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary" type="radio" id="statusRadio2" value="0" name="status" autocompleted="" {{$page->status == 0 ? 'checked' : ''}}>
                       <label class="mt-px inline-block pl-[0.15rem] hover:cursor-pointer" for="statusRadio2">Disable</label>
                    </div>
                 </div>
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
       
      });
   });
</script>
@endpush