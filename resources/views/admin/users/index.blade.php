@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Users</h2>
    </div>
    <hr>
    <form class="my-4 d-flex gap-3 align-items-end" method="GET">
        <div class="col">
            <label for="search" class="form-label fw-bold">Search</label>
            <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
        </div>
        <div class="col">
            <label for="registration_date_start" class="form-label fw-bold">Registration date start</label>
            <input type="datetime-local" name="date[]" class="form-control" id="registration_date_start" value="{{$filters['date'][0] ?? null}}">
        </div>
        <div class="col">
            <label for="registration_date_end" class="form-label fw-bold">Registration date end</label>
            <input type="datetime-local" name="date[]" class="form-control" id="registration_date_end" value="{{$filters['date'][1] ?? null}}">
        </div>
        <div class="col">
            <label for="ban" class="form-label fw-bold">Banned</label>
            <select name="ban" class="form-select" aria-label="Default select example">
                <option selected value="">All</option>
                @foreach(['banned' => 'Banned', 'not_banned' => 'Not banned'] as $id => $label)
                    <option @selected(($filters['ban'] ?? null) === $id) value="{{$id}}">{{$label}}</option>
                @endforeach
            </select>
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
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th class="w-35" scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Subscribers</th>
                <th scope="col">Videos</th>
                <th scope="col">Comments</th>
                <th scope="col">Registered</th>
                <th scope="col">Last login</th>
                <th scope="col">Ban</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="bg-light">
                <td class="align-middle">
                    <a href="{{$user->route}}" class="d-flex align-items-start gap-3 text-decoration-none">
                        <img class="rounded" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar" style="width: 50px;">
                        <div>
                            <span>{{$user->username}}</span>
                            <x-expand-item max="150">
                                {{$user->description}}
                            </x-expand-item>
                        </div>
                    </a>
                </td>
                <td class="align-middle">
                    {{$user->email}}
                </td>
                <td class="align-middle">
                    @if($user->subscribers_count)
                        <div class="badge bg-primary">{{$user->subscribers_count}} subscribers</div>
                    @else
                        <div class="badge bg-secondary">No subscribers</div>
                    @endif
                </td>
                <td class="align-middle">
                    @if($user->videos_count)
                        <a href="{{route('admin.videos.index') .'?user='.$user->id}}" class="badge bg-primary text-decoration-none">{{$user->videos_count}} videos</a>
                    @else
                        <div class="badge bg-secondary">No videos</div>
                    @endif
                </td>
                <td class="align-middle">
                    @if($user->comments_count)
                        <div class="badge bg-primary">{{$user->comments_count}} comments</div>
                    @else
                        <div class="badge bg-secondary">No comments</div>
                    @endif
                </td>
                <td class="align-middle">{{$user->created_at->diffForHumans()}}</td>
                <td class="align-middle">{{$user->last_login_at->diffForHumans()}}</td>
                <td class="align-middle">
                    @if($user->is_banned)
                        <div class="badge bg-warning">
                            {{$user->banned_at->diffForHumans()}}
                        </div>
                    @else
                        <button
                            class="btn btn-danger btn-sm"
                            title="Ban user"
                            data-bs-toggle="modal"
                            data-bs-target="#ban_user"
                            data-route="{{route('admin.users.ban', $user)}}"
                            data-username="{{$user->username}}"
                            data-avatar="{{$user->avatar_url}}"
                        >
                            <i class="fa-solid fa-ban"></i>
                            Ban user
                        </button>
                    @endif
                </td>
                <td class="align-middle">
                    <a target="_blank" class="btn btn-primary btn-sm" href="{{route('impersonate', $user)}}">
                        <i class="fa-solid fa-user-ninja"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
  </div>
 {{ $users->links() }}
 @include('admin.users.modals.ban')
@endsection
