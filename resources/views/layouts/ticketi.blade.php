<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="@yield('description')" />
    <meta name="author" content="Dominik Jałowiecki" />
    <meta name="robots" content="noindex" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticketi - @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">
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
                        Dominik Jałowiecki &copy; 2023
                    </p>
                    <div class="d-flex justify-content-center">
                        <a href="" class="mx-2" rel="nofollow"><i class="bi bi-github me-1"></i>GitHub</a>
                        <a href="" class="mx-2"><i class="bi bi-envelope-fill me-1"></i>Email</a>
                        <a href="" class="mx-2"><i class="bi bi-basket3-fill me-1"></i>Shopping cart</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @prepend('scripts')
    <!-- Bootstrap core JS-->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('js/scripts.js') }}"></script>
    @endprepend
    <!-- @yield('js_include') -->
    @stack('scripts')
</body>

</html>