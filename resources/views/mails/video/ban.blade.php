@component('mail::message')

# Hello {{$notifiable->username}}.

You are receiving this email because one of your video was banned for violating our <a target="_blank" class="text-danger fw-bold" href="/terms">Terms of Service</a>.

@component('mail::panel')
    <img src="{{$video->thumbnail_url}}" alt="">
    <strong style="margin-top: 10px;display: block">{{$video->title}}</strong>
    <small style="margin-top: 10px;display: block">{{trans_choice('views', $video->views_count)}} • {{$video->created_at->format('d F Y')}}</small>
@endcomponent

If you want contest this decision, please contact our support.

@component('mail::button', ['url' => route('contact.show')])
    Contact Support
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent

