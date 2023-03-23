@extends('layouts.user')

@section('title', 'Channel notifications')

@section('content')
    @if($user_notifications->total())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Notifications</h2>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col">Notification</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($user_notifications as $notification)
                    <tr class="bg-light">
                        <td>
                            <a href="{{$notification->url}}" class="d-flex align-items-center gap-3 text-decoration-none text-black">
                                @if(!$notification->is_read)
                                    <span class="bg-primary rounded-circle" style="width: 10px;height: 10px"></span>
                                @endif
                                {!! $notification->message !!}
                            </a>
                        </td>
                        <td class="align-middle">
                            <div data-bs-toggle="tooltip" data-bs-title="{{$notification->created_at->format('d F Y - H:i')}}">
                                {{$notification->created_at->diffForHumans()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1">
                                @if(!$notification->is_read)
                                    <a class="btn btn-primary btn-sm" href="{{route('user.notifications.read', $notification)}}">Mark as read</a>
                                @else
                                    <a class="btn btn-primary btn-sm" href="{{route('user.notifications.unread', $notification)}}">Mark as unread</a>
                                @endif
                                <form method="POST" action="{{route('user.notifications.remove', $notification)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center gap-1">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">No matching notifications</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $user_notifications->links() }}
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-bell-slash fa-2x"></i>
                    <h5 class="my-3">Your notifications live here</h5>
                    <div class="text-muted my-3">Subscribe to your favorite channels to get notified about their latest videos.</div>
                </div>
            </div>
        </div>
    @endif
@endsection
