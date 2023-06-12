@extends('layouts.ticketi')

@section('title', 'Confirm Password')
@section('description', 'Confirm Password page of Ticketi service')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <h3 class="fw-bolder">Confirm Password</h3>
        <p class="lead fw-normal text-muted mb-0">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
        <form method="POST" class="my-4 row gy-3 gx-0 needs-validation" action="{{ route('password.confirm') }}" novalidate>
            @csrf
            <div class="mb-1">
                <label for="passwordInput" class="form-label">{{ __('Password') }}</label>
                <input type="password" class="form-control" name="password" id="passwordInput" aria-describedby="passwordHelp" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required autofocus autocomplete="current-password" />
                <div id="passwordHelp" class="form-text">
                    Min. 8 characters, 1 lower, 1 upper, 1 digit, 1 special
                    character.
                </div>
                <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md-full-width">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
        @include('shared.validation-errors')
    </div>
</div>
@endsection