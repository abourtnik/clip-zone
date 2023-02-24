<div class="modal fade" tabindex="-1" id="save" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" x-data="{playlists : 0}">
            <div class="modal-header">
                <h5 class="modal-title">Save Video to ...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body w-50">
                @foreach(\Illuminate\Support\Facades\Auth::user()->playlists as $playlist)
                    <div class="form-check mb-3 w-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <input
                                    class="form-check-input"
                                    name="playlists[]"
                                    type="checkbox"
                                    value="{{$playlist->id}}"
                                    id="playlist-{{$playlist->id}}"
                                    @change="playlists += ($event.target.checked) ? 1 : -1"
                                >
                                <label class="form-check-label" for="playlist-{{$playlist->id}}">
                                    {{$playlist->title}}
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
                <button x-show="playlists > 0" type="button" class="btn btn-success">
                    Save to <span x-text="playlists"></span> playlist(s)
                </button>
            </div>
        </div>
    </div>
</div>
