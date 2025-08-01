@extends('mails.layout')

@section('content')
    <h2>Welcome to Signboard 🎉</h2>

    <p>
        Hi {{ $user->firstname }},
    </p>

    <p>
        Welcome to <strong>Signboard</strong>, the platform where businesses go digital!<br>
        Whether you're a customer looking for amazing local businesses or a business owner ready to show off
        your signboard to the world, you've come to the right place.
    </p>

    <p>Here's what you can do on Signboard:</p>

    <ul>
        <li>📍 Discover businesses by category or location</li>
        <li>🗺️ Explore signboards on an interactive map</li>
        <li>⭐ Leave reviews and read others' feedback</li>
        <li>🏪 As a business, manage your listings with ease</li>
    </ul>

    <p>
        🚀 Ready to get started?
    </p>

    <a href="{{ url('/login') }}" class="button">Login Now</a>
@endsection
