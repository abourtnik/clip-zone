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
        <div class="col-xl-9 order-last order-xl-first">
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
        <div class="col-xl-3 order-first order-xl-last mb-3 mb-xl-0">
            <div class="card card-body">
                <h5 class="text-primary">{{ __('Statistics') }}</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item ps-0">
                        <span>{{ __('Liked videos') }} : {{$user->video_likes_count}}</span>
                    </li>
                    <li class="list-group-item ps-0">
                        <span>{{ __('Disliked videos') }} : {{$user->video_likes_count}}</span>
                    </li>
                    <li class="list-group-item ps-0">
                        <span>{{ __('Liked comments') }} : {{$user->video_likes_count}}</span>
                    </li>
                    <li class="list-group-item ps-0">
                        <span>{{ __('Disliked comments') }} : {{$user->video_likes_count}}</span>
                    </li>
                    <li class="list-group-item ps-0">
                        <span>{{ __('Comments') }} : {{$user->video_likes_count}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
