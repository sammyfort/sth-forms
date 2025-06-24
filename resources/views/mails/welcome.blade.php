@component('mail::message')

    <p>
        Hi {{$user->firstname}},
        Welcome to Signboard, the platform where businesses go digital! <br>
        Whether you're a customer looking for amazing local businesses or a business owner ready to show off <br>
        your signboard to the world, you've come to the right place.
    </p>
    <p>
        Here's what you can do on Signboard: <br>
        📍 Discover businesses by category or location <br>
        🗺️ Explore signboards on an interactive map <br>
        ⭐ Leave reviews and read others' feedback <br>
        🏪 As a business, manage your listings with ease <br>
        🚀 Ready to get started? @component('mail::button', ['url' => url('/login')]) Login Now @endcomponent
    </p>

    Cheers,
    The Signboard Team
    {{ config('app.name') }}
    {{ env('APP_URL') }}
@endcomponent
