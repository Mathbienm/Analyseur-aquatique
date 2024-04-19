@component('mail::message')
    # {{ $subject }}

    {!! nl2br(($content)) !!}

    Merci,
@endcomponent
