@extends('layouts.default')

@section('title', __($playlist->title))

@section('content')
    <div class="row justify-content-center gy-3">
        <div class="col-md-12 col-lg-5 col-xl-4 col-xxl-4 text-black">
            <div class="text-black h-100 card">
                @if($playlist->first_video)
                    <img class="img-fluid card-img-top" src="{{$playlist->first_video->thumbnail_url}}" alt="{{$playlist->title}}">
                @endif
                <div class="card-body">
                    <h2 class="h4 my-3">{{__($playlist->title)}}</h2>
                    <div class="mt-3">{{ __('by') }} <a class="text-decoration-none fw-bold " href="{{$playlist->user->route}}">{{$playlist->user->username}}</a> </div>
                    <span class="badge bg-{{$playlist->status->color()}} my-3">
                        <i class="fa-solid fa-{{$playlist->status->icon()}}"></i>&nbsp;
                        {{__($playlist->status->name())}}
                    </span>
                    <div class="d-flex align-items-center gap-1 mb-2">
                        <div class="text-sm">{{trans_choice('videos', $playlist->videos_count)}}</div>
                        <div class="text-sm">•</div>
                        <div class="text-sm">{{ __('Created') }} {{$playlist->created_at->diffForHumans()}}</div>
                    </div>
                    <div class="text-sm mb-3">{{ __('Updated') }} {{$playlist->updated_at->diffForHumans()}}</div>
                    @if($actions->count() && $playlist->videos_count)
                        <div class="d-flex gap-2">
                            @foreach($actions as $label => $route)
                                <form action="{{$route}}" method="POST">
                                    @csrf()
                                    <button class="btn btn-primary btn-sm" type="submit">{{$label}}</button>
                                </form>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-3">
                        <x-expand-item max="150" color="dark">
                            {{$playlist->description}}
                        </x-expand-item>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-7 col-xl-8 col-xxl-8">
            <div class="card card-body p-0">
                @if($playlist->videos_count)
                    <div class="overflow-auto" style="height: 700px">
                        <ul class="list-group list-group-flush">
                            @foreach($playlist->videos as $key => $video)
                                <div class="d-flex gap-3 align-items-center list-group-item list-group-item-action px-2 px-sm-3 py-3 py-sm-3">
                                    @if($video->user->is(Auth::user()) || $video->is_public)
                                        @include('videos.card-secondary', ['video' => $video, 'playlist_video' => true])
                                    @else
                                        <div class="w-100 align-items-center">
                                            <div class="col-12">
                                                <div class="bg-secondary text-white d-flex flex-column justify-content-center align-items-center w-100 gap-2" style="height: 100px">
                                                    <i class="fa-solid fa-lock fa-1x"></i>
                                                    <div class="text-center text-sm">
                                                        <div class='text-sm fw-bold'>{{ __('This video is private') }}</div>
                                                        <div class='text-sm'>{{ __('The author update video status to private') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        {{ __('This playlist does not contain any videos at the moment.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
