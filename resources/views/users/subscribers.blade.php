@extends('layouts.user')

@section('content')
    {{ Breadcrumbs::render('subscribers') }}
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
        @foreach($user->subscribers as $subscriber)
            <tr class="bg-light">
                <td>
                    <a href="{{route('pages.user', $subscriber)}}" class="d-flex align-items-center gap-2">
                        <img class="rounded" src="{{$subscriber->avatar_url}}" alt="{{$subscriber->username}} avatar" style="width: 50px;">
                        <span>{{$subscriber->username}}</span>
                    </a>
                </td>
                <td>{{$subscriber->suscribe_at}}</td>
                <td>{{$subscriber->subscribers_count}}</td>
                <td>{{$subscriber->created_at}}</td>
                <td>
                    <subscribe-button isSubscribe="{{$user->isSubscribe($subscriber) ? 'true' : 'false'}}" user="{{$user->id}}"/>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
