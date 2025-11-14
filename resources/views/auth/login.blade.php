@extends('layouts.auth')

@section('content')
<div class="auth-container">
        <div class="logo">
        <img src="{{ asset('images/logo-bthc.png') }}" alt="BHC" class="logo-img">
        <h2 style="letter-spacing: 2px; margin-top: 10px;">BETTER HOPE COLLECTION</h2>
    </div>

    <div class="auth-card">
        <h1 style="font-weight: 600;">Login</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>

            <div class="auth-links">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">
                        Don't have an account? Register
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
