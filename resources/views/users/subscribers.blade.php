@extends('layouts.user')

@section('content')
    @if($subscribers->total() || $filters)
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Subscribers</h2>
        </div>
        <hr>
        <form class="my-4 d-flex gap-3 align-items-end" method="GET">
            <div class="col">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col">
                <label for="subscription_date_start" class="form-label fw-bold">Subscription date start</label>
                <input type="datetime-local" name="date[]" class="form-control" id="subscription_date_start" value="{{$filters['date'][0] ?? null}}">
            </div>
            <div class="col">
                <label for="subscription_date_end" class="form-label fw-bold">Subscription date end</label>
                <input type="datetime-local" name="date[]" class="form-control" id="subscription_date_end" value="{{$filters['date'][1] ?? null}}">
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
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
            @forelse($subscribers as $subscriber)
                <tr class="bg-light">
                    <td>
                        <a href="{{route('pages.user', $subscriber)}}" class="d-flex align-items-center gap-2 text-decoration-none">
                            <img class="rounded" src="{{$subscriber->avatar_url}}" alt="{{$subscriber->username}} avatar" style="width: 50px;">
                            <span>{{$subscriber->username}}</span>
                        </a>
                    </td>
                    <td class="align-middle">
                        {{$subscriber->pivot->subscribe_at->format('d F Y - H:i')}}
                    </td>
                    <td class="align-middle">
                        {{trans_choice('subscribers', $subscriber->subscribers_count)}}
                    </td>
                    <td class="align-middle">{{$subscriber->created_at->format('d F Y - H:i')}}</td>
                    <td class="align-middle">
                        <subscribe-button
                            @if(!$subscriber->is_subscribe_to_current_user) is-subscribe @endif
                            user="{{$subscriber->id}}"
                            size="sm"
                        />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <i class="fa-solid fa-database fa-2x my-3"></i>
                        <p class="fw-bold">No matching subscribers</p>
                    </td>
                </tr>
            @endforelse
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
