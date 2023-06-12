@extends('layouts.user-profile')

@section('user-profile-content')
<div class="row">
    <div class="col-lg-5">
        <div class="card text-dark bg-light mb-3">
        <div class="card-header">User details</div>
        <div class="card-body">
            <h5 class="card-title">{{ $user->name . ' ' . $user->surname}}</h5>
            <p class="card-text">
                Email: {{ $user->email }}<br />
                Birthdate: {{ substr($user->birthdate, 0, 10) }}
            </p>
        </div>
        <div class="card-footer">
            <small class="text-muted"
            >Created
            <span class="time-component"
                >{{ $user->created_datetime }} UTC</span
            >
            </small>
        </div>
        </div>
    </div>
    </div>
    <!-- Button trigger modal -->
    <button
    type="button"
    class="btn btn-primary btn-md-full-width mt-3"
    data-bs-toggle="modal"
    data-bs-target="#changePasswordModal"
    >
    Change password
    </button>
    <div class="row">
        <div class="col-lg-6">
            @include('shared.validation-errors')
        </div>
    </div>
    </div>
    <!-- Modal -->
    <div
    class="modal fade"
    id="changePasswordModal"
    tabindex="-1"
    aria-labelledby="changePasswordModalLabel"
    aria-hidden="true"
    >
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="changePasswordModalLabel">
            Change password
            </h5>
            <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            ></button>
        </div>
        <form
            method="POST"
            action="{{ route('change-password') }}"
            class="row gy-3 gx-0 needs-validation"
            novalidate
        >
            @csrf
            <div class="modal-body">
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
                <div class="invalid-feedback">
                Invalid password format
                </div>
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
                <div id="newPasswordHelp" class="form-text">
                Min. 8 characters, 1 lower, 1 upper, 1 digit, 1
                special character.
                </div>
                <div class="invalid-feedback">
                Invalid password format
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal"
            >
                Close
            </button>
            <button
                type="submit"
                class="btn btn-primary"
            >
                Submit
            </button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection