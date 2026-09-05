@if(config('app.ads_enabled'))
    <div class="mb-3 tw:w-full">
        <div class="tw:w-full tw:bg-gray-300 tw:border tw:border-gray-500 tw:min-h-25 tw:max-h-75 tw:overflow-hidden">
            @if(app()->isProduction())
                <ins class="adsbygoogle"
                     style="display:block; width:100%; height: 100%"
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
