<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ auth()->user()->username }} - {{config('app.name')}}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <main class="h-100 overflow-auto">
        @include('layouts.parts.header')
        <div class="admin-content d-flex overflow-hidden">
            <nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
                @include('layouts.parts.sidebar', ['type' => 'user'])
            </nav>
            <div class="container-fluid mt-3" style="margin-left: 240px">
                @yield('content')
            </div>
        </div>
    </main>
</body>
</html>
