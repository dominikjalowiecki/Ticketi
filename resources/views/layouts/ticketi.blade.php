@extends('layouts.base')

@section('ticketi')
<main class="flex-shrink-0">
    @include('shared.navigation')
    @yield('header')
    <!-- Content section-->
    <content class="py-5">
        <div class="container px-4 my-5">
            @yield('content')
        </div>
    </content>
</main>
<!-- Footer-->
<footer class="bg-dark py-4 mt-auto">
    <div class="container px-4">
        <div class="row my-4 text-center">
            <div class="col-12 text-light">
                <h4 class="fw-bold">Ticketi</h4>
                <p>
                    Created with
                    <a class="text-light" href="https://getbootstrap.com/" rel="nofollow">Bootstrap 5</a>
                    <br />
                    Dominik Jalowiecki &copy; 2023
                </p>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="https://github.com/dominikjalowiecki" class="mx-2 d-flex flex-column footer-link" rel="nofollow"><i class="bi bi-github"></i>GitHub</a>
                    <a href="mailto:dominik.jalowiecki1@gmail.com" class="mx-2 d-flex flex-column footer-link"><i class="bi bi-envelope-fill"></i>Email</a>
                    <a href="{{ route('cart.show') }}" class="mx-2 d-flex flex-column footer-link"><i class="bi bi-basket3-fill"></i>Cart</a>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection
