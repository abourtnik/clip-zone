{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="https://www.google.com/schemas/sitemap-video/1.1">
    @foreach ($videos as $video)
        <url>
            <loc>{{ $video->route }}</loc>
            <video:video>
                <video:thumbnail_loc>{{ $video->thumbnail_url }}</video:thumbnail_loc>
                <video:title>{{ $video->title }}</video:title>
                <video:description><![CDATA[{{ $video->description }}]]></video:description>
                <video:content_loc>{{ $video->file_url }}</video:content_loc>
                <video:duration>{{ $video->getRawOriginal('duration') }}</video:duration>
                <video:publication_date>{{ $video->published_at->toAtomString() }}</video:publication_date>
            </video:video>
        </url>
    @endforeach
</urlset>
