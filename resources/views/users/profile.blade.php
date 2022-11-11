@extends('layouts.user')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mt-5">
            Merci de corriger les erreurs suivantes:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex align-items-center my-3">
        <h2>My informations</h2>
    </div>
    <div class="card shadow-soft">
        <div class="card-body">
            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required value="{{$user->username}}">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="{{$user->email}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="avatar" class="form-label">Avatar</label>
                        <input class="form-control" type="file" id="avatar" name="avatar">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="background" class="form-label">Background</label>
                        <input class="form-control" type="file" id="background" name="background">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-user-edit"></i>
                    Update
                </button>
            </form>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Password</h2>
    </div>
    <div class="card shadow-soft my-3">
        <div class="card-body">
            <form action="{{ route('user.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning">
                    <i class="fa-solid fa-user-edit"></i>
                    Update Password
                </button>
            </form>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2 class="text-danger">Remove account</h2>
    </div>
    <div class="card shadow-soft my-3">
        <div class="card-body">
            <div class="alert alert-danger">
                Vous n'êtes pas satisfait du contenu du site ?
                Ou vous souhaitez supprimer toutes les informations associées à ce compte ?
            </div>
            <form action="{{ route('user.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fa-solid fa-user-times"></i>
                    Remove your account
                </button>
            </form>
        </div>
    </div>
@endsection
