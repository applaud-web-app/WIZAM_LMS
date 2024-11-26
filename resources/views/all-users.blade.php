@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
   
<section class=" mx-[12px] lg:mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">


   <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">

         <!-- Breadcrumb Section -->
         <div class="leading-[1.8571428571] flex flex-wrap sm:justify-between justify-start items-center ssm:mb-[24px] mb-[18px] max-sm:flex-col gap-x-[15px] gap-y-[5px]">
            <!-- Title -->
            <h4 class="capitalize text-[20px] text-dark dark:text-title-dark font-semibold">All Users</h4>
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
                        <span class="text-[14px] font-normal leading-[20px] flex items-center capitalize text-light dark:text-subtitle-dark">All Users</span>
                     </li>
                  </ol>
               </nav>
            </div>
         </div>

      </div>
   </div>
 
   <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
      <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between  max-sm:h-auto border-b border-regular dark:border-box-dark-up">
         <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
            25 User</h1>
            <div class="flex items-center gap-x-[10px] gap-y-[5px]">
               <button type="button" class="flex items-center px-[14px] text-sm hover:text-white hover:bg-secondary text-secondary rounded-md font-normal bg-secondary/10 border-primary h-10 gap-[6px] transition-[0.3s]">
                  <span class="m-0">Export</span>
               </button>
               <button type="button" class="flex items-center px-[14px] text-sm text-white rounded-md  bg-primary border-primary h-10 gap-[6px] transition-[0.3s]">
                  <i class="uil uil-plus"></i>
                  <span class="m-0">Add user</span>
               </button>
            </div>
      </div>
      <div class="p-[25px] pt-[15px]">

         <div class="table-responsive" >
            <table class="min-w-full leading-normal table-auto display">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Name</th>
                     <th>Position</th>
                     <th>Office</th>
                     <th>Age</th>
                     <th>Start date</th>
                     <th>Salary</th>
                     <th>Action</th>
                     
                  </tr>
               </thead>
               <tbody>

                  @for ($i = 1; $i < 55; $i++)
                  <tr>
                     <td>{{$i}}</td>
                     <td>Tiger Nixon</td>
                     <td>System Architect</td>
                     <td>Edinburgh</td>
                     <td>61</td>
                     <td>2011/04/25</td>
                     <td>$320,800</td>
                     <td>
                        <div class="text-light dark:text-subtitle-dark text-[19px]  flex items-center justify-start p-0 m-0 gap-[20px]">
                           <a href="" class="cursor-pointer edit-task-title uil uil-eye hover:text-primary"></a>
                           <a href="" class="cursor-pointer edit-task-title uil uil-edit-alt hover:text-info"></a>
                           <a href="" class="cursor-pointer remove-task-wrapper uil uil-trash-alt hover:text-danger"></a>
                        </div>
                     </td>
                  </tr>
                  @endfor
                 
                
               </tbody>
            </table>
         </div>

      </div>
   </div>

</section>

@endsection
