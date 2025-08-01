@extends('mails.layout')

@section('content')
    <h2 style="color: #004576;">You've received a new rating!</h2>

    <p>Hello {{ $user->firstname }},</p>

    <p>
        <strong>{{ $rater->fullname }}</strong> just rated your {{ $itemType }} with an average score of
        <span style="color: #fb8509; font-weight: bold;">{{ $averageRating }}/5</span>.
    </p>

    <p><strong>They commented:</strong> "{{ $review->review }}"</p>

    <p>
        Keep up the good work and continue delivering value to your audience!
    </p>

    <p>If you believe this rating was submitted in error or have questions, feel free to reach out to support.</p>
@endsection
