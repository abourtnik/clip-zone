@props(['action'])

@if (config('services.turnstile.enabled'))
    @pushOnce('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=onTurnstileLoad" async defer></script>
    @endPushOnce

    <div id="turnstile-container" class="my-3"></div>

    @pushOnce('scripts')
        <script>
            function onTurnstileLoad() {
                turnstile.render("#turnstile-container", {
                    sitekey: "{{ config('services.turnstile.site_key') }}",
                    size: 'flexible',
                    appearance: 'interaction-only',
                    theme: localStorage.getItem('theme') || 'light',
                    language: '{{ app()->getLocale() }}',
                    action: "{{ $action }}",
                });
            }
        </script>
    @endPushOnce
@endif
