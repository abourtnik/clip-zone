@extends('layouts.user')

@section('title', __('Channel Subtitles'))

@section('content')
    @if($videos->total())
        {{ Breadcrumbs::render('subtitles') }}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>{{ __('Subtitles') }}</h2>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col" style="min-width: 400px">{{ __('Video') }}</th>
                    <th scope="col">{{ __('Languages') }}</th>
                    <th scope="col" style="min-width: 140px">{{ __('Modified on') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($videos as $video)
                    <tr class="bg-light">
                        <td class="">
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{$video->route}}">
                                    @include('users.videos.partials.thumbnail', ['video' => $video])
                                </a>
                                <small>{{Str::limit($video->title, 100), '...'}}</small>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            @forelse($video->subtitles as $subtitle)
                                <div class="badge bg-primary">
                                    {{$subtitle->language_name}}
                                </div>
                            @empty
                                <div class="badge bg-secondary">
                                    {{ __('No Languages') }}
                                </div>
                            @endforelse
                        </td>
                        <td class="align-middle">
                            <div class="text-muted text-center" data-bs-toggle="tooltip" data-bs-title="{{$video->subtitles->first()?->updated_at->format('d F Y - H:i') ?? 'empty'}}">
                                {{$video->subtitles->first()?->updated_at->diffForHumans()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1">
                                <a href="{{route('user.videos.subtitles.index', $video)}}" class="btn btn-primary btn-sm" title="Edit Video Subtitles">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $videos->links() }}
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-upload fa-2x"></i>
                    <h5 class="my-3">{{ __('Import your first video') }}</h5>
                    <div class="text-muted my-3">
                        {{ __('Ready to share your talents with the world ?') }}
                    </div>
                    <button class="btn btn-success btn" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-plus"></i>
                        {{ __('Import') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
