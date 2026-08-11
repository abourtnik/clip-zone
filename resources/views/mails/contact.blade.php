@component('mail::message')

# You have received new message from {{ $name }} !

@component('mail::panel')
    {!! nl2br(e($message)) !!}
@endcomponent

Sender IP : {{$ip}}
@endcomponent
