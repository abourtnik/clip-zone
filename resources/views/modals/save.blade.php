<div class="modal fade" tabindex="-1" id="save" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" x-data="save">
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
                                    @change="($event.target.checked) ? playlists.push(event.target.value) : playlists = playlists.filter(id => id != $event.target.value)"
                                >
                                <label class="form-check-label" for="playlist-{{$playlist->id}}">
                                    {{Str::limit($playlist->title, 90)}}
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
                <div x-show="error" x-text="error" class="alert alert-danger mt-4"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button :disabled="loading" type="button" @click="perform()" class="d-flex btn align-items-center gap-1" :class="saved ? 'btn-success' : 'btn-primary'">
                    <span x-show="loading" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                    <span x-show="!saved">Save</span>
                    <i x-show="saved" class="fa-solid fa-check"></i>
                    <span x-show="saved">Saved</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('save', () => ({
            loading: false,
            playlists: [{{$user_playlists->filter(fn($p) => $p->has_video)->implode('id', ',')}}],
            saved: false,
            error: null,
            async perform () {
                this.loading = true;
                this.error = null;
                const response = await fetch('{{route('save')}}', {
                    method: 'POST',
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        'playlists' : this.playlists,
                        'video_id': {{$video->id}}
                    })
                });
                this.loading = false;
                if (response.status === 201) {
                    this.saved = true;
                    setTimeout(() => {
                        this.saved = false;
                    }, 3000)
                } else {
                    const data = await response.json();
                    this.error = data?.message ?? 'Whoops! An error occurred. Please try again later.';
                }
            }
        }));
    })
</script>
