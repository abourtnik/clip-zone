@extends('layouts.default')

@section('title', 'Home')

@section('content')
    <videos-area url="{{route('videos.home')}}" />
@endsection


