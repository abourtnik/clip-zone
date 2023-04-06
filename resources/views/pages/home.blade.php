@extends('layouts.default')

@section('title', 'Share and Watch Amazing Videos')

@section('content')
    <videos-area url="{{route('videos.home')}}" />
@endsection


