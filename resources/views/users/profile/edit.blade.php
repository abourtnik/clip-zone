@extends('layouts.user')

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
    <div class="row align-items-start my-3">
        <div class="col-4">
            <h2>Profile Informations</h2>
            <p class="text-muted">Update you account profile informations and email address.</p>
        </div>
        <div class="col-8">
            <div class="card shadow-soft">
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required value="{{old('username', $user->username)}}">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="{{old('email', $user->email)}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="avatar" class="form-label">Avatar</label>
                                <input class="form-control" type="file" id="avatar" name="avatar">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-control" name="country" id="country">
                                    <option selected value="">Select Country</option>
                                    @foreach($countries as $code => $name)
                                        <option @if(old('country', $user->country) === $code) selected @endif value="{{$code}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa-solid fa-user-edit"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-4">
            <h2>Channel Informations</h2>
            <p class="text-muted">Update you channel informations</p>
        </div>
        <div class="col-8">
            <div class="card shadow-soft">
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="banner" class="form-label">Banner</label>
                                <input class="form-control" type="file" id="banner" name="banner">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input class="form-control" type="text" id="website" name="website" value="{{old('website', $user->website)}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Channel Description</label>
                                <textarea class="form-control" id="description" rows="6" name="description" maxlength="5000">{{old('description', $user->description)}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="show_subscribers"
                                        value="1"
                                        name="show_subscribers"
                                        @if(old('show_subscribers', $user->show_subscribers)) checked @endif
                                    >
                                    <label class="form-check-label" for="show_subscribers">
                                        Show subscribers count
                                    </label>
                                </div>
                                <div class="form-text">If you disabled this, you don't appear on discover page </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa-solid fa-user-edit"></i>
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-4">
            <h2>Update Password</h2>
            <p class="text-muted">Ensure your account using a long, random password to stay secure.</p>
        </div>
        <div class="col-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update-password') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="current_password" class="form-label">Current password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label for="new_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            <div class="col-6 mb-3">
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
        <div class="col-4">
            <h2 class="text-danger">Delete Account</h2>
            <p class="text-muted">Permanently delete your account</p>
        </div>
        <div class="col-8">
            <div class="card shadow-soft my-3">
                <div class="card-body">
                    <div class="alert alert-danger fw-bold">
                        Once your account is deleted, all of its resources and data wille be permanently deleted.
                        Before deleting your account, please download and data or information that you wish to retain.
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
@endsection
