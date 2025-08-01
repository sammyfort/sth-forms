@extends('mails.layout')

@section('content')
    <p>
        Hi {{ $user->firstname }},
    </p>

    <p>
        🎉 Great news! Your promotion for <strong>{{ $promotedName }}</strong> is now live.
    </p>

    <p>
        Your service/signboard/product will now be featured according to the selected plan: <strong>{{ $plan->name }}</strong>, giving you increased visibility and reaching more potential customers.
    </p>

    <p>
        Thank you for using {{ config('app.name') }} to promote your business. We’re excited to help you grow!
    </p>

    <p>
        If you have any questions or need support, feel free to reach out to us anytime.
    </p>
@endsection
