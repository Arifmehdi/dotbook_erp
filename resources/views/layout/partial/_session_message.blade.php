<script>
    // Rendering Session messages
    @if (Session::has('message') || Session::has('success') || Session::has('error') || Session::has('info') || Session::has('warning'))

        var text = "{{ session('successMsg') ?? (session('message')['text'] ?? (session('success') ?? (session('error') ?? (session('info') ?? session('warning'))))) }}";
        var posClass = "toast-{{ session('message')['position'] ?? 'top-center' }}";
        toastr.options.positionClass = posClass;

        var type = 'info';

        @if (isset(session('message')['type']))
            type = "{{ session('message')['type'] }}";
        @elseif (null !== session('success'))
            type = 'success';
        @elseif (null !== session('info'))
            type = 'info';
        @elseif (null !== session('error'))
            type = 'error';
        @elseif (null !== session('warning'))
            type = 'warning';
        @endif

        switch (type) {
            case 'info':
                toastr.info(text);
                break;
            case 'warning':
                toastr.warning(text);
                break;
            case 'success':
                toastr.success(text);
                break;
            case 'error':
                toastr.error(text);
                break;
            default:
                toastr.info(text);
                break;
        }
    @endif
</script>
