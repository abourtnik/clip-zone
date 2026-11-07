@if (config('services.turnstile.enabled'))

    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce

    <div {{ $attributes->merge(['class' => 'turnstile-wrapper']) }}>
        <div
            class="cf-turnstile"
            data-sitekey="{{ config('services.turnstile.site_key') }}"
            data-theme="auto"
            data-size="flexible"
            data-language="auto"
            data-appearance="interaction-only"
        >
        </div>
    </div>
@endif
