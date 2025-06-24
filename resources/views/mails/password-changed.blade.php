@component('mail::message')

    <p>
        Hi {{ $user->firstname }},
        We wanted to let you know that your password was successfully changed.
        If you made this change, no further action is needed.
    </p>
    <p>
        If you did not make this change, please reset your password immediately or contact our support team.
    </p>

    Cheers,
    The Signboard Team
    {{ config('app.name') }}
    {{ env('APP_URL') }}
@endcomponent
