@if (session('success'))
<script>
    Swal.fire({
        title: "Yaay!",
        text: "{{ session('success') }}",
        icon: "success"
    });
</script>
@endif

@if (session('info'))
<script>
    Swal.fire({
        title: "Upps!",
        text: "{{ session('info') }}",
        icon: "info"
    });
</script>
@endif
