@if(config('app.ads_enabled'))
    <div class="mb-3">
        <div class="tw:bg-gray-300 tw:border tw:border-gray-500 tw:min-h-25 tw:max-h-75 tw:overflow-hidden tw:flex tw:items-center tw:justify-center">
            @if(app()->isProduction())
                <ins class="adsbygoogle"
                     style="display:block; text-align:center;"
                     data-ad-layout="in-article"
                     data-ad-format="fluid"
                     data-ad-client="ca-pub-3386885268137177"
                     data-ad-slot="9219560601"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @else
                <span>Ads here</span>
            @endif
        </div>
    </div>
@endif
