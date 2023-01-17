@extends('layouts.default')

@section('title', $search)

@section('content')
    <div x-data="{ filters: false }">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-primary btn-sm" @click="filters = !filters">
                <i class="fa-solid fa-filter"></i>
                Filters
            </button>
            @if($results)
                <div class="fw-bold">{{$results->total()}} Results</div>
            @else
                <div class="fw-bold">0 Results</div>
            @endif
        </div>
        <form class="my-4 d-flex gap-3 align-items-end" method="GET" x-show.important="filters">
            <div class="col">
                <label for="status" class="form-label fw-bold">Type</label>
                <select name="status" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    <option value="">User</option>
                    <option value="">Videos</option>
                </select>
            </div>
            <div class="col">
                <label for="category" class="form-label fw-bold">Upload Date</label>
                <select name="category" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    <option value="">Last hour</option>
                    <option value="">Today</option>
                    <option value="">This week</option>
                    <option value="">This month</option>
                    <option value="">This year</option>
                </select>
            </div>
            <div class="col">
                <label for="category" class="form-label fw-bold">Duration</label>
                <select name="category" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    <option value="">Under 4 minutes</option>
                    <option value="">4-20 minutes</option>
                    <option value="">Over 20 minutes</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
        <hr>
    </div>
    <div class="container">
        @if($results->count())
            @foreach($results as $result)
                @include('search.'.$result->type, [$result->type => $result])
            @endforeach
            {{ $results->links() }}
        @else
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="w-50 border p-4 bg-light text-center">
                    <i class="fa-solid fa-database fa-7x mb-3"></i>
                    <h2>No results found</h2>
                    <div class="text-muted">Try different keywords or remove search filters</div>
                </div>
            </div>
        @endif
    </div>
@endsection


