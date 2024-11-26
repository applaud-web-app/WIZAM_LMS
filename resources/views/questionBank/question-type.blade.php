@extends('layouts.master')

@section('title', 'Question Types')

@section('content')

<section class="mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-start items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">Question Types</h4>
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
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">Question Types</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>
      </div>
   </div>

   <!-- Question Types Table Section -->
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
   
      <div class="p-[25px] pt-[15px]">
         <div class="table-responsive" >
            <table class="min-w-full leading-normal table-auto display">
               <thead>
                  <tr>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">#</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Name</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Short Description</th>
                     <th class="text-left px-4 py-2 border-b dark:border-box-dark-up">Status</th>
                  </tr>
               </thead>
               <tbody>
                    @isset($questionType)
                        @foreach ($questionType as $item)
                            <tr class="border-b dark:border-box-dark-up">
                                <td class="px-4 py-3">{{$loop->index+1}}</td>
                                <td class="px-4 py-3">{{$item->name}}</td>
                                <td class="px-4 py-3" width="60%">{{$item->description}}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $color = $item->status == 1 ? "success" : "danger";
                                    @endphp
                                    <span class="bg-{{$color}}/10 capitalize font-medium inline-flex items-center justify-center min-h-[24px] px-3 rounded-[15px] text-{{$color}} text-xs">
                                        {{$item->status == 1 ? "Active" : "Inactive"}}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
               </tbody>
            </table>
         </div>
      </div>
   </div>

</section>

@endsection
