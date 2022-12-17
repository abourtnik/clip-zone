@extends('layouts.default')

@section('content')
    <videos-area />
@endsection

@push('scripts')
    <script>
        /*const video_container = document.getElementById('video-container');
        const template = document.getElementById('article')
        const observer = new IntersectionObserver( async (entries) => {
            for(const entry of entries) {
                if(entry.isIntersecting) {

                    const response =  await fetch('/api/videos', {

                    });

                    const data = await response.json();

                    console.log(data);

                   // video_container.append(article)
                }
                console.log(
                    entry.target,
                    entry.isIntersecting
                )
            }
        })

        observer.observe(document.getElementById('loader'))*/
    </script>
@endpush


