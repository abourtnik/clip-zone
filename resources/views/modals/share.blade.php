<div class="modal fade" tabindex="-1" id="share" aria-hidden="true" x-data="{embed:false}">
    <div class="modal-dialog" :class="embed && 'modal-xl'">
        <div class="modal-content" >
            <div x-show="!embed">
                <div class="modal-header">
                    <h5 class="modal-title">Share Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" x-data="startAt('{{$video->route}}')">
                    <img class="img-fluid rounded img-thumbnail mb-3" src="{{$video->thumbnail_url}}" alt="{{$video->title}} Thumbnail">
                    <div class="d-flex flex-wrap" style="row-gap: 1rem !important;">
                        <a type="button" class="bg-transparent fa-stack fa-2x" style="font-size: 1.6em" @click="embed = true">
                            <i class="fa fa-circle fa-stack-2x" style="color: #888888;"></i>
                            <i class="fa-solid fa-code fa-stack-1x fa-inverse"></i>
                        </a>
                        <a href="mailto:?body={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #888888;"></i>
                            <i class="fa-regular fa-envelope fa-stack-1x fa-inverse"></i>
                        </a>
                        <a target="_blank" href="https://www.facebook.com/share.php?u={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #3A5997;"></i>
                            <i class="fa-brands fa-facebook fa-stack-1x fa-inverse"></i>
                        </a>
                        <a target="_blank" href="https://api.whatsapp.com/send/?text={{$video->route}}&type=custom_url&app_absent=0" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #22D365;"></i>
                            <i class="fa-brands fa-whatsapp fa-stack-1x fa-inverse"></i>
                        </a>
                        <a target="_blank" href="https://twitter.com/share?text={{$video->title}}&url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #1EA1F1;"></i>
                            <i class="fa-brands fa-twitter fa-stack-1x fa-inverse"></i>
                        </a>
                        <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #0277B5;"></i>
                            <i class="fa-brands fa-linkedin fa-stack-1x fa-inverse"></i>
                        </a>
                        <a target="_blank" href="" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #4680C2;"></i>
                            <i class="fa-brands fa-vk fa-stack-1x fa-inverse"></i>
                        </a>
                        <a target="_blank" href="https://www.pinterest.fr/pin/create/button/?description=test&url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                            <i class="fa fa-circle fa-stack-2x" style="color: #BC091C;"></i>
                            <i class="fa-brands fa-pinterest fa-stack-1x fa-inverse"></i>
                        </a>
                    </div>
                    <hr>
                    <label for="link" class="form-label">Video Link</label>
                    <div class="mb-3 input-group">
                        <input type="text" class="form-control" readonly id="link" x-model="link">
                        <button
                            class="btn btn-primary"
                            type="button"
                            @click="navigator.clipboard.writeText(link)"
                            title="Copy video link"
                            data-bs-toggle="tooltip"
                            data-bs-trigger="click"
                            data-bs-title="Link copied !"
                        >
                            <span>Copy</span>
                        </button>
                    </div>
                    <div class="col-10 col-sm-6">
                        <div class="input-group mb-3 ">
                            <div class="input-group-text d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="checkbox" aria-label="Checkbox for following text input" x-model="enabled">
                                <label for="link" class="form-label mb-0">Start at :</label>
                            </div>
                            <input type="time" id="time" class="form-control disabled" aria-label="Text input with checkbox" value="00:01" :disabled="!enabled" x-model="time">
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="embed">
                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="bg-transparent" @click="embed = false">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <h5 class="modal-title">Embed</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-xl-8">
                            <iframe src="{{route('video.embed', $video)}}" class="w-100" width="960" height="540" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                        <div class="col-12 col-xl-4 d-flex flex-column gap-2">
                            <textarea readonly rows="8" class="form-control" style="resize: none">{{ '<iframe width="560" height="315" src="'.route('video.embed', $video).'" title="'.config('app.name').' video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>' }}</textarea>
                            <button
                                class="btn btn-primary btn-block d-flex align-items-center gap-2 justify-content-center"
                                type="button"
                                @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                title="Copy video link"
                                data-bs-toggle="tooltip"
                                data-bs-trigger="click"
                                data-bs-title="Code copied !"
                                data-link="{{ '<iframe width="560" height="315" src="'.route('video.embed', $video).'" title="'.config('app.name').' video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>' }}"
                            >
                                <i class="fa-regular fa-copy"></i>
                                <span>Copy code</span>
                            </button>
                            <div class="mt-auto">
                                <p class="text-muted text-sm mb-0">By embedding {{config('app.name')}} videos on your site, you are agreeing to {{config('app.name')}}
                                    <a class="text-decoration-none" target="_blank" href="{{route('pages.terms')}}">Terms of Service</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('startAt', (initial) => ({
            link : initial,
            enabled: false,
            time: '00:01',
            init() {
                this.$watch('time', value => {
                    if(value) {
                        this.link = initial + '?t=' + this.getSeconds(value)
                    } else {
                        this.link = initial
                    }
                });
                this.$watch('enabled', value => {
                   if(this.time) {
                       this.link = initial + (value ? '?t=' + this.getSeconds(this.time) : '')
                   }
                })
            },
            getSeconds(time) {
                const a = time.split(':')
                return (+a[0]) * 60 + (+a[1]);
            }
        }));
    })
</script>
