<?php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Nikah App') }}</title>
<link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">

<!-- Fonts -->
<link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
<link href="{{asset('assets/css/responsive.css')}}" rel="styleshee">

<!-- Scripts -->
@routes
@viteReactRefresh
@vite('resources/js/app.jsx')
{{--@inertiaHead--}}
</head>
<body class="font-sans antialiased">
<div class="alert alert-danger">
    <p>Payment Failed</p>
</div>
</body>
</html>
