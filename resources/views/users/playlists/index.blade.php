@use('App\Filters\Forms\User\PlaylistFiltersForm')

@extends('layouts.user')

@section('title', __('My Playlists'))

@section('content')
    @if($playlists->total() || request()->all())
    <div x-data={playlist:{}}>
        {{ Breadcrumbs::render('playlists') }}
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between align-items-start align-items-md-center my-3">
            <h2>{{ __('My Playlists') }}</h2>
            <a href="{{route('user.playlists.create')}}" class="btn btn-success d-flex align-items-center gap-1">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('Create new playlist') }}</span>
            </a>
        </div>
        <hr>
        {!! form(FormBuilder::create(PlaylistFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col" class="w-40" style="min-width: 370px;">{{ __('Playlist') }}</th>
                    <th scope="col">{{ __('Visibility') }}</th>
                    <th scope="col" style="min-width: 160px;">{{ __('Created') }}</th>
                    <th scope="col" style="min-width: 160px;">{{ __('Updated') }}</th>
                    <th scope="col" style="min-width: 160px;">{{ __('Videos count') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($playlists as $playlist)
                    <tr class="bg-light">
                        <td class="d-flex gap-3">
                            <a href="{{$playlist->route}}">
                                @if($playlist->first_video)
                                    <img src="{{$playlist->first_video->thumbnail_url}}" alt="{{$playlist->title}} thumbnail" style="width: 120px;height: 68px">
                                @else
                                    <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 120px;height: 68px">
                                        <i class="fa-solid fa-image fa-2x"></i>
                                    </div>
                                @endif
                            </a>
                            <div class="d-flex flex-column align-items-start gap-2">
                                <a class="text-black text-decoration-none" href="{{$playlist->route}}">{{Str::limit($playlist->title, 100), '...'}}</a>
                                <small class="text-muted">{{Str::limit($playlist->description, 190), '...'}}</small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-{{$playlist->status->color()}}">
                                <i class="fa-solid fa-{{$playlist->status->icon()}}"></i>&nbsp;
                                {{ $playlist->status->name() }}
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
                            <div class="d-flex gap-1">
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
                                    data-thumbnail="{{$playlist->first_video?->thumbnail_url}}"
                                    data-infos="{{trans_choice('videos', $playlist->videos_count) . ' • ' . $playlist->created_at->diffForHumans()}}"
                                    data-route="{{route('user.playlists.destroy', $playlist)}}"
                                    @click="playlist = $el.dataset;"
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">{{ __('No matching playlists') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $playlists->links() }}
        @include('users.playlists.modals.delete')
    </div>
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-plus fa-2x"></i>
                    <h5 class="my-3">{{ __('Create your first Playlist') }}</h5>
                    <div class="text-muted my-3">{{ __('Create your first playlist to start organizing your videos.') }}</div>
                    <a href="{{route('user.playlists.create')}}" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                        {{ __('Create new playlist') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
