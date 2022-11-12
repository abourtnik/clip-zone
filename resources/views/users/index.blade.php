@extends('layouts.user')

@section('content')
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Uploaded videos</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-video fa-2x"></i>
                        <p class="card-text text-center fs-1">{{auth()->user()->videos()->count()}}</p>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See all
                        </a>
                        <a href="{{route('user.videos.create')}}" class="btn btn-success">
                            <i class="fa-solid fa-upload"></i>
                            Import new video
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Subscribers</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-user fa-2x"></i>
                        <p class="card-text text-center fs-1">{{auth()->user()->subscribers_count}}</p>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See all
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Views</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-eye fa-2x"></i>
                        <p class="card-text text-center fs-1">10000</p>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Comments</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-comment fa-2x"></i>
                        <p class="card-text text-center fs-1">10000</p>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See details
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3 mt-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Interactions</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-thumbs-up fa-2x"></i>
                            <p class="card-text text-center fs-1">10000</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-thumbs-down fa-2x"></i>
                            <p class="card-text text-center fs-1">10000</p>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
