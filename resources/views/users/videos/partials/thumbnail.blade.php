@if($video->is_public || $video->user->is(Auth::user()))
    @if($video->thumbnail)
        <img src="{{$video->thumbnail_url}}" alt="{{$video->title}} Thumbnail" style="width: 120px;height: 68px;object-fit: cover">
    @else
        <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 120px;height: 68px">
            <i class="fa-solid fa-image fa-2x"></i>
        </div>
    @endif
@elseif($video->is_banned)
    <div class="bg-secondary text-white d-flex flex-column gap-2 justify-content-center align-items-center text-center" style="width: 120px;height: 68px">
        <small class="text-sm">{{ __('This video was banned') }}</small>
    </div>
@else
    <div class="bg-secondary text-white d-flex flex-column gap-2 justify-content-center align-items-center text-center" style="width: 120px;height: 68px">
        <small class="text-sm">{{ __('This video is private') }}</small>
    </div>
@endif
