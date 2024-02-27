@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Users</h2>
        <a class="btn btn-outline-success" href="{{route('admin.users.export')}}">
            <i class="fa-solid fa-file-export"></i>
            Export
        </a>
    </div>
    <hr>
    <div x-data="{ filters: window.innerWidth > 992 }">
        <button class="btn btn-primary btn-sm d-flex d-lg-none align-items-center gap-2 mb-3" @click="filters = !filters">
            <i class="fa-solid fa-filter"></i>
            <span>{{ __('Filters') }}</span>
            <i class="fa-solid fa-chevron-down" x-show.important="!filters" ></i>
            <i class="fa-solid fa-chevron-up" x-show.important="filters" ></i>
        </button>
        <form class="mb-4 row align-items-end gx-2 gy-2" method="GET" x-show.important="filters">
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl col-xxl-3">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl">
                <label for="date_start" class="form-label fw-bold">Registration date start</label>
                <input type="datetime-local" name="date_start" class="form-control" id="date_start" value="{{$filters['date_start'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl">
                <label for="date_end" class="form-label fw-bold">Registration date end</label>
                <input type="datetime-local" name="date_end" class="form-control" id="date_end" value="{{$filters['date_end'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl">
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach(['banned' => 'Banned', 'not_confirmed' => 'Not confirmed', 'premium' => 'Premium'] as $id => $label)
                        <option @selected(($filters['status'] ?? null) === $id) value="{{$id}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="btn-group col-auto">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
        <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th class="w-35" scope="col" style="min-width: 300px">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Subscribers</th>
                <th scope="col">Videos</th>
                <th scope="col">Comments</th>
                <th scope="col">Interactions</th>
                <th scope="col" style="min-width: 180px;">Registered</th>
                <th scope="col" style="min-width: 180px;">Last login</th>
                <th scope="col" style="min-width: 110px;">Ban</th>
                <th scope="col" style="min-width: 84px;">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr class="bg-light">
                <td class="align-middle">
                    <div class="d-flex align-items-start gap-3 text-decoration-none">
                        <a href="{{$user->route}}" class="text-decoration-none">
                            <img class="rounded" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar" style="width: 50px;">
                        </a>
                        <div>
                            <div class="d-flex gap-2 align-items-center">
                                <a href="{{$user->route}}" class="text-decoration-none">{{$user->username}}</a>
                                @if($user->is_premium)
                                    <i class="fa-solid fa-star text-warning"></i>
                                @endif
                            </div>
                            <x-expand-item max="100">
                                {{$user->description}}
                            </x-expand-item>
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <small>{{$user->email}}</small>
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
                        <a href="{{route('admin.videos.index') .'?user='.$user->id}}" class="badge bg-primary text-decoration-none">{{trans_choice('videos', $user->videos_count)}}</a>
                    @else
                        <div class="badge bg-secondary">No videos</div>
                    @endif
                </td>
                <td class="align-middle">
                    @if($user->comments_count)
                        <a href="{{route('admin.comments.index') .'?user='.$user->id}}" class="badge bg-primary  text-decoration-none">{{trans_choice('comments', $user->comments_count)}}</a>
                    @else
                        <div class="badge bg-secondary">No comments</div>
                    @endif
                </td>
                <td class="align-middle">
                    @if($user->interactions_count)
                        <div class="badge bg-primary">{{trans_choice('interactions', $user->interactions_count)}}</div>
                    @else
                        <div class="badge bg-secondary">No interactions</div>
                    @endif
                </td>
                <td class="align-middle">
                    <small>{{$user->created_at->diffForHumans()}}</small>
                </td>
                <td class="align-middle">
                    <small>{{$user->last_login_at?->diffForHumans()}}</small>
                </td>
                <td class="align-middle">
                    @if($user->hasVerifiedEmail())
                        @if($user->is_banned)
                            <div class="badge bg-warning">
                                {{$user->banned_at->diffForHumans()}}
                            </div>
                        @elseif(!$user->is_premium)
                            <button
                                class="btn btn-danger btn-sm"
                                title="Ban user"
                                data-bs-toggle="modal"
                                data-bs-target="#ban_user"
                                data-route="{{route('admin.users.ban', $user)}}"
                                data-username="{{$user->username}}"
                                data-avatar="{{$user->avatar_url}}"
                                data-videos="{{$user->videos_count}}"
                                data-comments="{{$user->comments_count}}"
                            >
                                <i class="fa-solid fa-ban"></i>
                                Ban user
                            </button>
                        @endif
                    @endif
                </td>
                <td class="align-middle">
                    <div class="d-flex gap-1">
                        @if($user->hasVerifiedEmail())
                            <a target="_blank" class="btn btn-primary btn-sm" href="{{route('impersonate', $user)}}">
                                <i class="fa-solid fa-user-ninja"></i>
                                Ninja
                            </a>
                        @else
                            <form method="POST" action="{{route('admin.users.confirm', $user)}}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-user-check"></i>
                                    Confirm email
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">
                    <i class="fa-solid fa-database fa-2x my-3"></i>
                    <p class="fw-bold">No matching users</p>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
  </div>
 {{ $users->links() }}
 @include('admin.users.modals.ban')
@endsection
