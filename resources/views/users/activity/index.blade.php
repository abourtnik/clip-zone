@use('App\Filters\Forms\User\ActivityFiltersForm')

@extends('layouts.user')

@section('title', __('My Activity'))

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>{{ __('My Activity') }}</h2>
    </div>
    <hr>
    {!! form(FormBuilder::create(ActivityFiltersForm::class)) !!}
    <div class="row">
        <div class="col-xl-8 order-last order-xl-first">
            @if($activities->total() || request()->all())
                @forelse($activities as $activity)
                    @include('users.activity.types.'. $activity->type)
                @empty
                    <div class="card">
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <div class="my-3 text-center">
                                <i class="fa-solid fa-bars-staggered fa-2x my-3"></i>
                                <p class="fw-bold">{{ __('No matching activity for selected filters') }}</p>
                            </div>
                        </div>
                    </div>
                @endforelse
                {{ $activities->links() }}
            @else
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <div class="my-3 text-center">
                            <i class="fa-solid fa-eye-slash fa-2x"></i>
                            <h5 class="my-3">{{ __('No activity yet') }}</h5>
                            <div class="text-muted my-3">{{ __('Start to interact with your first video') }}</div>
                            <a href="{{route('pages.home')}}" class="btn btn-success">
                                <i class="fa-solid fa-play"></i>&nbsp;
                                {{ __('Start interact') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xl-4 order-first order-xl-last mb-3 mb-xl-0">
            <div class="card card-body">
                <h5 class="text-primary">{{ __('Statistics') }}</h5>
                <hr>
                <ul class="list-group list-group-flush border-top-0">
                    <li class="list-group-item ps-0 py-3">
                        <div class="row">
                            <div class="col-12 col-sm d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="rounded-circle text-white px-2 py-1 bg-success">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </div>
                                <span>{{ __('Liked videos') }} : {{$user->video_likes_count}}</span>
                            </div>
                            <div class="col-12 col-sm d-flex align-items-center gap-3">
                                <div class="rounded-circle text-white px-2 py-1 bg-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </div>
                                <span>{{ __('Disliked videos') }} : {{$user->video_dislikes_count}}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item ps-0 py-3">
                        <div class="row">
                            <div class="col-12 col-sm d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="rounded-circle text-white px-2 py-1 bg-success">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </div>
                                <span>{{ __('Liked comments') }} : {{$user->comment_likes_count}}</span>
                            </div>
                            <div class="col-12 col-sm d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="rounded-circle text-white px-2 py-1 bg-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </div>
                                <span>{{ __('Disliked comments') }} : {{$user->comment_dislikes_count}}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item ps-0 d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle text-white px-2 py-1 bg-primary">
                                <i class="fa-solid fa-comment"></i>
                            </div>
                            <span>{{ __('Comments') }} : {{$user->comments_count}}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
