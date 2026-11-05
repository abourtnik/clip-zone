@component('mail::message')

# Hello {{$notifiable->username}}.

You are receiving this email because one of your comment was banned for violating our <a target="_blank" class="text-danger fw-bold" href="/terms">Terms of Service</a>.

@component('mail::panel')
    <strong style="display: block">Video : {{$comment->video->title}}</strong>
    <strong style="margin-top: 10px;display: block">Comment : </strong>
    <small>{{$comment->content}}</small>
    <small style="margin-top: 10px;display: block">{{$comment->created_at->format('d F Y')}}</small>
@endcomponent

If you want contest this decision, please contact our support.

@component('mail::button', ['url' => route('contact.show')])
    Contact Support
@endcomponent

Regards, {{ config('app.name') }}

@endcomponent

