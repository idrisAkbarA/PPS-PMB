<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/nonSPA.css')}}">
    {{-- <link rel="icon" href="{{asset('/images/LogoUIN.png')}}"> --}}
    <title>Pasca Sarjana UIN Suska Riau</title>
</head>
<body>
    <h1>base</h1>
    <div>
        @yield('content')
    </div>

    <script src="{{ mix('js/nonSPA.js') }}"></script>
</body>
</html>