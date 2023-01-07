@extends('layouts.default')

@section('content')
    @if(!$subscriptions)
        <div class="alert alert-success">Begin with subscribe to your first channel</div>
        <div class="row"></div>
            @forelse($users as $user)
                <div class="col-2 card">
                    <div class="card-body d-flex flex-column gap-2 align-items-center">
                        <img style="width: 80px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                        <strong>{{$user->username}}</strong>
                        <div class="text-muted">{{trans_choice('subscribers', $user->subscribers_count)}}</div>
                        <subscribe-button isSubscribe="false" user="{{$user->id}}"/>
                    </div>
                </div>
            @empty
                <p>No users to subscribe</p>
            @endforelse
    @else
        @foreach($sorted_videos as $date => $videos)
            <h5>{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
            <div class="row">
                @each('videos.card', $videos, 'video')
            </div>
        @endforeach
    @endif
@endsection
