<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')"/>
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta name="copyright" content="//youtube.antonbourtnik.fr" />
    <meta name="author" content="Anton Bourtnik" />

    <meta name="theme-color" content="#FF6174" />
    <meta name="msapplication-navbutton-color" content="#FF6174" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#FF6174" />

    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:title" content="@yield('title') - {{config('app.name')}}" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image" content="/logo.png" />
    <meta property="og:language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icons/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json" />

    @vite(['resources/js/app.js'])
</head>
<body>
    <main class="h-100 overflow-auto">
        @include('layouts.menus.header')
        <div class="admin-content d-flex">
            @include('layouts.menus.front')
            <div id="main-container" class="container-fluid my-3 @yield('class')" style="@yield('style')">
                @yield('content')
            </div>
        </div>
    </main>
    @include('layouts.menus.responsive')
    @include('users.videos.modals.create')
    @include('reports.modal')
    @stack('scripts')
</body>
</html>
