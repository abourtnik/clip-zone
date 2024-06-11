@use(App\Models\Video)

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{$video->title}} | {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{Str::limit($video->description, 155)}}"/>
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta name="copyright" content="{{config('app.url')}}" />
    <meta name="author" content="Anton Bourtnik" />

    <meta name="theme-color" content="#FF6174" />
    <meta name="msapplication-navbutton-color" content="#FF6174" />
    <meta name="apple-mobile-web-app-status-bar-style" content="#FF6174" />

    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:title" content="{{$video->title}} - {{config('app.name')}}" />
    <meta property="og:description" content="{{Str::limit($video->description, 155)}}" />
    <meta property="og:image" content="{{asset('images/logo.png')}}" />
    <meta property="og:language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/icons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/icons/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/icons/favicon-16x16.png')}}">

    <meta name="robots" content="noindex">

    <style>
        #background {
            background-image: url({{$video->thumbnail_url}});
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body class="w-100 h-100" x-data="{player:true, share: false, title: true}">
    <div id="player" class="w-100 h-100 position-relative" @mouseover="title = true" @mouseleave="!document.getElementById('video').paused ? title = false : null">
        <div id="background" class="w-100 h-100" x-show.important="player"></div>
        <div class="position-absolute top-0 right-0 w-100 h-100 bg-dark bg-opacity-25"></div>
        <button class="position-absolute top-50 start-50 translate-middle bg-transparent text-decoration-none" @click="player = false" x-show.important="!share && player">
            <i class="fa-solid fa-play-circle fa-4x text-white"></i>
        </button>
        <div class="position-absolute w-100 top-0 d-flex justify-content-between align-items-center p-3 z-1" x-show.important="!share && title">
            <div class="d-flex gap-2 align-items-center">
                <a href="{{$video->user->route}}" class="text-decoration-none">
                    <img class="rounded-circle" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 48px;height: 48px;">
                </a>
                <a href="{{$video->route}}" class="text-white text-decoration-none">
                    {{$video->title}}
                </a>
            </div>
            <div>
                <button class="text-white d-flex flex-column gap-1 align-items-center bg-transparent text-decoration-none" @click="share = true">
                    <i class="fa-solid fa-share"></i>
                    <span>Share</span>
                </button>
            </div>
        </div>
        <div class="ratio ratio-16x9 w-100 h-100" x-show.important="!player">
            <video id="video" :controls="!share" class="w-100 border border-1 rounded" controlsList="nodownload" oncontextmenu="return false;" :autoplay="!player">
                <source src="{{$video->file_url}}" type="{{Video::MIMETYPE}}">
            </video>
        </div>
        <a href="{{$video->route}}" class="position-absolute text-white bg-dark bg-opacity-75 bottom-0 p-2 text-decoration-none mb-2" x-show.important="player">
            Watch on {{config('app.name')}}
        </a>
    </div>
    <div x-show.important="share" class="position-absolute top-0 right-0 w-100 h-100 bg-dark bg-opacity-75 d-flex align-items-center justify-content-center">
        <button class="bg-transparent" @click="share = false">
            <i class="fa-solid fa-xmark text-white position-absolute top-0 left-95 mt-3"></i>
        </button>
        <div class="text-white text-center z-1">
            <p class="fs-5">Share</p>
            <a href="{{$video->route}}" class="fs-5 text-decoration-none text-white">{{$video->route}}</a>
            <div class="d-flex flex-wrap justify-content-center mt-3" style="row-gap: 1rem !important;">
                <a target="_blank" href="https://www.facebook.com/share.php?u={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                    <i class="fa fa-circle fa-stack-2x" style="color: #3A5997;"></i>
                    <i class="fa-brands fa-facebook fa-stack-1x fa-inverse"></i>
                </a>
                <a target="_blank" href="https://api.whatsapp.com/send/?text={{$video->route}}&type=custom_url&app_absent=0" class="fa-stack fa-2x" style="font-size: 1.6em">
                    <i class="fa fa-circle fa-stack-2x" style="color: #22D365;"></i>
                    <i class="fa-brands fa-whatsapp fa-stack-1x fa-inverse"></i>
                </a>
                <a target="_blank" href="https://twitter.com/share?text={{$video->title}}&url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                    <i class="fa fa-circle fa-stack-2x" style="color: #1EA1F1;"></i>
                    <i class="fa-brands fa-twitter fa-stack-1x fa-inverse"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
