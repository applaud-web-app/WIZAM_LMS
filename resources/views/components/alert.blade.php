@if (Session::has('success'))
    <script>
        iziToast.success({
            title: 'Success',
            position: 'topRight',
            message: "{{ session('success') }}",
        });
    </script>
@endif

@if (Session::has('error'))
    <script>
        iziToast.error({
            title: 'Error',
            position: 'topRight',
            message: "{{ session('error') }}",
        });
    </script>
@endif

@if (Session::has('warning'))
    <script>
        iziToast.warning({
            title: 'Warning',
            position: 'topRight',
            message: "{{ session('warning') }}",
        });
    </script>
@endif

@if ($errors->any())
    <script>
        iziToast.error({
            title: 'Validation Error',
            position: 'topRight',
            message: "{!! implode('<br>', $errors->all()) !!}",
        });
    </script>
@endif
