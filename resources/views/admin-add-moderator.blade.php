@extends('layouts.ticketi-admin')

@section('content')
<h1 class="mt-4">Add moderator</h1>
<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ route('admin.addModerator') }}" class="my-4 row gy-3 gx-0 needs-validation" novalidate>
            @csrf
            <div class="mb-1">
                <label for="emailInput" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" name="email" id="emailInput" maxlength="150" value="{{ old('email') }}" required autofocus />
                <div class="invalid-feedback">Invalid email format</div>
            </div>
            <div class="mb-1">
                <label for="nameInput" class="form-label">{{ __('Name') }}</label>
                <input type="text" class="form-control" name="name" id="nameInput" maxlength="45" value="{{ old('name') }}" required />
                <div class="invalid-feedback">This field is required</div>
            </div>
            <div class="mb-1">
                <label for="surnameInput" class="form-label">{{ __('Surname') }}</label>
                <input type="text" class="form-control" name="surname" id="surnameInput" maxlength="65" value="{{ old('surname') }}" required />
                <div class="invalid-feedback">This field is required</div>
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
                <label for="passwordConfirmationInput" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" class="form-control" name="password_confirmation" id="passwordConfirmationInput" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required />
                <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md-full-width">
                    Register
                </button>
            </div>
        </form>
        @include('shared.validation-errors')
    </div>
</div>
@endsection