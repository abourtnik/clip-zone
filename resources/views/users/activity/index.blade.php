@use('App\Filters\Forms\User\ActivityFiltersForm')

@extends('layouts.user')

@section('title', 'Channel Activity')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>My Activity</h2>
    </div>
    <hr>
    {!! form(FormBuilder::create(ActivityFiltersForm::class)) !!}
    <div class="row">
        <div class="col-xl-8 order-last order-xl-first">
            <div class="card card-body">
                <h5 class="text-primary">Activity</h5>
                <hr>
                @if($activities->total() || request()->all())
                    @forelse($activities as $activity)
                        @include('users.activity.types.'. $activity->type)
                    @empty
                        <div class="card">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <div class="my-3 text-center">
                                    <i class="fa-solid fa-bars-staggered fa-2x my-3"></i>
                                    <p class="fw-bold">No matching activity for selected filters</p>
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
                                <h5 class="my-3">No activity yet</h5>
                                <div class="text-muted my-3">Start to interact with your first video</div>
                                <a href="{{route('pages.home')}}" class="btn btn-success">
                                    <i class="fa-solid fa-play"></i>&nbsp;
                                    Start interaction
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-xl-4 order-first order-xl-last mb-3 mb-xl-0">
            <div class="card card-body">
                <h5 class="text-primary">Statistics</h5>
                <hr>
                <ul class="list-group list-group-flush border-top-0">
                    <li class="list-group-item ps-0 py-3">
                        <div class="row">
                            <div class="col-12 col-sm d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="rounded-circle text-white px-2 py-1 bg-success">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </div>
                                <span>Video Likes : {{$user->video_likes_count}}</span>
                            </div>
                            <div class="col-12 col-sm d-flex align-items-center gap-3">
                                <div class="rounded-circle text-white px-2 py-1 bg-success">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </div>
                                <span>Comment Likes : {{$user->comment_likes_count}}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item ps-0 py-3">
                        <div class="row">
                            <div class="col-12 col-sm d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="rounded-circle text-white px-2 py-1 bg-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </div>
                                <span>Video Dislikes : {{$user->video_dislikes_count}}</span>
                            </div>
                            <div class="col-12 col-sm d-flex align-items-center gap-3 mb-3 mb-sm-0">
                                <div class="rounded-circle text-white px-2 py-1 bg-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </div>
                                <span>Comment Dislikes : {{$user->comment_dislikes_count}}</span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item ps-0 d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle text-white px-2 py-1 bg-primary">
                                <i class="fa-solid fa-comment"></i>
                            </div>
                            <span>Comments : {{$user->comments_count}}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
