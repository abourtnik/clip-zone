@use('App\Filters\Forms\SearchFiltersForm')

@extends('layouts.default')

@section('title', $search ?? 'Search')

@section('content')
    {!! form(FormBuilder::create(SearchFiltersForm::class)) !!}
    <hr>
    @if($search)
        <div class="fw-bold text-end mb-4"> {{trans_choice('results', $results->total())}}</div>
    @endif
    <div class="row">
        <div class="col-12 col-xl-10 offset-xl-1">
            @if($search && $results->total())
                @foreach($results as $result)
                    @include('search.'.$result->type, [$result->type => $result])
                @endforeach
                {{ $results->links() }}
            @else
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="w-100 border p-4 bg-light text-center">
                        <h2 class="text-break">{{ __('No results found') }} {{ $search ? __('for'). ' "' . $search . '"' : '' }}</h2>
                        <div class="text-muted">{{ __('Try different keywords or remove search filters') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


