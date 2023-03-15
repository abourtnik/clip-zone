<div class="modal fade" tabindex="-1" id="save" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" x-data="{playlists : [{{$user_playlists->filter(fn($p) => $p->has_video)->implode('id', ',')}}]}">
            <div class="modal-header">
                <h5 class="modal-title">Save Video to ...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach($user_playlists as $playlist)
                    <div class="form-check mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <input
                                    class="form-check-input"
                                    name="playlists[]"
                                    type="checkbox"
                                    @checked($playlist->has_video)
                                    value="{{$playlist->id}}"
                                    id="playlist-{{$playlist->id}}"
                                    @change="($event.target.checked) ? playlists.push(1) : playlists = playlists.filter(id => id != $event.target.value)"
                                >
                                <label class="form-check-label" for="playlist-{{$playlist->id}}">
                                    {{Str::limit($playlist->title, 60)}}
                                </label>
                            </div>
                            <i class="fa-solid fa-{{$playlist->status->icon()}}"></i>&nbsp;
                        </div>
                    </div>
                @endforeach
                <a target="_blank" class="btn btn-success btn-sm" href="{{route('user.playlists.create')}}">
                    <i class="fa-solid fa-plus"></i>&nbsp;
                    Create new playlist
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button
                    id="save_playlist"
                    type="button"
                    class="btn btn-success ajax-button"
                    data-url="{{route('video.save')}}"
                    data-method="POST"
                    :data-body="JSON.stringify({'playlists' : playlists, 'video_id': {{$video->id}}})"
                >
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
