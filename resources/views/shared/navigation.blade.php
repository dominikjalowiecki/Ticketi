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
                            <a class="nav-link btn btn-primary btn-sm me-2 position-relative {{ Route::is('followed') ? 'active' : '' }}" {{ Route::is('followed') ? 'aria-current=page' : '' }} href="{{ route('followed') }}"><i class="bi bi-star-fill me-1"></i>Followed</a>
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
                        <a id="cart-popover" class="nav-link {{ Route::is('cart.show') ? 'active' : '' }}" {{ Route::is('cart.show') ? 'aria-current=page' : '' }} href="{{ route('cart.show') }}"><i class="bi bi-basket3-fill me-1"></i>Cart
                            <span id="cart-items-count" class="badge rounded-pill bg-primary">{{ get_cart_items_count() }}</span>
                            <span id="cart-total">{{ number_format(get_cart_total(), 2, '.', ',') }}&euro;</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
@if (!Auth::user() || !Auth::user()->hasRole('MODERATOR'))
<div id="cart-popover-content">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Cost</th>
            </tr>
        </thead>
        <tbody>
            @if (count($cartItems = Session::get('cart_items', [])) > 0)
                @foreach (Session::get('cart_items', []) as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['price'] }}&euro;</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" class="text-center">Empty...</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endif