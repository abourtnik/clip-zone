@if($video->thumbnail)
    <img src="{{$video->thumbnail_url}}" alt="{{$video->title}} Thumbnail" style="width: 120px;height: 68px;object-fit: cover">
@else
    <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 120px;height: 68px">
        <i class="fa-solid fa-image fa-2x"></i>
    </div>
@endif
