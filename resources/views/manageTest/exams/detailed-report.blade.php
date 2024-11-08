@extends('layouts.master')

@section('title', 'Add Exam')

@section('content')

<section class="mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">

    <div class="flex justify-between items-center mb-[30px]">
        <h2><b>{{$exam->title}} - Detailed Report</b></h2>
        <a href="{{route('overall-report',[$exam->id])}}" class="capitalize bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white dark:text-title-dark text-[14px] leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] transition duration-300 ease-in-out">Overall Report</a>
    </div>
    <div class="bg-white m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        <div class="p-[25px] pt-[15px]">
            <div>
               <table id="category-table" class="min-w-full leading-normal table-auto display">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>Task Taker</th>
                        <th>Completed On</th>
                        <th>Percentage</th>
                        <th>Status</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
</section>

@endsection
@push('scripts')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // DataTables initialization
        $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('detailed-report',[$exam->id]) }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                { data: 'task_taker', name: 'task_taker' },
                { data: 'completed_on', name: 'completed_on' },
                { data: 'percenatge', name: 'percenatge' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush