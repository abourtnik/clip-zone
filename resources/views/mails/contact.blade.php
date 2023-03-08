@component('mail::message')

# You have received new message from {{ $name }} !

@component('mail::panel')
    {{ $message }}
@endcomponent

@endcomponent
