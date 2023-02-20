@extends('layouts.default')

@section('title', 'Terms')

@section('content')
    <div class="container">
        <h1 class="mb-4">Terms of services</h1>
        <hr>
        <h2 class="mb-4">Use of specific personal data</h2>
        <h3 class="mb-4">Comments and topics</h3>
        <p>
            When you leave a comment or a subject on {{config('app.name')}}, the data entered in the form, but also your IP address and the user agent of your browser are collected to help us detect unwanted comments.
        </p>
        <p>
            After validation of your comment, your profile photo will be publicly visible next to your comment.
        </p>
        <h3 class="mb-4">Avatars</h3>
        <p>
            If you are a registered user and upload images to the website, we advise you to avoid uploading images containing EXIF ​​GPS coordinate data.
            Visitors to your website can download and extract location data from these images.
        </p>
        <h3 class="mb-4">Cookies</h3>
        <p>
            If you have an account and you connect to the site, a temporary cookie will be created in order to persist your connection state.
            This cookie will be automatically destroyed when you log out of the site.
        </p>
        <h3 class="mb-4">Statistics and audience measurements</h3>
        <p>
            In order to analyze site traffic (number of visits, most viewed pages...) we do not use a third-party service but a self-hosted service based on
            <a href="https://umami.is/">Umami</a>.
            The data is anonymized and does not identify the individual behavior of a user but rather a general trend (number of visits to the site and number of visits per page only).
        </p>
    </div>
@endsection
