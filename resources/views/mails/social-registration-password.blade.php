@component('mail::message')

    <p>
        Hi {{ $user->name }},
        Thank you for signing up using your {{ $platform }} account.
    </p>
    <p>
        For your convenience, we’ve also created a password for you to log in directly without using your social account.
    </p>
    <p>
        🔑 Your temporary password: {{ $password }}
        We recommend you change this password after your first login.
    </p>
    <p>
        If you didn’t sign up or received this by mistake, please contact us right away.
        Welcome aboard!
    </p>

    Cheers,
    The Signboard Team
    {{ config('app.name') }}
    {{ env('APP_URL') }}
@endcomponent
