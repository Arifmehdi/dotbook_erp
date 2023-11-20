<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('menu.announcement')</title>
</head>

<body>
    <h1>{{ $announcement->title }}</h1>
    <p>
        {!! $announcement->description !!}
    </p>
    <hr>
    <div>
        <img src="{{ asset('uploads/announcement/' . $announcement->files) }} " height="300" width="100%" />
    </div>
</body>

</html>
