@extends('layouts.ticketi')

@section('title', 'Register')
@section('description', 'Registration page of Ticketi service')

@section('content')
<div class="row">
    <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('login') }}">Login</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                Register
            </li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-4">
        <h3 class="fw-bold">@yield('title')</h3>
        <form method="POST" action="{{ route('register') }}" class="my-4 row gy-3 gx-0 needs-validation" novalidate>
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
                <label for="birthdateInput" class="form-label">{{ __('Birthdate') }}</label>
                <input type="date" class="form-control" name="birthdate" id="ebirthdateInput" value="{{ old('birthdate') }}" required />
                <div class="invalid-feedback">
                    Birthdate cannot be in future
                </div>
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
        @if ($errors->any())
        <hr />
        <div class="alert alert-danger text-center" role="alert">
            <ol class="list-group list-group-flush list-group-numbered">
                @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">
                    {{ $error }}
                </li>
                @endforeach
            </ol>
        </div>
        @endif
    </div>
    <div id="register-illustration" class="col-lg-8 d-md-none d-lg-block bg-image"></div>
</div>
@endsection

@push('scripts')
<!-- Page specific JS-->
<script type="module" src="{{ asset('js/register.js') }}"></script>
@endpush