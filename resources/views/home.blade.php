@extends('layouts.ticketi')

@section('title', 'Home')
@section('description', 'Home page of Ticketi service')

@section('header')
<!-- Header section-->
<header class="bg-dark py-5">
    <div class="container px-5">
        <div class="row gx-5 align-items-center justify-content-center">
            <div class="col-lg-8 col-xl-7 col-xxl-6">
                <div class="my-5 text-center text-xl-start">
                    <h1 class="display-5 fw-bolder text-white mb-2">Ticketi</h1>
                    <p class="lead fw-normal text-white-50 mb-4">
                        Web service with tickets for spectacular events.
                    </p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                        <a class="btn btn-primary btn-lg px-4 me-sm-3" href="{{ route('home') }}">View events</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                <img class="img-fluid rounded-3 my-5" src="{{ asset('img/event-placeholder.webp') }}" alt="Spectacular event" />
            </div>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="container px-5 mb-5">
    @include('shared.alerts')
    @if (!$popular_events->isEmpty())
    <div class="row">
        <div class="col">
            <div class="container px-5">
                <div class="row gx-5 mb-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <div class="text-center">
                            <h2 class="fw-bolder">Popular events</h2>
                        </div>
                    </div>
                </div>
                <div class="row gx-5">
                    @foreach ($popular_events as $event)
                    <div class="col-lg-4 mb-5">
                        <div class="card h-100">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger likes-count">
                                <i class="bi bi-hand-thumbs-up-fill"></i><br />
                                {{ $event->likes_count }}
                                <span class="visually-hidden">likes</span>
                            </span>
                            <img class="card-img-top event-card-image" src="{{ $event->image ? $event->image : asset('/img/event-placeholder.webp') }}" />
                            <div class="card-body p-4">
                                <a class="text-decoration-none link-dark stretched-link" href="{{ route('event.page', [$event->url]) }}">
                                    <h5 class="card-title mb-3">{{ $event->name }}</h5>
                                </a>
                                <p class="card-text">
                                    {{ strip_tags($event->excerpt) }}
                                </p>
                                <div class="badge bg-primary bg-gradient rounded-pill mb-2">
                                    {{ $event->category_name }}
                                </div>
                                @if (!$event->is_adult_only)
                                <div class="badge bg-success bg-gradient rounded-pill mb-2">
                                    Child allowed
                                </div>
                                @endif
                                <h4 class="card-text fw-bold mt-2">{{ $event->ticket_price }}&euro;</h4>
                            </div>
                            <div class="card-footer p-4 pt-0 bg-transparent border-top-0">
                                <div class="d-flex align-items-end justify-content-between">
                                    <div class="d-flex align-items-end">
                                        <div class="small">
                                            <div class="fw-bold">
                                                Starting <span class="time-component">{{ $event->start_datetime }} UTC</span>
                                                · {{ $event->city_name }}
                                            </div>
                                            <div class="text-muted">
                                                Uploaded <span class="time-component">{{ $event->created_datetime }} UTC</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection