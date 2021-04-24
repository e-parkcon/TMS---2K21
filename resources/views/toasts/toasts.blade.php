@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <script>
            toastr.error("{{ $error }}")
        </script>
    @endforeach
@endif
  
@if(session('success'))
    <script>
        toastr.success("{{ session('success') }}")
    </script>
@endif

@if(session('error'))
    <script>
        toastr.error("{{ session('error') }}")
    </script>
@endif

@if(session('warning'))
    <script>
        toastr.warning("{{ session('warning') }}")
    </script>
@endif

@if(session('info'))
    <script>
        toastr.info("{{ session('info') }}")
    </script>
@endif