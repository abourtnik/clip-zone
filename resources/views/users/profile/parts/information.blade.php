<div class="row align-items-start my-3">
    <div class="col-12 col-xl-4">
        <h2>{{ __('Profile Information') }}</h2>
        <p class="text-muted">{{ __('Update you account profile information\'s and email address') }}</p>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card shadow-soft my-3">
            @if (!$user->hasVerifiedEmail())
                <div class="alert alert-primary fw-bold d-flex flex-column flex-lg-row align-items-center justify-content-between gap-4 radius-end-0 radius-start-0">
                    <div class="d-flex flex-column gap-2 text-left">
                        <div>{{ __('Please verify your email address for access all features') }}</div>
                        <small class="text-muted text-sm">{{ __('You can verify your email until') }} {{ $user->created_at->addMinutes(config('auth.verification.expire'))->translatedFormat('d F Y H:i') }}. {{ __('After that time, your account will be automatically deleted') }}</small>
                    </div>
                    <form
                        method="POST"
                        action="{{route('verification.send')}}"
                        x-data="{timer: {{ session()->get("verification.send|$user->id:next")?->isFuture() ? (int) now()->diffInSeconds(session()->get("verification.send|$user->id:next")) : 0}}}"
                        x-init="const t = setInterval(() => timer === 0 ? clearInterval(t) : timer--, 1000)"
                    >
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1" :disabled="timer > 0">
                            <span>{{ __('Resend verification email') }}</span>
                            <div x-show="timer > 0">(<span x-text="timer"></span>)</div>
                        </button>
                    </form>
                </div>
            @endif
            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @method('PUT')
                    @csrf
                    <div @class(["d-flex justify-content-between align-items-center", "flex-column" => $user->premium_subscription, 'flex-column flex-lg-row' => !$user->premium_subscription])>
                        <div class="d-flex align-items-center justify-content-start flex-column flex-sm-row gap-2 gap-sm-5">
                            <div
                                class="position-relative overflow-hidden rounded-circle border border-secondary"
                                x-data="{hover:false}"
                                @mouseover="hover=true"
                                @mouseleave="hover=false"
                                @cropped-avatar.window="$refs.image.setAttribute('src', URL.createObjectURL($event.detail))"
                                style="width: 150px; height: 150px"
                            >
                                <img x-ref="image" class="img-fluid w-100" src="{{$user->avatar_url}}" alt="Avatar" >
                                <div
                                    class="position-absolute w-100 text-center text-white start-50 z-2"
                                    :class="hover ? 'opacity-100 top-50' : 'opacity-0 top-75'"
                                    style="transition: all 0.3s ease-in-out 0s;transform: translate(-50%, -50%);"
                                >
                                    <div class="fw-bold">{{ __('Click to update avatar') }}</div>
                                </div>
                                <image-upload
                                    name="avatar"
                                    config="avatar"
                                    class="position-absolute bottom-0 left-0 right-0 top-0 opacity-0 cursor-pointer w-100 z-3"
                                ></image-upload>
                                <div
                                    style="transition: all 0.4s ease-in-out 0s;"
                                    class="position-absolute bg-dark bg-opacity-75 bottom-0 left-0 right-0 top-0 w-100"
                                    :class="hover ? 'opacity-100' : 'opacity-0'"
                                >
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div class="text-muted">{{ __('Member since :date', ['date' => $user->created_at->longAbsoluteDiffForHumans()]) }}</div>
                                @if($user->is_premium)
                                    <div class="badge bg-warning">
                                        <i class="fa-solid fa-star"></i>
                                        <span>{{ __('Premium account') }}</span>
                                    </div>
                                @else
                                    <div class="badge bg-secondary">{{ __('Standard account')}}</div>
                                @endif
                            </div>
                        </div>
                        @if($user->premium_subscription)
                            <hr class="w-100">
                            <div class="alert alert-info w-100">
                                <div class="d-flex justify-content-between flex-column flex-sm-row gap-2">
                                    <div class="fw-bold">{{ __('My subscription') }} : {{$user->premium_subscription->plan->price}}  / {{ __($user->premium_subscription->plan->period) }}</div>
                                    <div>
                                        <span>{{ __('Status') }} :</span>
                                        <strong @class(['text-danger' => !$user->premium_subscription->is_active, 'text-success' => $user->premium_subscription->is_active ])>
                                            {{$user->premium_subscription->status}}
                                        </strong>
                                    </div>
                                </div>
                                <hr>
                                @if($user->premium_subscription->is_canceled)
                                    <p>{{ __('Your subscription was canceled the')}} <strong>{{ $user->premium_subscription->ends_at->translatedFormat('d F Y') }}</strong></p>
                                    <a class="btn btn-primary" href="{{route('pages.premium')}}">
                                        {{ __('Renew my subscription') }}
                                    </a>
                                @elseif($user->premium_subscription->is_trial_canceled)
                                    <p>{{ __('You have canceled your subscription')}}</p>
                                    <p>{{ __('It will be automatically suspended after the') }} : <strong>{{ $user->premium_subscription->ends_at->translatedFormat('d F Y') }}</strong>.</p>
                                    <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                        {{ __('Reactivate my subscription') }}
                                    </a>
                                @elseif($user->premium_subscription->on_trial)
                                    <p>{{ __('You are currently on trial period until')}} {{$user->premium_subscription->trial_ends_at->translatedFormat('d F Y')}}</p>
                                    <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                        {{ __('Cancel my subscription') }}
                                    </a>
                                @elseif($user->premium_subscription->is_unpaid)
                                    <p class="text-danger fw-bold">Your last payment the <strong>{{$user->premium_subscription->next_payment->subDay()->translatedFormat('d F Y')}}</strong> was unsuccessful.</p>
                                    <p class="text-danger fw-bold">{{ __('Please update your payment method to continue to profit premium features') }}</p>
                                    <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                        {{ __('Update payment information') }}
                                    </a>
                                @else
                                    <p>{{ __('Your next bank debit will be on')}} : <strong>{{ $user->premium_subscription->next_payment->translatedFormat('d F Y') }}</strong></p>
                                    <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                        {{ __('Manage my subscription') }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <hr class="w-100 d-block d-lg-none">
                            <a class="btn btn-warning text-white fw-bold d-flex align-items-center gap-2" href="{{route('pages.premium')}}">
                                <i class="fa-solid fa-star"></i>
                                <span>{{ __('Become premium') }}</span>
                            </a>
                            <div></div>
                        @endif
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                required
                                value="{{old('username', $user->username)}}"
                                minlength="{{config('validation.user.username.min')}}"
                                maxlength="{{config('validation.user.username.max')}}"
                            >
                        </div>
                        <div
                            class="col-12 col-sm-6 mb-3"
                            x-data="{ slug: '{{  $user->slug }}', valid :true }"
                        >
                            <label for="slug" class="form-label">{{ __('Handle') }}</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text">@</span>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="!valid && 'is-invalid'"
                                    id="slug"
                                    name="slug"
                                    required
                                    value="{{old('slug', $user->slug)}}"
                                    minlength="{{config('validation.user.slug.min')}}"
                                    maxlength="{{config('validation.user.slug.max')}}"
                                    x-ref="slug"
                                    @keyup="slug = $refs.slug.value; valid = /^[a-zA-Z0-9._-]+$/.test($refs.slug.value)"
                                    pattern="^[a-zA-Z0-9._\-]+$"
                                    title="Handle can only contain letters, numbers, periods (.), dashes (-), and underscores (_)"
                                >
                                <div class="invalid-feedback" x-show="!valid">
                                    {{ __('Handle can only contain letters, numbers, periods (.), dashes (-), and underscores (_)') }}
                                </div>
                            </div>
                            <div class="form-text" x-show="valid">
                                <span>{{ config('app.url') }}/@</span><span x-text="slug"></span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" required value="{{old('email', $user->email)}}" @disabled($user->getTemporaryEmailForVerification())>
                            @if($user->getTemporaryEmailForVerification())
                                <p class="form-text text-danger text-sm mb-1">
                                    {{ __('Please confirm your new email')}} : <strong>{{$user->getTemporaryEmailForVerification()}}</strong> by clicking the link in the confirmation email sent to you.
                                </p>
                                <div class="d-flex align-items-center gap-1 justify-content-start">
                                    <button
                                        type="submit"
                                        form="resend-notification-form"
                                        class="flex gap-1 btn btn-link btn-sm p-0 text-decoration-none"
                                        x-data="{timer: {{ session()->get("verification.send.update|$user->id:next")?->isFuture() ? (int) now()->diffInSeconds(session()->get("verification.send.update|$user->id:next")) : 0}}}"
                                        x-init="const t = setInterval(() => timer === 0 ? clearInterval(t) : timer--, 1000)"
                                        :disabled="timer > 0"
                                    >
                                        <span>{{ __('Resend verification email') }}</span>
                                        <span x-show="timer > 0">(<span x-text="timer"></span>)</span>
                                    </button>
                                    <span>·</span>
                                    <button
                                        type="submit"
                                        form="cancel-notification-form"
                                        class="btn btn-link btn-sm p-0 text-decoration-none"
                                    >
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="country" class="form-label">{{ __('Country') }}</label>
                            <select class="form-control" name="country" id="country">
                                <option selected value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $code => $name)
                                    <option @selected(old('country', $user->country) === $code) value="{{$code}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="w-100">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3" x-data="{selected: {{$user->jsonPhone()}}}">
                            <label for="phone" class="form-label">{{ __('Phone') }}</label>
                            <div class="input-group mb-3">
                                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span x-text="selected.flag"></span>
                                    <span x-text="selected.prefix"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach(config('phone.countries') as $country)
                                        <li x-show="selected.code !== '{{$country['code']}}'">
                                            <button type="button" class="dropdown-item d-flex align-items-center gap-2" @click="selected = {{{json_encode($country)}}}">
                                                <span>{{$country['flag']}}</span>
                                                <span>{{$country['prefix']}}</span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="prefix" :value="selected.prefix" required>
                                <input type="hidden" name="code" :value="selected.code" required>
                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    value="{{old('phone', $user->getPhoneWithoutPrefix())}}"
                                    @required($user->hasVerifiedPhone())
                                />
                                @if($user->hasVerifiedPhone())
                                    <span
                                        class="input-group-text"
                                        data-bs-toggle="tooltip"
                                        data-bs-trigger="hover"
                                        data-bs-title="Phone number verified"
                                    >
                                        <i class="fa-solid fa-check text-success"></i>
                                    </span>
                                @endif
                            </div>
                            @if($user->waitForPhoneCode())
                                <p class="form-text text-danger text-sm mb-1">
                                    {{ __('Please confirm your phone number by entering the 5-digit code we just sent you') }}
                                </p>
                                <div class="d-flex align-items-center gap-1 justify-content-start">
                                    <button
                                        type="submit"
                                        form="send-notification-form-mobile"
                                        class="flex gap-1 btn btn-link btn-sm p-0 text-decoration-none"
                                        x-data="{timer: {{ session()->get("phone.send|$user->id:next")?->isFuture() ? (int) now()->diffInSeconds(session()->get("phone.send|$user->id:next")) : 0}}}"
                                        x-init="const t = setInterval(() => timer === 0 ? clearInterval(t) : timer--, 1000)"
                                        :disabled="timer > 0"
                                    >
                                        <span>{{ __('Resend verification code') }}</span>
                                        <span x-show="timer > 0">(<span x-text="timer"></span>)</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @if($user->waitForPhoneCode())
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="code" class="form-label">{{ __('Code') }}</label>
                                <div class="input-group">
                                    <input form="verify-phone-code" type="text" class="form-control" id="code" name="code" minlength="5" maxlength="5">
                                    <button class="btn btn-primary" type="submit" form="verify-phone-code">{{ __('Verify') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-user-edit"></i>
                        {{ __('Update Profile') }}
                    </button>
                </div>
            </form>
            <form id="resend-notification-form" class="d-none" method="POST" action="{{route('verification.send.update')}}">
                @csrf
            </form>
            <form id="cancel-notification-form" class="d-none" method="POST" action="{{route('verification.verify.update.cancel')}}">
                @csrf
            </form>
            <form id="send-notification-form-mobile" class="d-none" method="POST" action="{{route('phone.send')}}">
                @csrf
            </form>
            <form id="verify-phone-code" class="d-none" method="POST" action="{{route('phone.verify')}}">
                @csrf
            </form>
        </div>
    </div>
</div>
