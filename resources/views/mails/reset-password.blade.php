@extends('mails.layout')

@section('content')
    <h2>Password Reset Request</h2>

    <p>Hello {{ $user->firstname }},</p>

    <p>We received a request to reset the password associated with your account.</p>

    <p>
        If you initiated this request, click the button below to reset your password:
    </p>

    <div class="text-center my-6">
        <a href="{{ $url }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Reset Password
        </a>
    </div>

    <p>If you did not make this request, you can safely ignore this email — no changes have been made to your account.</p>

    <p>For security, this link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.</p>
@endsection
