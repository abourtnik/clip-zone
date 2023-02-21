@extends('layouts.user')

@section('title', 'Channel playlists')

@section('content')
    @if($playlists->total() || $filters)
        {{ Breadcrumbs::render('playlists') }}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Playlists</h2>
            <a href="{{route('user.playlists.create')}}" class="btn btn-success d-flex align-items-center gap-1">
                <i class="fa-solid fa-plus"></i>
                <span class="d-none d-sm-block">Create new playlist</span>
            </a>
        </div>
        <hr>
        <form class="my-4 d-flex flex-column flex-xl-row gap-3 align-items-center align-items-xl-end" method="GET">
            <div class="row w-100">
                <div class="col-12 col-sm-4 col-xl">
                    <label for="search" class="form-label fw-bold">Search</label>
                    <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
                </div>
                <div class="col-12 col-sm-4 col-xl">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" aria-label="Default select example">
                        <option selected value="">All</option>
                        @foreach($status as $id => $name)
                            <option @selected(($filters['status'] ?? null) === (string) $id) value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-xl">
                    <label for="playlist_date_start" class="form-label fw-bold">Playlist date start</label>
                    <input type="datetime-local" name="date[]" class="form-control" id="playlist_date_start" value="{{$filters['date'][0] ?? null}}">
                </div>
                <div class="col-12 col-sm-6 col-xl">
                    <label for="playlist_date_end" class="form-label fw-bold">Playlist date end</label>
                    <input type="datetime-local" name="date[]" class="form-control" id="playlist_date_end" value="{{$filters['date'][1] ?? null}}">
                </div>
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
                    <th scope="col" style="width: 40%">Playlist</th>
                    <th scope="col">Visibility</th>
                    <th scope="col">Created</th>
                    <th scope="col">Last updated</th>
                    <th scope="col">Videos count</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($playlists as $playlist)
                    <tr class="bg-light">
                        <td class="d-flex gap-3">
                            <a href="{{$playlist->route}}">
                                <img src="{{$playlist->videos()->first()->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                            </a>
                            <div>
                                <div>{{Str::limit($playlist->title, 100), '...'}}</div>
                                <small class="text-muted">{{Str::limit($playlist->description, 190), '...'}}</small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-{{$playlist->status->color()}}">
                                <i class="fa-solid fa-{{$playlist->status->icon()}}"></i>&nbsp;
                                {{$playlist->status->name()}}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$playlist->created_at->format('d F Y - H:i')}}">
                                {{$playlist->created_at->diffForHumans()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$playlist->updated_at->format('d F Y - H:i')}}">
                                {{$playlist->updated_at->diffForHumans()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="badge bg-secondary">
                                {{trans_choice('videos', $playlist->videos_count)}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <a href="{{route('user.playlists.edit', $playlist)}}" class="btn btn-primary btn-sm" title="Edit Playlist">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button
                                type="button"
                                title="Delete video"
                                class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#delete_playlist"
                                data-title="{{$playlist->title}}"
                                data-route="{{route('user.playlists.destroy', $playlist)}}"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">No matching playlists</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $playlists->links() }}
        @include('users.playlists.modals.delete')
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-plus fa-2x"></i>
                    <h5 class="my-3">Create your first Playlist</h5>
                    <div class="text-muted my-3">Some description</div>
                    <a href="{{route('user.playlists.create')}}" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                        Create Playlist
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
