@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Articles</h2>
        <div>
            <a class="btn btn-outline-success" href="{{route('admin.articles.export')}}">
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
                <th scope="col">Title</th>
                <th scope="col">Status</th>
                <th scope="col">Created</th>
                <th scope="col">Updated</th>
            </tr>
        </thead>
        <tbody>
        @foreach($articles as $article)
            <tr class="bg-light">
                <th scope="row">{{$article->id}}</th>
                <td>{{$article->title}}</td>
                <td>
                    @if($article->status)
                        <span class="badge text-bg-success">En ligne</span>
                    @else
                        <span class="badge text-bg-danger">Hors ligne</span>
                    @endif
                </td>
                <td>{{$article->created_at->format('d F Y - H:i:s')}}</td>
                <td>{{$article->updated_at->format('d F Y - H:i:s')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $articles->links() }}
@endsection
