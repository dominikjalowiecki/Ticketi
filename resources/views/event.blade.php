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
        {{ $event->name }}
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
        <div class="carousel-item active">
        <img
            src="{{ asset('/img/event-placeholder.webp') }}"
            class="d-block w-100"
        />
        </div>
        <div class="carousel-item">
        <img
            src="{{ asset('/img/event-placeholder.webp') }}"
            class="d-block w-100"
        />
        </div>
        <div class="carousel-item">
        <img
            src="{{ asset('/img/event-placeholder.webp') }}"
            class="d-block w-100"
        />
        </div>
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
        99
        <span class="visually-hidden">unread messages</span>
    </span>
    <div class="row h-100 g-0">
        <div class="col d-flex flex-column">
        <div class="card-body p-0">
            <h5 class="card-title mt-3 mt-md-0 mb-3">
            Blog post title
            </h5>
            <div
            class="badge bg-primary bg-gradient rounded-pill mb-2"
            >
            News
            </div>
            <div
            class="badge bg-success bg-gradient rounded-pill mb-2"
            >
            Child allowed
            </div>
            <h4 class="card-text fw-bold my-1">170.00&euro;</h4>
            <span class="badge bg-secondary">Ticket count: 203</span>
            <span class="badge bg-secondary">Sold out!</span>
            <div class="mt-4">
            <a href="cart.html" class="btn btn-primary disabled"
                >Add to cart</a
            >
            <a href="admin-edit-event.html" class="btn btn-danger"
                >Edit event</a
            >
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
                    >2023-05-5 5:29:44 UTC</span
                    >
                </div>
                <div class="text-muted">
                    Uploaded
                    <span class="time-component"
                    >2023-05-15 23:29:44 UTC</span
                    >
                </div>
                </div>
                <div>
                <button
                    class="btn btn-primary add-favourite-btn"
                    data-event-id="3"
                    aria-label="Add or remove from favourites"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Add to favourite"
                >
                    <i class="bi bi-star-fill"></i>
                </button>
                <button
                    class="btn btn-outline-primary like-comment-btn"
                    aria-label="Like comment"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Like event"
                >
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </button>
                <button
                    class="btn btn-warning"
                    aria-label="Like comment"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Add to Google Calendar"
                >
                    <i class="bi bi-calendar-fill"></i>
                </button>
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
    <p>Hello World!</p>
    <p>Some initial <strong>bold</strong> text</p>
    <ol>
    <li>
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. In,
        totam!
    </li>
    <li>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea
        tenetur quae expedita esse!
    </li>
    <li>Lorem ipsum dolor sit amet consectetur adipisicing.</li>
    </ol>
</div>
<div class="col-md-5 mt-3 mt-md-0 d-flex align-items-center">
    <!-- <div class="embed-responsive embed-responsive-16by9">
        <iframe
        class="embed-responsive-item"
        src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0"
        allowfullscreen
        ></iframe>
    </div> -->
    <img src="{{ asset('/img/event-placeholder.webp') }}" class="img-fluid" />
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
    src="https://www.google.com/maps/embed/v1/place?key={{ config('ticketi.maps') }}&q=Sosnowiec,Będzińska+39"
    >
    </iframe>
</div>
</div>
<hr class="mb-5" />
<div class="row">
<div class="col">
    <div class="mb-4">
    <form id="commentForm" method="POST">
        <div class="mb-3">
        <label for="exampleFormControlTextarea1" class="form-label"
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
    <!-- Comments Show More button lazy loading / eager load / animation fade in out-->
    <div class="progress mb-3">
    <div
        class="progress-bar progress-bar-striped progress-bar-animated bg-danger w-75"
        role="progressbar"
        aria-valuenow="75"
        aria-valuemin="0"
        aria-valuemax="100"
    ></div>
    </div>
    <div id="commentsContainer">
    <div class="card feature-card">
        <div class="card-body">
        <h5 class="card-title">Charles Adas</h5>
        <p class="card-text">
            With supporting text below as a natural lead-in to
            additional content.
        </p>
        </div>
        <div class="card-footer bg-transparent border-top-0">
        <div
            class="d-flex align-items-end justify-content-between w-100"
        >
            <div class="text-muted">
            Created
            <span class="time-component"
                >2023-05-5 5:29:44 UTC</span
            >
            ·
            <span class="badge bg-primary"
                ><span class="likes-counter">251</span>
                <i class="bi bi-hand-thumbs-up-fill"></i
            ></span>
            </div>
            <div>
            <button
                class="btn btn-outline-primary like-comment-btn"
                aria-label="Like comment"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="Like comment"
            >
                <i class="bi bi-hand-thumbs-up-fill"></i>
            </button>
            <button
                class="btn-close btn-warning remove-comment-btn"
                value="3"
                aria-label="Remove comment"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                title="Remove comment"
            ></button>
            </div>
        </div>
        </div>
    </div>
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
    <button id="loadCommentsBtn" class="btn btn-danger">
    Show more comments
    </button>
</div>
</div>
@endsection

@push('scripts')
<!-- Page specific JS-->
<script type="module" src="{{ asset('js/event.js') }}"></script>
@endpush