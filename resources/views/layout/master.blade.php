<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GABEN</title>
    <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}" />
</head>
<body>

@if(session()->has('STEAM_ID'))
    @include('logged.navbar')
@else
    @include('not-logged.navbar')
@endif
<div id="content-wrapper">
    <div class="container">
        @yield('content')
    </div>
</div>

</body>
</html>