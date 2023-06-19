@extends('layouts.ticketi')

@section('title', 'Verify Email')
@section('description', 'Email verification prompt of Ticketi service')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <h3 class="fw-bold">@yield('title')</h3>

        <div class="card text-dark bg-light mt-5">
            <div class="card-header">Prompt</div>
            <div class="card-body">
                <p class="card-text">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>
                @if (session('status') == 'verification-link-sent')
                <p class="card-text text-success">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </p>
                @endif
                <div class="mt-4 d-flex align-items-center justify-content-between">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">{{ __('Resend Verification Email') }}</button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection