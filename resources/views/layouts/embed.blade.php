<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta name="copyright" content="{{config('app.url')}}" />
    <meta name="author" content="Anton Bourtnik" />

    <meta name="theme-color" content="#FF6174" />
    <meta name="msapplication-navbutton-color" content="#FF6174" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#FF6174" />

    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/icons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/icons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/icons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/manifest.json')}}"/>

    <meta name="robots" content="noindex">

    @vite(['resources/js/app.js'])
</head>
<body class="w-100 h-100">
<div id="player" class="w-100 h-100 position-relative bg-dark">
    <div class="position-absolute top-50 w-100 start-50 translate-middle text-white d-flex gap-4 align-items-center justify-content-center">
        <i class="fa-solid fa-circle-exclamation fa-4x"></i>
        <span class="fs-4">@yield('message')</span>
    </div>
    <a href="{{route('pages.home')}}" class="position-absolute text-white bg-dark bg-opacity-75 bottom-0 right-0 p-2 text-decoration-none text-sm">
        {{config('app.name')}}
    </a>
</div>
</body>
</html>
