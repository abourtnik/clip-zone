@use('App\Filters\Forms\User\SubscriberFiltersForm')

@extends('layouts.user')

@section('title', __('My Subscribers'))

@section('content')
    @if($subscriptions->total() || request()->all())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>{{ __('My Subscribers') }}</h2>
        </div>
        <hr>
        {!! form(FormBuilder::create(SubscriberFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col" style="min-width: 200px;">{{ __('Channel') }}</th>
                    <th scope="col" style="min-width: 174px;">{{ __('Subscribed at') }}</th>
                    <th scope="col" style="min-width: 159px;">{{ __('Subscribers count') }}</th>
                    <th scope="col" style="min-width: 151px;">{{ __('Registration date') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($subscriptions as $subscription)
                    <tr class="bg-light">
                        <td>
                            <a href="{{$subscription->subscriber->route}}" class="d-flex align-items-center gap-2 text-decoration-none">
                                <img class="rounded" src="{{$subscription->subscriber->avatar_url}}" alt="{{$subscription->subscriber->username}} avatar" style="width: 50px;">
                                <span>{{$subscription->subscriber->username}}</span>
                            </a>
                        </td>
                        <td class="align-middle">
                            <div class="text-sm" data-bs-toggle="tooltip" data-bs-title="{{$subscription->subscribe_at->format('d F Y - H:i')}}">
                                {{$subscription->subscribe_at->diffForHumans()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <span class="text-sm">
                                 {{trans_choice('subscribers', $subscription->subscriber->subscribers_count)}}
                            </span>
                        </td>
                        <td class="align-middle">
                            <div class="text-sm" data-bs-toggle="tooltip" data-bs-title="{{$subscription->subscriber->created_at->format('d F Y - H:i')}}">
                                {{$subscription->subscriber->created_at->diffForHumans()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <subscribe-button
                                @if(!$subscription->subscriber->is_current_user_subscribe) is-subscribe @endif
                                user="{{$subscription->subscriber->id}}"
                                size="sm"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">{{ __('No matching subscribers') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $subscriptions->links() }}
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-user-slash fa-2x"></i>
                    <h5 class="my-3">{{ __('No subscriber yet') }}</h5>
                    <div class="text-muted my-3">{{ __('Upload more video for more subscribers') }}</div>
                    <button class="btn btn-success " data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-plus"></i>
                        {{ __('Upload new video') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
