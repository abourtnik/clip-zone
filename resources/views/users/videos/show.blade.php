@extends('layouts.user')

@section('title', 'Video statistics')

@section('content')
    {{ Breadcrumbs::render('show_video', $video) }}
    <div class="row">
        <div class="col-xl-9 order-last order-xl-first mt-2 mt-xl-0">
            <div class="card shadow border-primary h-100">
                <div class="card-body">
                    <h4 class="card-title text-center text-primary mb-3">{{ __('Views Evolution') }}</h4>
                    @if($video->views)
                        <div class="d-block" style="height: 547px">
                            <x-chartjs-component :chart="$chart" />
                        </div>
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 600px">
                            <i class="fa-solid fa-eye-slash fa-2x"></i>
                            <h5 class="my-3">{{ __('No views yet') }}</h5>
                            <small class="text-muted">Some description</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-3 order-first order-xl-last">
            <div class="row g-2 h-100">
                <div class="col-12 col-sm-6 col-xl-12">
                    <div class="card shadow border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-center text-primary">{{ __('Uploaded') }}</h5>
                            <hr>
                            <div class="d-flex align-items-center justify-content-center gap-4">
                                <i class="fa-solid fa-upload fa-2x"></i>
                                <p class="card-text text-center fs-5">{{$video->created_at->translatedFormat('d M Y \a\t H:i')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-12">
                    <div class="card shadow border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-center text-primary">{{ __('Views') }}</h5>
                            <hr>
                            <div class="d-flex align-items-center justify-content-center gap-4">
                                <i class="fa-solid fa-eye fa-2x"></i>
                                <p class="card-text text-center fs-3">{{$video->views}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-12">
                    <div class="card shadow border-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title text-center text-primary">{{ __('Comments') }}</h5>
                            <hr>
                            <div class="d-flex align-items-center justify-content-center gap-4">
                                <i class="fa-solid fa-comment fa-2x"></i>
                                <p class="card-text text-center fs-2">{{$video->comments_count}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-12">
                    <div class="card shadow border-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title text-center text-primary">{{ __('Interactions') }}</h5>
                            <hr>
                            <div class="d-flex align-items-center justify-content-center gap-5">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-thumbs-up fa-2x"></i>
                                    <p class="card-text text-center fs-2">{{$video->likes_count}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="fa-solid fa-thumbs-down fa-2x"></i>
                                    <p class="card-text text-center fs-2">{{$video->dislikes_count}}</p>
                                </div>
                            </div>
                            <div class="progress m-auto my-4" style="height: 20px">
                                <div
                                    class="progress-bar bg-success {{ ($video->likes_ratio >= $video->dislikes_ratio) ? 'fw-bold' : '' }}"
                                    role="progressbar"
                                    aria-label="Likes ratio"
                                    style="width: {{$video->likes_ratio}}%;"
                                    aria-valuenow="{{$video->likes_ratio}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{$video->likes_ratio}}%
                                </div>
                                <div
                                    class="progress-bar bg-danger {{ ($video->dislikes_ratio >= $video->likes_ratio) ? 'fw-bold' : '' }}"
                                    role="progressbar"
                                    aria-label="Dislikes ratio"
                                    style="width: {{$video->dislikes_ratio}}%"
                                    aria-valuenow="{{$video->dislikes_ratio}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{$video->dislikes_ratio}}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
