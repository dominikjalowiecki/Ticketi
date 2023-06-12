@extends('layouts.ticketi')

@section('title', 'Followed Events')
@section('description', 'Followed events page of Ticketi service')

@section('content')
@include('shared.alerts')
<div class="row">
    <div class="col">
        <h3 class="fw-bold mb-4">Favourites</h3>
        @if (count($followed_events) > 0)
        <div id="favouritesContainer">
            @foreach ($followed_events as $event)
                <div class="card feature-card mb-4">
                    <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger likes-count"
                    >
                    <i class="bi bi-hand-thumbs-up-fill"></i><br />
                    {{ $event->likes_count }}
                    <span class="visually-hidden">likes</span>
                    </span>
                    <div class="row event-card-internal g-0">
                    <div class="col-md-4 d-flex align-items-center">
                        <img
                        src="{{ $event->image ? $event->image : asset('/img/event-placeholder.webp') }}"
                        class="event-card-image rounded-start"
                        loading="lazy"
                        />
                    </div>
                    <div class="col-md-8 d-flex flex-column">
                        <div class="card-body">
                        <a
                            class="text-decoration-none link-dark stretched-link"
                            href="{{ route('event.page', [$event->url]) }}"
                            ><h5 class="card-title mb-3">{{ $event->name }}</h5>
                        </a>
                        <p class="card-text">
                            {{ strip_tags($event->excerpt) }}
                        </p>
                        <div
                            class="badge bg-primary bg-gradient rounded-pill mb-2"
                        >
                            {{ $event->category_name }}
                        </div>
                        @if (!$event->is_adult_only)
                            <div
                                class="badge bg-success bg-gradient rounded-pill mb-2"
                            >
                                Child allowed
                            </div>
                        @endif
                        <h4 class="card-text fw-bold mt-2">{{ $event->ticket_price }}&euro;</h4>
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
                                <span class="time-component">{{ $event->start_datetime }} UTC</span>
                                            · {{ $event->city_name }}
                                </div>
                                <div class="text-muted">
                                Uploaded
                                <span class="time-component">{{ $event->created_datetime }} UTC</span>
                                </div>
                            </div>
                            <button
                                class="btn-close btn-warning remove-favourite-btn"
                                data-action="{{ route('event.follow') }}"
                                data-id-event="{{ $event->id_event }}"
                                aria-label="Remove from followed"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Remove from followed"
                            ></button>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <p class="h3 text-center">Items not found...</p>
        @endif
        {{ $followed_events->links() }}
    </div>
@endsection

@push('scripts')
<!-- Page specific JS-->
<script type="module" src="{{ asset('js/followed.js') }}"></script>
@endpush