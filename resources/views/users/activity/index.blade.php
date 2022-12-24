@extends('layouts.user')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>My activity</h2>
    </div>
    <hr>
    @foreach($activity_log as $date => $log)
        <div class="row">
            <div class="col-1 d-flex flex-column justify-content-center align-items-center">
                <div class="alert alert-secondary p-1 mb-0">
                    {{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}
                </div>
                <div class="h-100 border border-secondary" style="width: 1px"></div>
            </div>
            <div class="col-11 mt-4">
                @foreach($log as $activity)
                    <div class="d-flex align-items-center position-relative">
                        <div class="position-absolute d-flex justify-content-center align-items-center rounded-circle bg-primary text-white p-2" style="width: 30px;height: 30px;left: -6.3%">
                            <i class="fa-solid fa-comment"></i>
                        </div>
                        <div class="my-4 card card-body">
                            <div class="d-flex gap-3">
                                <div class="w-25">
                                    <div class="d-flex gap-3">
                                        <strong>Liked</strong>
                                        <a href="#" class="text-decoration-none fw-bold">{{$activity->subject?->likeable?->title}}</a>
                                    </div>
                                    <a href="#" class="text-sm my-2 text-decoration-none d-block">
                                        {{$activity->subject?->likeable?->user->username}}
                                    </a>
                                    <small class="text-muted">{{$activity->created_at->format('H:i')}}</small>
                                </div>
                                <a href="">
                                    <img class="img-fluid" src="{{$activity->subject?->likeable?->poster_url}}" alt="{{$activity->subject?->likeable?->title}}" style="width: 120px;height: 68px">
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endsection
