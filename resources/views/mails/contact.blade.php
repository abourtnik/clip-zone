@component('mail::message')

# You have received new message from {{ $name }} !

@component('mail::panel')
    {!! nl2br($message) !!}
@endcomponent

@endcomponent
