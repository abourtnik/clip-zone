@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    {{ Breadcrumbs::render('categories') }}
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2 class="mb-0">Categories</h2>
        <button  class="btn btn-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#categories_organise">
            <i class="fa-solid fa-sort"></i>
            <span>Sort</span>
        </button>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th class="w-25">Category</th>
                <th class="w-50">Slug</th>
                <th>Icon</th>
                <th>In menu</th>
                <th>Position</th>
            </tr>
            </thead>
            <tbody>
            @forelse($categories as $category)
                <tr class="bg-light">
                    <td class="align-middle">
                        <small>{{$category->title}}</small>
                    </td>
                    <td class="align-middle">
                        <small>{{$category->slug}}</small>
                    </td>
                    <td class="align-middle">
                        <i class="fa-solid fa-{{$category->icon}}"></i>
                    </td>
                    <td class="align-middle">
                        {{$category->in_menu}}
                    </td>
                    <td class="align-middle">
                        <small>{{$category->position}}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <i class="fa-solid fa-database fa-2x my-3"></i>
                        <p class="fw-bold">No matching categories</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @include('admin.categories.modals.organise', ['categories' => $active_categories])
@endsection
