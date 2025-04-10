<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
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
    <meta property="og:image" content="@yield('image', asset('images/logo.png'))" />
    <meta property="og:language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

    <link rel="icon" href="{{asset('/icons/favicon.ico')}}" sizes="any">
    <link rel="apple-touch-icon" href="{{asset('/icons/apple-touch-icon.png')}}">
    <link rel="manifest" href="/manifest.json" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($attributes->get('statistics', false) && config('app.statistics_enabled') && !auth()->user()?->is_admin)
    <script defer src="https://stats.antonbourtnik.fr/script.js" data-website-id="4023828e-765e-4411-b34b-66712803430b"></script>
    @endif

    @vite(['resources/js/app.ts'])
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
                        <strong id="toast-message" class="text-break"></strong>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        @if (Auth::check())
            @include('users.videos.modals.upload')
            @include('layouts.menus.account')
        @endif
    </main>
    <script type="text/javascript">
        window.USER = {!! Auth::check() ? Auth::user()->json  : 'null' !!}
        window.LANG = '{{app()->getLocale()}}'

        @if (Auth::check())
            document.addEventListener("DOMContentLoaded", function(event) {
                window.PRIVATE_CHANNEL = window.Echo.private(`App.Models.User.${window.USER.id}`);
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
