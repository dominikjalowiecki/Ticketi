<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="Administration Panel of Ticketi service" />
    <meta name="author" content="Dominik Jałowiecki" />
    <meta name="robots" content="noindex" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ config('app.url') . '/' }}">
    <title>Ticketi - Administration Panel</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link href="{{ asset('css/simple-datatables.style.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/adm-bootstrap.css') }}" rel="stylesheet" />
    @stack('styles')
    <script
      src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
      <!-- Navbar Brand-->
      <a class="navbar-brand ps-3 fw-bold" href="{{ route('home') }}">Ticketi</a>
      <!-- Sidebar Toggle-->
      <button
        class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
        id="sidebarToggle"
        href="#!"
      >
        <i class="fas fa-bars"></i>
      </button>
      <!-- Navbar Search-->
      <form
        method="GET"
        action={{ route('admin.search') }}
        class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"
      >
        <div class="input-group">
          <input
            class="form-control"
            type="search"
            name='s'
            placeholder="Search for events..."
            aria-label="Search for events..."
            aria-describedby="btnNavbarSearch"
            maxlength="50"
          />
          <button class="btn btn-primary" id="btnNavbarSearch" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
      <!-- Navbar-->
      <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle {{ Route::is('admin.change-password') ? 'active' : '' }}"
            id="navbarDropdown"
            href="#"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            ><i class="fas fa-user fa-fw"></i
          ></a>
          <ul
            class="dropdown-menu dropdown-menu-end"
            aria-labelledby="navbarDropdown"
          >
            <li>
              <a class="dropdown-item {{ Route::is('admin.change-password') ? 'active' : '' }}" {{ Route::is('admin.change-password') ? 'aria-current=page' : '' }} href="{{ route('admin.change-password') }}"
                >Change password</a
              >
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</a>
                </form>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
    <div id="layoutSidenav">
      <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
          <div class="sb-sidenav-menu">
            <div class="nav">
              <div class="sb-sidenav-menu-heading">Navigation</div>
                @php
                $navigation = [
                    'Dashboard' => ['admin.dashboard', 'fa-tachometer-alt'],
                    'Orders' => ['admin.orders', 'fa-truck-fast'],
                    'Add new moderator' => ['admin.addModerator', 'fa-plus'],
                    'Add new event' => ['admin.createEvent', 'fa-plus'],
                ];
                @endphp
                @foreach($navigation as $name => $route)
                    <a class="nav-link {{ Route::is($route[0]) ? 'active' : '' }}" {{ Route::is($route[0]) ? 'aria-current=page' : '' }} href="{{ route($route[0]) }}">
                        <div class="sb-nav-link-icon">
                          <i class="fa-solid {{ $route[1] }}"></i>
                        </div>
                        {{ $name }}
                    </a>
                @endforeach
            </div>
          </div>
          <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ Auth::user()->name . ' ' . Auth::user()->surname  }}
          </div>
        </nav>
      </div>
      <div id="layoutSidenav_content">
        <main>
          <div class="container-fluid px-4">
            @include('shared.alerts')
            @yield('content')
          </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
          <div class="container-fluid px-4">
            <div
              class="d-flex align-items-center justify-content-between small"
            >
              <div class="text-muted">Copyright &copy; Dominik Jalowiecki 2023</div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    @prepend('scripts')
    <script type="module" src="{{ asset('js/adm-scripts.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/simple-datatables.min.js') }}"></script>
    <script src="{{ asset('js/adm-datatables-simple.js') }}"></script>
    @endprepend
    @stack('scripts')
  </body>
</html>
