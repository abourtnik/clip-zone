@extends('layouts.user')

@section('title', 'Channel settings')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <p class="fw-bold">Oups some fields are incorrect</p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success fade show" role="alert">
            <strong>{!! session('status') !!}</strong>
        </div>
    @endif
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Profile Information</h2>
            <p class="text-muted">Update you account profile informations and email address.</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-5">
                                <image-upload source="{{$user->avatar_url}}" name="avatar" style="width: 150px; height: 150px"></image-upload>
                                <div class="d-flex flex-column gap-2">
                                    <div class="text-muted">Member since {{$user->created_at->longAbsoluteDiffForHumans()}}</div>
                                    @if($user->is_premium)
                                        <div class="badge bg-warning">
                                            <i class="fa-solid fa-star"></i>
                                            <span>Premium account</span>
                                        </div>
                                    @else
                                        <div class="badge bg-secondary">Standard account</div>
                                    @endif
                                </div>
                            </div>
                            @if($subscription)
                                <div class="alert alert-info w-50">
                                    <h6 class="text-center fw-bold">My subscription</h6>
                                    <hr>
                                    @if($subscription->is_active)
                                        <p class="fw-bold">You are currently subscribed</p>
                                        <p>Your next bank debit will be on : <strong>{{ $subscription->next_payment->format('d F Y') }}</strong></p>
                                        <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                            Manage my subscription
                                        </a>
                                    @else
                                        <p class="fw-bold">You have canceled your subscription.</p>
                                        <p>It will be automatically suspended after the : <strong>{{ $subscription->next_payment->format('d F Y') }}</strong>.</p>
                                        <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                            Reactivate my subscription
                                        </a>
                                    @endif
                                </div>
                            @else
                                <a class="btn btn-warning text-white fw-bold d-flex align-items-center gap-2" href="{{route('pages.premium')}}">
                                    <i class="fa-solid fa-star"></i>
                                    <span>Become premium</span>
                                </a>
                                <div></div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="username" class="form-label">Username</label>
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
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="{{old('email', $user->email)}}">
                            </div>
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-control" name="country" id="country">
                                    <option selected value="">Select Country</option>
                                    @foreach($countries as $code => $name)
                                        <option @selected(old('country', $user->country) === $code) value="{{$code}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-user-edit"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Channel Informations</h2>
            <p class="text-muted">Update you channel informations</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <image-upload source="{{$user->banner_url}}" name="banner"></image-upload>
                        <div class="row mt-3">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="website"
                                    name="website"
                                    value="{{old('website', $user->website)}}"
                                    maxlength="{{config('validation.user.website.max')}}"
                                >
                            </div>
                        </div>
                        <div class="row" x-data="{ count: 0 }" x-init="count = $refs.message.value.length">
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Channel Description</label>
                                <textarea
                                    class="form-control"
                                    id="description"
                                    rows="6"
                                    name="description"
                                    maxlength="{{config('validation.user.description.max')}}"
                                    x-ref="message"
                                    @keyup="count = $refs.message.value.length"
                                >{{old('description', $user->description)}}</textarea>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.user.description.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" value="0" name="show_subscribers">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="show_subscribers"
                                        value="1"
                                        name="show_subscribers"
                                        @checked(old('show_subscribers', $user->show_subscribers))
                                    >
                                    <label class="form-check-label" for="show_subscribers">
                                        Show subscribers count
                                    </label>
                                </div>
                                <div class="form-text">By disabling this option, you don't appear on <a class="text-decoration-none" href="{{route('subscription.discover')}}">discover</a> page</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a class="btn btn-success d-flex align-items-center gap-2" href="{{$user->route}}">
                            <i class="fa-solid fa-eye d-none d-sm-block"></i>
                            <span>Show my channel</span>
                        </a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-edit d-none d-sm-block"></i>
                            <span>Update Channel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Update Password</h2>
            <p class="text-muted">Ensure your account using a long, random password to stay secure.</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update-password') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="current_password" class="form-label">Current password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="new_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa-solid fa-user-edit"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2 class="text-danger">Delete Account</h2>
            <p class="text-muted">Permanently delete your account</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <div class="card-body">
                    <div class="alert alert-danger fw-bold">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Before deleting your account, please download all data or information that you wish to retain.
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_account">
                        <i class="fa-solid fa-user-times"></i>
                        Delete account
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('users.profile.modals.delete_account', $user)
    @include('users.partials.crop')
@endsection
