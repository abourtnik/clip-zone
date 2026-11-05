@use('App\Filters\Forms\Admin\VideoFiltersForm')

@extends('layouts.admin')

@section('title', __('Videos'))

@section('content')
    <div x-data="{video: {}}">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>{{ __('Videos') }}</h2>
        </div>
        <hr>
        {!! form(FormBuilder::create(VideoFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th style="min-width: 300px" scope="col">{{ __('User') }}</th>
                    <th scope="col" style="min-width: 400px">{{ __('Video') }}</th>
                    <th scope="col">{{ __('Status') }}</th>
                    <th scope="col" style="min-width: 210px">{{ __('Date') }}</th>
                    <th scope="col">{{ __('Views') }}</th>
                    <th scope="col">{{ __('Comments') }}</th>
                    <th scope="col" style="min-width: 140px">{{ __('Interactions') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($videos as $video)
                    <tr class="bg-light">
                        <td class="align-middle">
                            <a href="{{$video->user->route}}" class="d-flex align-items-start gap-3 text-decoration-none">
                                <img class="rounded" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 50px;">
                                <div class="d-flex flex-column">
                                    <span>{{$video->user->username}}</span>
                                    <div class="d-flex gap-1 text-muted text-sm">
                                        <span>{{trans_choice('subscribers', $video->user->subscribers_count)}}</span>
                                        <span>•</span>
                                        <span>{{trans_choice('videos', $video->user->videos_count)}}</span>
                                    </div>
                                    @if($video->user->trashed())
                                        <span class="text-danger text-sm fw-bold mt-1">
                                            {{ __('Deleted') }} {{$video->user->deleted_at->diffForHumans()}}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-2 align-items-center my-3">
                                @if(!$video->is_draft)
                                    @if($video->category)
                                        <div class="badge tw:bg-black">
                                            {{$video->category->title}}
                                        </div>
                                    @else
                                        <div class="badge bg-secondary">
                                            {{ __('No category') }}
                                        </div>
                                    @endif
                                @endif
                                <div class="badge tw:bg-black">{{$video->duration}} - @size($video->size)</div>
                            </div>
                            <div class="d-flex gap-3">
                                <a class="d-block text-decoration-none" href="{{$video->route}}">
                                    @if($video->is_draft || $video->is_failed)
                                        <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 120px;height: 68px">
                                            <i class="fa-solid fa-image fa-2x"></i>
                                        </div>
                                    @else
                                        <img src="{{$video->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                                    @endif
                                </a>
                                <div class="text-sm">{{Str::limit($video->title, 100), '...'}}</div>
                            </div>
                        </td>
                        <td class="align-middle">
                            @include('videos.status')
                        </td>
                        <td class="align-middle">
                            @if($video->is_private || $video->is_unlisted || $video->is_draft || $video->is_failed)
                                {{$video->created_at->translatedFormat('d F Y H:i')}}
                                <div class="text-sm text-muted">{{ __('Uploaded') }}</div>
                            @elseif($video->is_planned)
                                {{$video->scheduled_at->translatedFormat('d F Y H:i')}}
                                <div class="text-sm text-muted">{{ __('Scheduled') }}</div>
                            @else
                                {{$video->published_at->translatedFormat('d F Y H:i')}}
                                <div class="text-sm text-muted">{{ __('Published') }}</div>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(!$video->is_draft)
                                @if($video->views)
                                    <span class="badge tw:bg-black">
                                        {{trans_choice('views', $video->views)}}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ __('No views') }}</span>
                                @endif
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(!$video->is_draft)
                                @if($video->comments_count)
                                    <a href="{{route('admin.comments.index') .'?video='.$video->id}}" class="text-decoration-none">
                                        <span class="badge tw:bg-black">{{trans_choice('comments', $video->comments_count)}}</span>
                                    </a>
                                @else
                                    <div class="badge bg-secondary">
                                        {{ __('No comments') }}
                                    </div>
                                @endif
                                @if(!$video->allow_comments)
                                    <div class="badge bg-danger">
                                        {{ __('Disabled') }}
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(!$video->is_draft)
                                @include('users.partials.interactions', ['item' => $video])
                                <div class="d-flex align-items-center gap-1">
                                    @if($video->interactions_count)
                                        <div class="badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#video_likes" style="cursor: pointer;" data-id="{{$video->id}}">
                                            {{ __('Details') }}
                                        </div>
                                    @endif
                                    @if(!$video->show_likes)
                                        <div class="badge bg-danger mt-2">
                                            {{ __('Disabled') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1">
                                <a href="{{route('user.videos.show', $video)}}" class="btn btn-success btn-sm" title="{{ __('Video statistics') }}">
                                    <i class="fa-solid fa-chart-simple"></i>
                                </a>
                                @can('ban', $video)
                                    <button
                                        type="button"
                                        title="{{ __('Ban video') }}"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ban_video"
                                        @click="video = $el.dataset"
                                        data-title="{{$video->title}}"
                                        data-infos="{{trans_choice('views', $video->views)}} • {{$video->created_at->translatedFormat('d F Y')}}"
                                        data-poster="{{$video->thumbnail_url}}"
                                        data-route="{{route('admin.videos.ban', $video)}}"
                                    >
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                @endcan
                                @can('download', $video)
                                    <a download="{{$video->slug}}" href="{{route('video.download', $video)}}" class="btn btn-dark btn-sm" title="{{ __('Download video') }}">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">{{ __('No matching videos') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $videos->links() }}
        @include('videos.modals.interactions')
        @include('admin.videos.modals.ban')
    </div>
@endsection
