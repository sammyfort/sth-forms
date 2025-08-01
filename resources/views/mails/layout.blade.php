<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #fb8509;
            --secondary: #004576;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 30px 15px;
            color: #333;
        }
        .email-container {
            max-width: 620px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .email-header {
            background-color: var(--secondary);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }
        .email-header img {
            max-height: 50px;
            margin-bottom: 10px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 1.4em;
            font-weight: 600;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h2 {
            color: var(--secondary);
            margin-top: 0;
        }
        .email-body ul {
            padding-left: 20px;
        }
        .button {
            display: inline-block;
            background-color: var(--primary);
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
        }
        .email-footer {
            text-align: center;
            font-size: 0.9em;
            color: #888;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .email-footer a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 500;
        }
        @media only screen and (max-width: 600px) {
            .email-body, .email-header, .email-footer {
                padding: 20px !important;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo">
        <h1>{{ config('app.name') }}</h1>
    </div>
    <div class="email-body">
        @yield('content')
    </div>
    <div class="email-footer">
        Cheers,<br>
        <strong>{{ config('app.name') }}</strong><br>
        <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
    </div>
</div>
</body>
</html>
