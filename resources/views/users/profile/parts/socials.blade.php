<div class="row align-items-start my-3">
    <div class="col-12 col-xl-4">
        <h2>{{ __('Social Connection') }}</h2>
        <p class="text-muted">{{ __('Link your account to a social network to use as a sign-in method') }}</p>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card shadow-soft my-3">
            <div class="card-body d-flex flex-column flex-sm-row gap-2">
                @if($user->facebook_id)
                    <form method="POST" action="{{ route('oauth.unlink', ['service' => 'facebook']) }}" class="tw:flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn w-100 text-white text-center position-relative tw:bg-[#47639e]!">
                            <i class="fa-brands fa-facebook-f position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                            <span>{{ __('Unlink your Facebook account') }}</span>
                        </button>
                    </form>
                @else
                    <a class="btn w-100 text-white text-center position-relative tw:bg-[#47639e]! tw:flex-1" href="{{route('oauth.connect', ['service' => 'facebook'])}}">
                        <i class="fa-brands fa-facebook-f position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                        <span>{{ __('Link your Facebook account') }}</span>
                    </a>
                @endif
                @if($user->google_id)
                    <form method="POST" action="{{ route('oauth.unlink', ['service' => 'google']) }}" class="tw:flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn w-100 text-white text-center position-relative tw:bg-[#dd4b39]!">
                            <i class="fa-brands fa-google position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                            <span>{{ __('Unlink your Google account') }}</span>
                        </button>
                    </form>
                @else
                    <a class="btn w-100 text-white text-center position-relative tw:bg-[#dd4b39]! tw:flex-1" href="{{route('oauth.connect', ['service' => 'google'])}}">
                        <i class="fa-brands fa-google position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                        <span>{{ __('Link your Google account') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
