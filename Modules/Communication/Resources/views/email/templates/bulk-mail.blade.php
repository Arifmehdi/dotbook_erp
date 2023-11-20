<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $bulkMailData['subject']}}</title>
</head>
<body>
   <h1>{{ $bulkMailData['subject']}}</h1>
   <div> {!! $bulkMailData['body'] !!}</div>
   <hr>
   <img src="{{ asset('images/logo.png') }}" alt="any text for alt attribute" width="200"  />
</body>
</html>
