@extends('mails.layout')

@section('content')
    <h2>Password Changed Successfully</h2>

    <p>Hi {{ $user->firstname }},</p>

    <p>
        We wanted to let you know that your password was successfully changed.
        If you made this change, no further action is needed.
    </p>

    <p>
        If you did not make this change, please reset your password immediately or contact our support team.
    </p>

    <a href="{{ config('app.url') }}/login" class="button">Login</a>
@endsection
