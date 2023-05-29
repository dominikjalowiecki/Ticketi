<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container px-5">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">Ticketi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @php
                    $navigation = [
                        'Home' => 'home',
                        'Events' => 'home',
                        'FAQ' => 'faq',
                    ];
                @endphp
                @foreach($navigation as $name => $route)
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is($route) ? 'active' : '' }}" {{ Route::is($route) ? 'aria-current=page' : '' }} href="{{ route($route) }}">{{ $name }}</a>
                    </li>
                @endforeach

                @auth
                    @if (Auth::user()->hasRole('USER'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ Route::is('user-profile') ? 'active' : '' }}" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">User profile</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                <li>
                                    <a class="dropdown-item {{ Route::is('user-profile') ? 'active' : '' }}" {{ Route::is('user-profile') ? 'aria-current=page' : '' }} href="{{ route('user-profile') }}">Go to user profile</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-info btn-sm me-2 text-dark position-relative {{ Route::is('home') ? 'active' : '' }}" {{ Route::is('home') ? 'aria-current=page' : '' }} href="{{ route('home') }}"><i class="bi bi-star-fill me-1"></i>Favourite
                                <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">New alerts</span>
                                </span></a>
                        </li>
                    @elseif (Auth::user()->hasRole('MODERATOR'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ Route::is('home') ? 'active' : '' }}" id="navbarDropdownAdmin" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Administration panel</a>
                            <ul class="dropdown-menu dropdown-menu-end mb-3" aria-labelledby="navbarDropdownAdmin">
                                <li>
                                    <a class="dropdown-item {{ Route::is('home') ? 'active' : '' }}" {{ Route::is('home') ? 'aria-current=page' : '' }} href="">Go to administration panel</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                @else
                    @php
                        $navigation = [
                            'Login' => 'login',
                            'Register' => 'register',
                        ];
                    @endphp
                    @foreach($navigation as $name => $route)
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is($route) ? 'active' : '' }}" {{ Route::is($route) ? 'aria-current=page' : '' }} href="{{ route($route) }}">{{ $name }}</a>
                        </li>
                    @endforeach
                @endauth
                @if (!Auth::user() || !Auth::user()->hasRole('MODERATOR'))
                    <li class="nav-item">
                        <a id="cart-popover" class="nav-link" href="cart.html"><i class="bi bi-basket3-fill me-1"></i>Cart
                            <span id="cart-items-count" class="badge rounded-pill bg-info text-dark">3</span>
                            <span id="cart-total">135.50&euro;</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@if (!Auth::user() || !Auth::user()->hasRole('MODERATOR'))
<div id="cart-popover-content">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Cost</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Event 1</td>
                <td>100.00&euro;</td>
            </tr>
            <tr>
                <td>Event 2</td>
                <td>80.00&euro;</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>Sum</td>
                <td>180.00&euro;</td>
            </tr>
        </tfoot>
    </table>
</div>
@endif