@extends('layouts.auth')

@section('title', 'Register – MovieBox')

@section('auth')
    <h1 class="auth-title">Register</h1>

    @if (isset($errors) && $errors->any())
        <div class="auth-alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="auth-form">
        @csrf
        <label class="auth-label" for="name">Nama</label>
        <input type="text" name="name" id="name" class="auth-input" placeholder="Nama" value="{{ old('name') }}" required autofocus autocomplete="name">

        <label class="auth-label" for="email">Email</label>
        <input type="text" name="email" id="email" class="auth-input" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">

        <label class="auth-label" for="password">Password</label>
        <input type="password" name="password" id="password" class="auth-input" placeholder="Minimal 8 karakter" required autocomplete="new-password">

        <label class="auth-label" for="password_confirmation">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="auth-input" placeholder="Konfirmasi Password" required autocomplete="new-password">

        <button type="submit" class="btn btn-primary btn-block auth-submit">Register</button>
    </form>

    <p class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Login</a></p>
@endsection