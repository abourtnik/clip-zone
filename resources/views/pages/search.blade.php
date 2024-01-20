@extends('layouts.default')

@section('title', $search ?? 'Search')

@section('content')
    <div x-data="{ filters: {{!empty($filters) ? 'true' : 'false'}} }">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary btn-sm" @click="filters = !filters">
                <i class="fa-solid fa-filter"></i>
                {{ __('Filters') }}
            </button>
            <div class="fw-bold"> {{trans_choice('results', $results->total())}}</div>
        </div>
        <form class="my-4 row align-items-end gx-2 gy-2" method="GET" x-show.important="filters">
            <input type="hidden" name="q" value="{{$search}}">
            <div class="col-12 col-sm-6 col-lg">
                <label for="date" class="form-label fw-bold">{{ __('Upload Date') }}</label>
                <select name="date" class="form-select" aria-label="Default select example">
                    <option selected value="">{{ __('All') }}</option>
                    @foreach(['hour' => __('Last hour') ,'today' => __('Today'), 'week' => __('This week'), 'month' => __('This month'), 'year' => __('This year')] as $value => $label)
                        <option @selected(($filters['date'] ?? null) === $value) value="{{$value}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <label for="duration" class="form-label fw-bold">{{ __('Duration') }}</label>
                <select name="duration" class="form-select" aria-label="Default select example">
                    <option selected value="">{{ __('All') }}</option>
                    @foreach(['4' => __('Under 4 minutes') , '4-20' => __('4-20 minutes'), '20' => __('Over 20 minutes')] as $value => $label)
                        <option @selected(($filters['duration'] ?? null) == $value) value="{{$value}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="btn-group col-auto">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?q={{$search}}&clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
        <hr>
    </div>
    <div class="row">
        <div class="col-12 col-xl-10 offset-xl-1">
            @if($results->total())
                @foreach($results as $result)
                    @include('search.'.$result->type, [$result->type => $result])
                @endforeach
                {{ $results->links() }}
            @else
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="w-100 border p-4 bg-light text-center">
                        <h2>{{ __('No results found') }} {{ $search ? 'for "'. $search . '"' : '' }}</h2>
                        <div class="text-muted">{{ __('Try different keywords or remove search filters') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


