<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('menu.print')</title>
</head>
<body>
    <div>
        <h1>@lang('menu.title')</h1>
        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam dolor aliquam, expedita doloremque excepturi dolorum quod cumque sequi! Odit ut illum nobis dolorum ullam facere nihil neque modi? Esse, soluta.
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nobis ab quia officia dolorum ea accusamus ut laborum praesentium sequi minus, a maiores nulla minima obcaecati laboriosam labore. Provident, at voluptates.
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Doloribus totam eum est maxime incidunt, voluptatem beatae pariatur unde. Facere laudantium odit consequatur libero tenetur consectetur, distinctio at. Deserunt, nostrum alias.
        </p>

        <ul>
            @foreach($users as $user)
                <li>{{ $user['id'] }} = {{ $user['name'] }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>
