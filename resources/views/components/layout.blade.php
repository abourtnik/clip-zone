<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description')"/>
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta name="copyright" content="{{config('app.url')}}" />
    <meta name="author" content="Anton Bourtnik" />

    <meta name="theme-color" content="#FF6174" />
    <meta name="msapplication-navbutton-color" content="#FF6174" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#FF6174" />

    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:title" content="@yield('title') - {{config('app.name')}}" />
    <meta property="og:description" content="@yield('description')" />
    <meta property="og:image" content="{{asset('images/logo.png')}}" />
    <meta property="og:language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/icons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/icons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/icons/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('/manifest.json')}}"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth()
        <meta name="user_id" content="{{auth()->user()->id}}">
    @endauth

    @if ($attributes->get('statistics', false) && config('app.statistics_enabled'))
        <script async defer data-website-id="32676102-17e3-4534-9023-f3aa1eb8300a" src="https://stats.antonbourtnik.fr/umami.js"></script>
    @endif

    @vite(['resources/js/app.js'])
</head>
<body>
    <main class="h-100 overflow-auto position-relative">
        @include('layouts.menus.header')
        <div class="d-flex">
            @include('layouts.menus.sidebars.'.$sidebar, ['type' => $type ?? null])
            <div id="main-container" class="container-fluid my-3 @yield('class')" style="@yield('style')">
                {{$slot}}
                @yield('content')
            </div>
        </div>
        <div class="toast-container pe-4 end-0 position-fixed" style="top:68px;">
            <div id="toast" class="toast align-items-center bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="10000">
                <div class="d-flex align-items-center justify-content-between mr-2">
                    <div class="toast-body d-flex align-items-center gap-3 ">
                        <div class="rounded-4 px-2 py-1 bg-danger text-white">
                            <i class="fa-2x fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <strong id="toast-message"></strong>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @if (Auth::check())
            <site-notifications initial="{{$json_notifications}}"></site-notifications>
            @include('modals.report')
            @include('users.videos.modals.upload')
        @endif
    </main>
    @stack('scripts')
    <script>
        const LANG = '{{app()->getLocale()}}'
    </script>
</body>
</html>
