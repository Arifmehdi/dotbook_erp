<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title')</title>
    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome6/css/all.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="{{ asset('css/selectize.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}"> --}}

    <link href="{{ asset('css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/typography.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/body.css') }}" rel="stylesheet" type="text/css">
    {{-- <link href="{{ asset('css/shCore.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/jquery.jqplot.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/jquery-ui-1.8.18.custom.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/data-table.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/form.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/ui-elements.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/wizard.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/sprite.css') }}" rel="stylesheet" type="text/css"> --}}
    {{-- <link href="{{ asset('css/gradient.css') }}" rel="stylesheet" type="text/css"> --}}
    <link rel="stylesheet" href="{{ asset('css/comon.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    @yield('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    @stack('scripts')
</body>

</html>
