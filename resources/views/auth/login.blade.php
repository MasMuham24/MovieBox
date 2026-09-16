@extends('layouts.auth')

@section('title', 'Login – MovieBox')

@section('auth')
    <h1 class="auth-title">Login</h1>

    @if (isset($errors) && $errors->any())
        <div class="auth-alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="auth-form">
        @csrf
        <label class="auth-label" for="email">Email</label>
        <input type="text" name="email" id="email" class="auth-input" placeholder="Email" value="{{ old('email') }}" required autofocus autocomplete="email">

        <label class="auth-label" for="password">Password</label>
        <input type="password" name="password" id="password" class="auth-input" placeholder="Password" required autocomplete="current-password">

        <button type="submit" class="btn btn-primary btn-block auth-submit">Login</button>
    </form>

    <p class="auth-footer">Belum punya akun? <a href="{{ route('register') }}" class="auth-link">Daftar</a></p>
@endsection