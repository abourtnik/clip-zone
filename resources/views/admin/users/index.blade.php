@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Users</h2>
        <div>
            <a class="btn btn-outline-success" href="{{route('admin.users.export')}}">
                <i class="fa-solid fa-file-export"></i>
                Export
            </a>
            <a class="btn btn-primary" href="">
                <i class="fa-solid fa-plus"></i>
                New
            </a>
        </div>
    </div>

    <hr>
    <table class="table table-bordered">
        <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">Admin</th>
                <th scope="col">Created</th>
                <th scope="col">Updated</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="bg-light">
                <th scope="row">{{$user->id}}</th>
                <td>{{$user->username}}</td>
                <td>{{$user->email}}</td>
                <td>
                    @if($user->isAdministrator())
                        <span class="badge text-bg-success">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    @else
                        <span class="badge text-bg-danger">
                            <i class="fa-solid fa-xmark"></i>
                        </span>
                    @endif
                </td>
                <td>{{$user->created_at->format('d F Y - H:i:s')}}</td>
                <td>{{$user->updated_at->format('d F Y - H:i:s')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
