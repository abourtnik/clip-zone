<x-layout sidebar="front">
    <div class="h-full">
        <div class="row align-items-center h-75">
            <div class="col-12 col-sm-10 offset-sm-1 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex flex-column px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/errors')}}/@yield('code').jpg" alt="@yield('code') image">
                    </div>
                    <div class="col-12 col-xl-6 p-5 text-center my-auto">
                        <h1>@yield('message')</h1>
                        <div class="text-muted my-4">
                            @yield('text')
                        </div>
                        <a class="btn btn-primary rounded-5 text-uppercase" href="{{route('pages.home')}}">Go home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
