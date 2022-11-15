@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', $exception->getMessage() ?? __('Not Found'))
