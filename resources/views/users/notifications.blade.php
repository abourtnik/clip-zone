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
                    <th scope="col" style="width: 10%">Date</th>
                    <th scope="col" style="width: 20%">Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($user_notifications as $notification)
                        <tr class="bg-light" id="notification-{{$notification->id}}" x-data="{is_read:{{$notification->is_read ? 'true' : 'false'}}}">
                            <td>
                                <a href="{{$notification->url}}" class="d-flex align-items-center gap-3 text-decoration-none text-black">
                                   <span x-show="!is_read" class="bg-primary rounded-circle" style="width: 10px;height: 10px"></span>
                                    <x-expand-item>
                                        {!! $notification->message !!}
                                    </x-expand-item>
                                </a>
                            </td>
                            <td class="align-middle">
                                <div data-bs-toggle="tooltip" data-bs-title="{{$notification->created_at->format('d F Y - H:i')}}">
                                    {{$notification->created_at->diffForHumans()}}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <button x-show="!is_read"  @click="is_read=true" hx-get="{{route('notifications.read', $notification)}}" class="btn btn-primary btn-sm" type="button">
                                        Mark as read
                                    </button>
                                    <button x-show="is_read"  @click="is_read=false" hx-get="{{route('notifications.unread', $notification)}}" class="btn btn-primary btn-sm" type="button">
                                        Mark as unread
                                    </button>
                                    <button @click="document.getElementById('notification-{{$notification->id}}').remove()" hx-delete="{{route('notifications.remove', $notification)}}" hx-headers='{{json_encode(['Accept' =>'application/json', 'X-CSRF-TOKEN' => csrf_token() ])}}' class="btn btn-danger btn-sm" type="button">
                                        Delete
                                    </button>
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
