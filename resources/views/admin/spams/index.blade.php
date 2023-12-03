@extends('layouts.admin')

@section('title', 'Spams')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Spam</h2>
    </div>
    <hr>
    <form method="POST" action="{{route('admin.spams.update')}}" class="col-6">
        @csrf()
        @method('PUT')
        <div class="mb-3">
            <label for="words" class="form-label">Stop words</label>
            <textarea class="form-control" id="words" rows="20" name="words">{{$words}}</textarea>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection
