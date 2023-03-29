@extends('layouts.default')

@section('title', 'Terms')

@section('content')
    <div class="row align-items-center h-75 mt-5">
        <div class="col-12 col-sm-10 offset-sm-1 border border-1 bg-light py-4 px-5">
            <h1 class="mb-3">Terms of services</h1>
            <p>{{config('app.name')}} agrees to furnish services to the Subscriber subject to the following Terms of Service. Use of {{config('app.name')}}'s service constitutes acceptance and agreement to {{config('app.name')}}'s Terms of Service.</p>
            <p>{{config('app.name')}} reserves the right to modify the Terms of Service without notice.</p>
            <h2 class="mt-4 mb-3 h3">Use of specific personal data</h2>
            <p>When you leave a comment on {{config('app.name')}}, the data entered in the form, but also your IP address and the user agent of your browser are collected to help us detect unwanted comments.</p>
            <p>After validation of your comment, your profile photo will be publicly visible next to your comment.</p>
            <h2 class="mt-4 mb-3 h3">Avatars</h2>
            <p>If you are a registered user and upload images to the website, we advise you to avoid uploading images containing EXIF coordinate data. Visitors to your website can download and extract location data from these images.</p>
            <h2 class="mt-4 mb-3 h3">Cookies</h2>
            <p>If you have an account and you connect to the site, a temporary cookie will be created in order to persist your connection state. This cookie will be automatically destroyed when you log out of the site.</p>
            <h2 class="mt-4 mb-3 h3">Statistics and audience measurements</h2>
            <p>
                In order to analyze site traffic (number of visits, most viewed pages...) we do not use a third-party service but a self-hosted service based on
                <a href="https://umami.is/">Umami</a>.
                The data is anonymized and does not identify the individual behavior of a user but rather a general trend (number of visits to the site and number of visits per page only).
            </p>
            <h2 class="mt-4 mb-3 h3">Storage times for your data</h2>
            <p>If you leave a comment, the comment and its metadata are retained indefinitely. This automatically recognizes and approves subsequent comments instead of leaving them in the moderation queue.</p>
            <p>For users who register on the site, we also store the personal data indicated in their profile. All users can view, modify or delete their personal information at any time. Only the site manager can also see and modify this information.</p>
            <h2 class="mt-4 mb-3 h3">The rights you have over your data</h2>
            <p>If you have an account or if you have left comments on the site, you can request the deletion of personal data concerning you. This does not take into account data stored for administrative, legal or security reasons.</p>
            <p>If you have an account and wish to delete your information you can do so automatically from your account by clicking on the deleted button.</p>
            <h2 class="mt-4 mb-3 h3">Transmission of your personal data</h2>
            <p>Your data is not shared with a third party.</p>
            <h2 class="mt-4 mb-3 h3">Protection of your data</h2>
            <p>So that the personal data that we collect is neither lost nor disclosed by unauthorized third parties, we have implemented the necessary measures (firewall, database not accessible remotely, etc.) in proportion to the sensitivity stored data.</p>
            <p>In accordance with the procedure provided for by the general data protection regulations in the event of a leak or anomaly concerning the personal data in our possession, we will notify you of the nature of the data that has leaked and the nature of the risk that may be generated, if this may affect your rights and freedoms (sensitive data) within a maximum of 72 hours after the problem has been identified.</p>
        </div>
    </div>
@endsection
