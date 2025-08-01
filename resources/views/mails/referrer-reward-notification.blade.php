@extends('mails.layout')

@section('content')
    <h2>🎉 Great News, {{ $user->name }}!</h2>

    <p>
        {{ $referredUser->fullname }} just joined <strong>{{ config('app.name') }}</strong> using your referral link, and you’ve been rewarded with <strong>{{ $pointsPerReferral }} points</strong>! 🙌
    </p>

    <p>You can use your points to:</p>
    <ul>
        <li>🪧 Promote your <strong>signboards</strong></li>
        <li>🛠️ Advertise your <strong>services</strong></li>
        <li>🛍️ Boost your <strong>products</strong></li>
    </ul>

    <p>Keep sharing your link — there’s no limit to how many points you can earn! 🚀</p>

    <a href="{{ route('profile.show') }}" class="button">Use Your Points</a>
@endsection
