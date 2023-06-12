@extends('layouts.ticketi')

@section('title', 'Reset Password')
@section('description', 'Reset Password page of Ticketi service')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <h3 class="fw-bold">Reset Password</h3>
        <form method="POST" class="my-4 row gy-3 gx-0 needs-validation" action="{{ route('password.update') }}" novalidate>
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="mb-1">
                <label for="emailInput" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" name="email" id="emailInput" maxlength="150" required value="{{ old('email', $request->email) }}" autofocus />
                <div class="invalid-feedback">Invalid email format</div>
            </div>
            <div class="mb-1">
                <label for="passwordInput" class="form-label">{{ __('Password') }}</label>
                <input type="password" class="form-control" name="password" id="passwordInput" aria-describedby="passwordHelp" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required />
                <div id="passwordHelp" class="form-text">
                    Min. 8 characters, 1 lower, 1 upper, 1 digit, 1 special
                    character.
                </div>
                <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div class="mb-1">
                <label for="confirmPasswordInput" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" class="form-control" name="password_confirmation" id="confirmPasswordInput" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required />
                <div class="invalid-feedback">Passwords are not equal!</div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md-full-width">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
        @include('shared.validation-errors')
    </div>
</div>
@endsection

@push('scripts')
<!-- Page specific JS-->
<script type="module" src="{{ asset('js/reset-password.js') }}"></script>
@endpush