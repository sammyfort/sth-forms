@extends('mails.layout')

@section('content')
    <h2>Welcome to {{ config('app.name') }} 🎉</h2>

    <p>
        Hi {{ $user->name }},
    </p>

    <p>
        Thank you for signing up using your <strong>{{ $platform }}</strong> account.
    </p>

    <p>
        For your convenience, we’ve also created a password for you to log in directly without using your social account.
    </p>

    <p>
        🔑 <strong>Your temporary password:</strong> <code>{{ $password }}</code><br>
        We recommend you change this password after your first login.
    </p>

    <p>
        If you didn’t sign up or received this by mistake, please contact us right away.
    </p>

    <p>
        Welcome aboard!
    </p>
@endsection
