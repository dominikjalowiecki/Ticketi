@extends('layouts.base')

@section('ticketi')
<main class="flex-shrink-0">
    @include('shared.navigation')
    @yield('header')
    <!-- Content section-->
    <content class="py-5">
        <div class="container px-5 my-5">
            @yield('content')
        </div>
    </content>
</main>
<!-- Footer-->
<footer class="bg-dark py-4 mt-auto">
    <div class="container px-5">
        <div class="row my-4 text-center">
            <div class="col-12 text-light">
                <h4 class="fw-bold">Ticketi</h4>
                <p>
                    Created with
                    <a class="text-light" href="https://getbootstrap.com/" rel="nofollow">Bootstrap 5</a>
                    <br />
                    Dominik Jalowiecki &copy; 2023
                </p>
                <div class="d-flex justify-content-center">
                    <a href="https://github.com/dominikjalowiecki" class="mx-2" rel="nofollow"><i class="bi bi-github me-1"></i>GitHub</a>
                    <a href="mailto:dominik.jalowiecki1@gmail.com" class="mx-2"><i class="bi bi-envelope-fill me-1"></i>Email</a>
                    <a href="{{ route('cart.show') }}" class="mx-2"><i class="bi bi-basket3-fill me-1"></i>Shopping cart</a>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection
