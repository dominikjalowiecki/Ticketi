@extends('layouts.ticketi-admin')

@section('content')
<h1 class="mt-4">Change password</h1>
<div class="row">
    <div class="col-md-4">
        <form
            method="POST"
            action="{{ route('change-password') }}"
            class="row my-4 gy-3 gx-0 needs-validation"
            novalidate
        >
            @csrf
            <div class="mb-1">
            <label for="currentPasswordInput" class="form-label"
                >{{ __('Current Password') }}</label
            >
            <input
                type="password"
                class="form-control"
                name="password"
                id="currentPasswordInput"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                required
                autocomplete="current-password"
                autofocus
            />
            <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div class="mb-1">
            <label for="newPasswordInput" class="form-label"
                >{{ __('New Password') }}</label
            >
            <input
                type="password"
                class="form-control"
                name="newPassword"
                id="newPasswordInput"
                aria-describedby="passwordHelp"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                required
            />
            <div id="passwordHelp" class="form-text">
                Min. 8 characters, 1 lower, 1 upper, 1 digit, 1 special
                character.
            </div>
            <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div>
            <button
                type="submit"
                class="btn btn-primary btn-md-full-width"
            >
                Submit
            </button>
            </div>
        </form>
        @include('shared.validation-errors')
    </div>
</div>
@endsection