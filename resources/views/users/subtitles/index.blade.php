@extends('layouts.user')

@section('title', 'Channel Subtitles')

@section('content')
    {{ Breadcrumbs::render('videos_subtitles', $video) }}
    @if($subtitles->total())
        <div x-data="{subtitle : {}}">
            <div class="d-flex justify-content-between align-items-center my-3">
                <h2 class="mb-0">Video Subtitles</h2>
                <a href="{{ route('user.videos.subtitles.create', $video) }}" class="btn btn-success d-flex align-items-center gap-1">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create new subtitle</span>
                </a>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr style="border-top: 3px solid #0D6EFD;">
                        <th scope="col" style="min-width: 100px">Name</th>
                        <th scope="col" style="min-width: 100px">Language</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="min-width: 140px">Created</th>
                        <th scope="col" style="min-width: 140px">Updated</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subtitles as $subtitle)
                        <tr class="bg-light">
                            <td class="align-middle">
                                {{$subtitle->name}}
                            </td>
                            <td class="align-middle">
                                {{$subtitle->language_name}}
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-{{$subtitle->status->color()}}">
                                    <i class="fa-solid fa-{{$subtitle->status->icon()}}"></i>&nbsp;
                                    {{$subtitle->status->name()}}
                                </span>
                            </td>
                            <td class="align-middle">
                                <div class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$subtitle->created_at->format('d F Y - H:i')}}">
                                    {{$subtitle->created_at->diffForHumans()}}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$subtitle->updated_at->format('d F Y - H:i')}}">
                                    {{$subtitle->updated_at->diffForHumans()}}
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex gap-1">
                                    <a href="{{ route('user.subtitles.edit', $subtitle) }}" class="btn btn-primary btn-sm" title="Edit Subtitle">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button
                                        type="button"
                                        title="Delete subtitle"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_subtitle"
                                        data-name="{{$subtitle->name}}"
                                        data-infos="{{$subtitle->language_name . ' • created ' . $subtitle->created_at->diffForHumans()}}"
                                        data-route="{{route('user.subtitles.destroy', $subtitle)}}"
                                        @click="subtitle = $el.dataset;"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $subtitles->links() }}
            @include('users.subtitles.modals.delete')
        </div>
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-closed-captioning fa-2x"></i>
                    <h5 class="my-3">This video doesn't have subtitles</h5>
                    <div class="text-muted my-3">
                        Reach a broader audience by adding subtitles to your video
                    </div>
                    <a href="{{ route('user.videos.subtitles.create', $video) }}" class="btn btn-success btn">
                        <i class="fa-solid fa-plus"></i>
                        Add subtitles
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
