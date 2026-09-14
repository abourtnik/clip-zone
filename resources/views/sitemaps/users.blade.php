{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($users as $user)
        <url>
            <loc>{{ $user->route }}</loc>
            @if ($user->videos_max_published_at)
                <lastmod>{{ $user->videos_max_published_at->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
</urlset>
