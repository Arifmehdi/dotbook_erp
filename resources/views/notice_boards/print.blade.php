<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('menu.notice_board')</title>
</head>

<body>
    <h1>{{ $notice->title }}</h1>
    <p>
        {!! $notice->description !!}
    </p>
    <hr>
    <div>
        <img src="{{ asset('uploads/notice/' . $notice->files) }} " height="300" width="100%" />
    </div>
</body>

</html>
