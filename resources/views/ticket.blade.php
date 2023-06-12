@extends('layouts.base')

@section('title', 'Ticket')
@section('description', 'Ticket page of Ticketi service')

@section('ticketi')
<main class="flex-shrink-0">
<!-- Content section-->
<content class="py-5 text-center">
    <div class="container my-4">
    <div class="row mb-4">
        <h1 class="mb-3">
        <a
            class="navbar-brand fw-bold text-decoration-underline text-primary"
            href="{{ route('home') }}"
            >Ticketi</a
        >
        </h1>
    </div>
    <div class="row">
        <h3>{{ $ticket->user_name }}</h3>
    </div>
    <div class="row mb-4">
        <h4>{{ substr($ticket->birthdate, 0, 10) }}</h4>
    </div>
    <div class="row justify-content-center mb-3">
        <div class="col">
        {!! QrCode::size(300)->generate(URL::signedRoute('ticket', [$ticket->id_order])) !!}
        </div>
    </div>
    <div class="row mb-3">
        <p class="text-muted">{{ $ticket->id_order }}</p>
    </div>
    <div class="row mb-5">
        <p class="fw-bold">
        Event: {{ $ticket->id_event . '. ' . $ticket->event_name }}
        <span class="time-component">{{ $ticket->start_datetime }} UTC</span>
        </p>
    </div>
    <div class="row">
        <small
        >Bought
        <span class="time-component">{{ $ticket->created_datetime }} UTC</span></small
        >
    </div>
    </div>
</content>
</main>
<!-- Footer-->
<footer class="py-4 mt-auto">
<div class="container text-center">
    <div class="row">
    <p>Dominik Jalowiecki &copy; 2023</p>
    </div>
</div>
</footer>
@endsection