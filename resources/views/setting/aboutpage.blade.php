@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-center items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Add Page</h4>
            <div class="flex flex-wrap justify-center">
               <nav>
                  <ol class="flex flex-wrap p-0 mb-0 list-none gap-[8px] max-sm:justify-center">
                     <li class="inline-flex items-center">
                        <a class="text-[14px] font-normal leading-[20px] text-body dark:text-neutral-200 hover:text-primary group" href="{{route('admin-dashboard')}}">
                           <i class="uil uil-estate text-light dark:text-white/50 me-[8px] text-[16px] group-hover:text-current"></i>Dashboard</a>
                     </li>
                     <li class="inline-flex items-center before:content-[''] before:w-1 before:h-1 before:ltr:float-left rtl:float-right before:bg-light-extra before:me-[7px] before:pe-0 before:rounded-[50%]" aria-current="page">
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Add Page</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>

   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="p-[25px]">
         <form action="{{route('store-about-page')}}" method="POST" enctype="multipart/form-data" autocomplete="off">
            @csrf
            <div class="mb-[20px]">
               <label for="editor" class="block text-sm font-medium text-body dark:text-title-dark mb-[5px]">Page Content <span class="text-red-500">*</span></label>
               <textarea id="editor" name="content" rows="10" required class="w-full rounded-4 border-1 border-normal text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="Write your page content...">@isset($about->description){{$about->description}}@endisset</textarea>
            </div>
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

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>

<script>
   document.addEventListener('DOMContentLoaded', function () {
       CKEDITOR.replace('editor', {
           height: 350,
           removePlugins: 'image',
           toolbar: [
               { name: 'document', items: ['Source', '-', 'Preview'] },
               { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
               { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
               { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', '-', 'RemoveFormat'] },
               { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
               { name: 'insert', items: ['Link', 'Unlink', 'Table'] },
               { name: 'styles', items: ['Styles', 'Format'] },
               { name: 'tools', items: ['Maximize'] }
           ]
       });
   });
</script>

@endpush
