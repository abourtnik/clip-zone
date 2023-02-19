<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Admin</title>
    <script>
        window.User = {
            id : {{auth()->user()->id}}
        }
    </script>
    @vite(['resources/js/app.js'])
</head>
<body>
    <main class="h-100 overflow-auto">
        @include('layouts.headers.admin')
        <div class="admin-content d-flex">
            @include('layouts.menus.back', ['type' => 'admin'])
            <div id="main-container" class="container-fluid my-3 @yield('class')" style="@yield('style')">
                @yield('content')
            </div>
        </div>
    </main>
</body>
</html>
