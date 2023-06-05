@extends('layouts.ticketi')

@section('title', 'User Profile')
@section('description', 'User Profile page of Ticketi service')

@section('content')
@include('shared.alerts')
<div class="row mb-5">
  <div class="col"><h3 class="fw-bold">User profile</h3></div>
</div>
<div class="row">
  <div class="col-lg-3 mb-5">
    <div class="navbar-nav">
      <ul class="nav flex-column list-group list-group-flush">
        @php
          $navigation = [
              'Dashboard' => 'user-profile',
              'Tickets' => 'user-tickets',
              'Contact' => 'user-contact-form',
          ];
        @endphp
        @foreach($navigation as $name => $route)
          <li class="nav-item list-group-item">
              <a class="nav-link {{ Route::is($route) ? 'active' : '' }}" {{ Route::is($route) ? 'aria-current=page' : '' }} href="{{ route($route) }}">{{ $name }}</a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="col-lg-9">
    @yield('user-profile-content')
  </div>
</div>
@endsection