@extends('layouts.ticketi')

@section('title', $event->name)
@section('description', 'Event page of ' . $event->name . ' in Ticketi service')

@section('content')
@include('shared.alerts')
<div class="row">
<nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
    <ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">Events</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">
        <a id="eventLink" class="link-secondary text-decoration-none" href="{{ route('event.page', [$event->url]) }}">{{ $event->name }}</a>
    </li>
    </ol>
</nav>
</div>
<div class="row mt-3 mb-5">
<div class="col-md-5">
    <div
    id="carouselExampleControls"
    class="carousel slide"
    data-bs-ride="carousel"
    >
    <div class="carousel-inner">
        @if (count($images) > 0)
        @foreach ($images as $key => $image)
        <div class="carousel-item {{ ($key === 0) ? 'active' : '' }}">
        <img
            src="{{ $image->url }}"
            class="d-block w-100"
        />
        </div>
        @endforeach
        @else
        <div class="carousel-item active">
        <img
            src="{{ asset('/img/event-placeholder.webp') }}"
            class="d-block w-100"
        />
        </div>
        @endif
    </div>
    <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleControls"
        data-bs-slide="prev"
    >
        <span
        class="carousel-control-prev-icon"
        aria-hidden="true"
        ></span>
        <span class="visually-hidden">Previous slide</span>
    </button>
    <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleControls"
        data-bs-slide="next"
    >
        <span
        class="carousel-control-next-icon"
        aria-hidden="true"
        ></span>
        <span class="visually-hidden">Next slide</span>
    </button>
    </div>
</div>
<div class="col-md-7">
    <div class="card h-100 border-0 mb-4">
    <span
        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger likes-count"
    >
        <i class="bi bi-hand-thumbs-up-fill"></i><br />
        <span id="likesCount">{{ $event->likes_count }}</span>
        <span class="visually-hidden">likes</span>
    </span>
    <div class="row h-100 g-0">
        <div class="col d-flex flex-column">
        <div class="card-body p-0">
            <h5 class="card-title mt-3 mt-md-0 mb-3">
            {{ $event->name }}
            </h5>
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
            <h4 class="card-text fw-bold my-1">{{ $event->ticket_price }}&euro;</h4>
            @if ($event->ticket_count > 0)
            <span class="badge bg-secondary">Ticket count: {{ $event->ticket_count }}</span>
            @else
            <span class="badge bg-secondary">Sold out!</span>
            @endif
            <div class="mt-4">
            @if (!Auth::user() || Auth::user()->hasRole('USER'))
            <form method="POST" action="{{ route('cart.show')}}">
                @csrf
                <input type="hidden" name="idEvent" value="{{ $event->id_event }}">
                <button type="submit" class="btn btn-primary {{ ($event->ticket_count <= 0) ? 'disabled' : '' }}"
                    >Add to cart</button
                >
            </form>
            @elseif (Auth::user()->hasRole('MODERATOR'))
                <a href="admin-edit-event.html" class="btn btn-danger"
                    >Edit event</a
                >
            @endif
            </div>
        </div>
        <div class="card-footer p-0 bg-transparent border-top-0">
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
                    >{{ $event->start_datetime }} UTC</span
                    >
                </div>
                <div class="text-muted">
                    Uploaded
                    <span class="time-component"
                    >{{ $event->created_datetime }} UTC</span
                    >
                </div>
                </div>
                <div>
                @auth
                @if (Auth::user()->hasRole('USER'))
                <form id="followEventForm" method="POST" class="d-inline" action="{{ route('event.follow') }}" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="idEvent" value="{{ $event->id_event }}">
                    <button
                        type="submit"
                        id="followEventButton"
                        class="btn btn-primary add-favourite-btn"
                        aria-label="Add or remove from favourites"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Add to favourite"
                    >
                        <i class="bi {{ $is_followed ? 'bi-star-fill' : 'bi-star' }}"></i>
                    </button>
                </form>
                @endif
                @endauth
                @if (!Auth::user() || Auth::user()->hasRole('USER'))
                <form id="likeEventForm" method="POST" class="d-inline" action="{{ route('event.like') }}" onsubmit="return false;">
                    @csrf
                    <input type="hidden" name="idEvent" value="{{ $event->id_event }}">
                    <button
                        type="submit"
                        id="likeEventButton"
                        class="btn btn-outline-primary like-comment-btn"
                        aria-label="Like comment"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Like event"
                    >
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                    </button>
                </form>
                <button
                    class="btn btn-warning"
                    aria-label="Like comment"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Add to Google Calendar"
                >
                    <i class="bi bi-calendar-fill"></i>
                </button>
                @endif
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
</div>
<div class="row my-5">
<div class="col-12">
    <h3 class="mb-3">Description</h3>
</div>
<div class="col-md-7">
    {!! $event->description !!}
</div>
<div class="col-md-5 mt-3 mt-md-0 d-flex align-items-center">
    @if ($video)
    <div class="ratio ratio-16x9">
        <iframe
        src="{{ $video->url }}"
        allowfullscreen
        ></iframe>
    </div>
    @else
    <img src="{{ asset('/img/event-placeholder.webp') }}" class="img-fluid" />
    @endif
</div>
</div>
<div class="row my-5">
<div class="col">
    <h3>Location</h3>
    <iframe
    height="300"
    class="w-100 mt-3"
    loading="lazy"
    allowfullscreen
    referrerpolicy="no-referrer-when-downgrade"
    src="https://www.google.com/maps/embed/v1/place?key={{ config('ticketi.embedMaps') }}&q={{ $event->city_name }},{{ $event->street }}"
    >
    </iframe>
</div>
</div>
<hr class="mb-5" />
<div class="row">
<div class="col">
    @auth
    @if (Auth::user()->hasRole('USER'))
    <div class="mb-4">
    <form id="commentForm" class="needs-validation"  method="POST" action="{{ route('event.addComment') }}" onsubmit="return false;" novalidate>
        @csrf
        <input type="hidden" name="idEvent" value="{{ $event->id_event }}">
        <div class="mb-3">
        <label for="commentTextarea" class="form-label"
            >Comment content</label
        >
        <textarea
            class="form-control"
            id="commentTextarea"
            name="comment"
            maxlength="200"
            rows="4"
        ></textarea>
        </div>
        <button
        type="submit"
        class="btn btn-primary btn-md-full-width"
        >
        Submit
        </button>
    </form>
    </div>
    @endif
    @endauth
    <div class="progress mb-3">
    <div
        class="progress-bar progress-bar-striped progress-bar-animated bg-danger w-75"
        role="progressbar"
        aria-valuenow="75"
        aria-valuemin="0"
        aria-valuemax="100"
    ></div>
    @csrf
    </div>
    <div id="commentsContainer">
        @include('shared.comments')
    </div>
</div>
</div>
<div id="commentsSpinner" class="row text-center mt-3 d-none">
<div class="col">
    <div class="spinner-grow" role="status">
    <span class="visually-hidden">Loading...</span>
    </div>
</div>
</div>
<div class="row text-center mt-3">
<div class="col">
    <button id="loadCommentsBtn" data-id-event="{{ $event->id_event }}" data-action="{{ route('event.getComments') }}" class="btn btn-danger">
    Show more comments
    </button>
</div>
</div>
@endsection

@push('scripts')
<!-- Page specific JS-->
<script type="module" src="{{ asset('js/event.js') }}"></script>
@endpush