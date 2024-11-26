@extends('layouts.master')
@section('title', 'Wizam : Users')
@section('content')
    <section class="mx-4 md:mx-8 min-h-[calc(100vh-195px)] mb-8 mt-4">
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12">
                <!-- Breadcrumb Section -->
                <div class="flex flex-wrap justify-between items-center mb-6">
                    <!-- Title -->
                    <h4 class="text-xl font-semibold text-dark dark:text-title-dark">All Students</h4>
                    <!-- Breadcrumb Navigation -->
                    <div>
                        <nav>
                            <ol class="flex flex-wrap p-0 mb-0 list-none gap-2">
                                <!-- Parent Link -->
                                <li class="inline-flex items-center">
                                    <a class="text-sm font-normal text-body dark:text-neutral-200 hover:text-primary"
                                        href="{{ route('admin-dashboard') }}">
                                        <i class="uil uil-estate text-light dark:text-white/50 mr-2 text-lg"></i>Dashboard</a>
                                </li>
                                <!-- Child (Current Page) -->
                                <li class="inline-flex items-center" aria-current="page">
                                    <span class="text-sm font-normal flex items-center text-light dark:text-subtitle-dark">All
                                        Students</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-box-dark p-6 rounded-lg relative h-full">
            <div class="flex flex-wrap items-center justify-between border-b pb-4 mb-4">
                <h1 class="text-lg font-semibold text-dark dark:text-title-dark">Students</h1>
                <div class="flex items-center gap-4">
                    <a href="{{ route('add-student') }}"
                        class="flex items-center px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-600 transition">
                        <i class="uil uil-plus mr-2"></i>
                        Add Student
                    </a>
                </div>
            </div>
            <!-- Table Container -->
            <div class="table-responsive" >
                <table id="userTable" class="w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Reg. Date</th>
                            <th class="px-4 py-2">Id</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Country</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                        <tr class="bg-gray-100">
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"><input type="text" class="w-full px-2 py-1 border rounded text-dark column-search" placeholder="Search ID" data-column="2" /></td>
                            <td class="px-4 py-2"><input type="text" class="w-full px-2 py-1 border rounded text-dark column-search" placeholder="Search Name" data-column="3" /></td>
                            <td class="px-4 py-2"><input type="text" class="w-full px-2 py-1 border rounded text-dark column-search" placeholder="Search Email" data-column="4" /></td>
                            <td class="px-4 py-2"><input type="text" class="w-full px-2 py-1 border rounded text-dark column-search" placeholder="Search Country" data-column="6" /></td>
                            <td class="px-4 py-2"><input type="text" class="w-full px-2 py-1 border rounded text-dark column-search" placeholder="Search Role" data-column="7" /></td>
                            <td class="px-4 py-2">
                                <select class="w-full px-2 py-1 border rounded text-dark column-search" data-column="8">
                                    <option value="">All</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </td>
                            <td class="px-4 py-2"></td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>                    
            </div>
        </div>
    </section>

    <!-- Modal code remains unchanged -->
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        var table = $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true, // Enable responsiveness
            ajax: {
                url: '{{ route("student-manager") }}',
                data: function (d) {
                    // Collect the values from the inputs
                    d.columns_search = {};
                    $('.column-search').each(function() {
                        var columnIndex = $(this).data('column');
                        d.columns_search[columnIndex] = $(this).val();
                    });
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'created_date', name: 'created_date', orderable: false, searchable: false},
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'country', name: 'country'},
                {data: 'role', name: 'role'},
                {data: 'status', name: 'status', orderable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            rowCallback: function(row, data) {
                $(row).attr('id', 'row' + data.id);
            }
        });

        // Apply the search
        $('.column-search').on('keyup change', function() {
            table.draw();
        });
    });

</script>
<script>
    $(document).ready(function(){
        // When delete item is clicked, store the URL in the confirm button
        $(document).on('click', '.deleteItem', function(){
            const delUrl = $(this).data('url');
            $('#confirmDelete').data('url', delUrl); // Use data method to set the URL
        });

        // When confirm delete is clicked, redirect to the URL
        $(document).on('click', '#confirmDelete', function(){
            const delUrl = $(this).data('url'); // Use data method to get the URL
            window.location.href = delUrl;
        });
    });
</script>
@endpush
