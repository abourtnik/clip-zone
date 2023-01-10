<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <main class="h-100 overflow-auto">
        @include('layouts.menus.header')
        <div class="admin-content d-flex">
            @include('layouts.menus.front')
            <div class="container-fluid my-3 @yield('class')" style="margin-left: 240px;height: calc(100vh - 56px - 2rem); @yield('style')">
                @yield('content')
            </div>
        </div>
    </main>
    @stack('scripts')
</body>
</html>
