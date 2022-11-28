@extends('layouts.user')

@section('content')
    @if($subscribers->total())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Subscribers</h2>
        </div>
        <hr>
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th scope="col">Channel</th>
                <th scope="col">Date of subscription</th>
                <th scope="col">Subscribers count</th>
                <th scope="col">Registration date</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subscribers as $subscriber)
                <tr class="bg-light">
                    <td>
                        <a href="{{route('pages.user', $subscriber)}}" class="d-flex align-items-center gap-2">
                            <img class="rounded" src="{{$subscriber->avatar_url}}" alt="{{$subscriber->username}} avatar" style="width: 50px;">
                            <span>{{$subscriber->username}}</span>
                        </a>
                    </td>
                    <td>{{$subscriber->pivot->subscribe_at->format('d F Y H:i')}}</td>
                    <td>
                        {{trans_choice('subscribers', $subscriber->subscribers_count)}}
                    </td>
                    <td>{{$subscriber->created_at->format('d F Y H:i')}}</td>
                    <td>
                       <subscribe-button isSubscribe="{{$subscriber->is_subscribe_to_current_user ? 'true' : 'false'}}" user="{{$subscriber->id}}" size="sm"/>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $subscribers->links() }}
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-user-slash fa-2x"></i>
                    <h5 class="my-3">No subscriber yet</h5>
                    <div class="text-muted my-3">Upload more video for more subscribers</div>
                    <a href="{{route('user.videos.create')}}" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                        Upload new video
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
