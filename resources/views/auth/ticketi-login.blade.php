@extends('layouts.ticketi')

@section('title', 'Login')
@section('description', 'Login page of Ticketi service')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <h3 class="fw-bold">@yield('title')</h3>
        @include('shared.auth-status')
        <form method="POST" action="{{ route('login') }}" class="my-4 row gy-3 gx-0 needs-validation" novalidate>
            @csrf
            <div class="mb-1">
                <label for="emailInput" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" name="email" id="emailInput" maxlength="150" value="{{ old('email') }}" required autofocus />
                <div class="invalid-feedback">Invalid email format</div>
            </div>
            <div class="mb-1">
                <label for="passwordInput" class="form-label">{{ __('Password') }}</label>
                <input type="password" class="form-control" name="password" id="passwordInput" aria-describedby="passwordHelp" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required autocomplete="current-password" />
                <div id="passwordHelp" class="form-text">
                    Min. 8 characters, 1 lower, 1 upper, 1 digit, 1 special
                    character.
                </div>
                <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div class="mb-1 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMeCheckbox" />
                <label class="form-check-label" for="rememberMeCheckbox">{{ __('Remember me') }}</label>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md-full-width">
                    {{ __('Log in') }}
                </button>
                <button type="reset" class="btn btn-secondary btn-md-full-width">
                    Reset
                </button>
            </div>
        </form>
        <ul class="list-group list-group-horizontal">
            <li class="list-group-item">
                <a href="{{ route('register') }}">Don't have account?</a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('password.request') }}">I forgot password!</a>
            </li>
        </ul>
        @if (session()->has('info'))
        <div class="alert alert-warning text-center" role="alert">
            {{ session('info') }}
        </div>
        @endif
        @include('shared.validation-errors')
    </div>
    <div id="login-illustration" class="col-lg-8 d-md-none d-lg-block bg-image"></div>
</div>
@endsection