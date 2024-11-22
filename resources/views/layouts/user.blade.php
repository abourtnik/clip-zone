<x-layout sidebar="back" type="user">
    {{--@section('class', 'overflow-auto')--}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <strong>{{ $message }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</x-layout>
