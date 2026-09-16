<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'MovieBox')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="auth-body">
    <div class="auth-shell">
        <a href="{{ route('login') }}" class="mb-logo auth-logo" aria-label="MovieBox">
            <span class="mb-logo-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                    <path d="M6 4l14 8-14 8z"/>
                </svg>
            </span>
            <span class="mb-logo-word">MovieBox</span>
        </a>

        <div class="auth-card">
            @yield('auth')
        </div>
    </div>
</body>
</html>