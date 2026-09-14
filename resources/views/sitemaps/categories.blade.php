{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($categories as $category)
        <url>
            <loc>{{ $category->route }}</loc>
            @if ($category->videos_max_published_at)
                <lastmod>{{ $category->videos_max_published_at->toAtomString() }}</lastmod>
            @endif
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach
</urlset>
