@extends('layouts.ticketi')

@section('title', 'Forgot Password')
@section('description', 'Forgot Password page of Ticketi service')

@section('content')
<div class="row">
    <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Login</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Forgot Password
            </li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-4">
        <h3 class="fw-bolder">Forgot password</h3>
        <p class="lead fw-normal text-muted mb-0">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
        @include('shared.auth-status')
        <form method="POST" class="my-4 row gy-3 gx-0 needs-validation" action="{{ route('password.email') }}" novalidate>
            @csrf
            <div class="mb-1">
                <label for="emailInput" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" name="email" id="emailInput" maxlength="150" required value="{{ old('email') }}" autofocus />
                <div class="invalid-feedback">Invalid email format</div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md-full-width">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>
        @include('shared.validation-errors')
    </div>
</div>
@endsection