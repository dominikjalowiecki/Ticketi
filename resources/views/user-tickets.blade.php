@extends('layouts.user-profile')

@section('user-profile-content')
@if (count($tickets) > 0)
@foreach ($tickets as $ticket)
<div class="card mb-4">
    <div class="row order-card-internal g-0">
        <div class="col d-flex flex-column">
        <div class="card-body">
            <a
            class="text-decoration-none link-dark"
            href="{{ route('event.page', [$ticket->url]) }}"
            ><h5
                class="card-title mb-3 text-decoration-underline text-primary"
            >
                {{ $ticket->name }}
            </h5>
            </a>
            <div
            class="badge bg-primary bg-gradient rounded-pill mb-2"
            >
            {{ $ticket->category_name }}
            </div>
            @if (!$ticket->is_adult_only)
            <div
            class="badge bg-success bg-gradient rounded-pill mb-2"
            >
            Child allowed
            </div>
            @endif
            <h4 class="card-text fw-bold mt-2">{{ $ticket->price }}&euro;</h4>
        </div>
        <div class="card-footer bg-transparent border-top-0">
            <div
            class="d-flex align-items-end justify-content-between"
            >
            <div
                class="d-flex align-items-end justify-content-between w-100"
            >
                <div class="small">
                <div class="fw-bold">
                    Starting
                    <span class="time-component"
                    >{{ $ticket->start_datetime }} UTC</span
                    >
                    · {{ $ticket->city_name }}
                </div>
                <div class="text-muted">
                    Bought
                    <span class="time-component"
                    >{{ $ticket->purchase_datetime }} UTC</span
                    >
                </div>
                </div>
                <a class="btn btn-primary mt-2" href="{{ URL::signedRoute('ticket', [$ticket->id_order]) }}"
                >View ticket</a
                >
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
@endforeach
@else
<p class="h3 text-center">Items not found...</p>
@endif
{{ $tickets->links() }}
@endsection