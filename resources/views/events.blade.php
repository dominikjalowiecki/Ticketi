@extends('layouts.ticketi')

@section('title', 'Events')
@section('description', 'Events page of Ticketi service')

@section('content')
@include('shared.alerts')
<div class="row justify-content-end mb-3">
    <div class="col-auto">
      <form
        method="GET"
        id="sortForm"
        action="{{ route('events') }}"
        class="mt-2 mb-4 row gy-3 gx-0"
      >
        <div class="col-auto">
          <label for="sortInput" class="col-form-label"
            >Sort by <i class="bi bi-sort-alpha-down"></i
          ></label>
        </div>
        <div class="col-auto ms-2">
          <select
            class="form-select"
            id="sortInput"
            name="sort"
            aria-label="Events sort select"
          >
            @php
                $sortingOptions = ['Newest', 'Lowest price', 'Highest price', 'Most likes', 'Starting soon'];
            @endphp
            @foreach($sortingOptions as $option)
                <option value="{{ $option }}" @if (Request::get('sort') === $option) selected @endif>{{ $option }}</option>
            @endforeach
          </select>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 mb-5">
      <nav class="navbar navbar-light navbar-expand-lg">
        <div class="container-fluid px-0">
          <h4 class="fw-bold mb-0">Filters</h4>
          <button
            class="navbar-toggler p-3"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarToggleExternalContent"
            aria-controls="navbarToggleExternalContent"
            aria-expanded="false"
            aria-label="Toggle filters"
          >
            <i class="bi bi-funnel-fill"></i>
          </button>
        </div>
      </nav>
      <div
        class="collapse sidebar-collapse px-1 pt-4"
        id="navbarToggleExternalContent"
      >
        <form method="GET" id="filtersForm" action="{{ route('events') }}" class="needs-validation" onsubmit="return false;" novalidate>
          <div class="mb-3">
            <label for="searchInput" class="form-label"
              >Search</label
            >
            <input
              type="search"
              id="searchInput"
              class="form-control"
              name="search"
              maxlength="50"
              placeholder="Type to search..."
              value="{{ old('search', Request::get('search')) }}"
            />
          </div>
          <div class="mb-3">
            <label for="categorySelect" class="form-label"
              >Category</label
            >
            <select
              class="form-select"
              id="categorySelect"
              name="category"
              aria-label="Select category"
            >
              <option value>Select category...</option>
              @foreach ($categories as $category)
                <option value="{{ $category->name }}" @if (Request::get('category') === $category->name) selected @endif>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="cityInput" class="form-label"
              >City (+10km)</label
            >
            <input
              type="text"
              class="form-control"
              id="cityInput"
              list="citiesList"
              id="cityInput"
              name="city"
              maxlength="45"
              value="{{ old('city', Request::get('city')) }}"
              placeholder="Type and select from list..."
            />
            <datalist id="citiesList">
              @foreach ($cities as $city)
                <option value="{{ $city->name }}"></option>
              @endforeach
            </datalist>
          </div>
          <div class="mb-3 form-check">
            <input
              type="checkbox"
              class="form-check-input"
              id="underageInput"
              name="underage"
              value="True"
              @if(Request::get('underage')) checked @endif
            />
            <label class="form-check-label" for="underageInput"
              >Underage event</label
            >
          </div>
          <button
            class="btn btn-primary btn-lg-full-width"
            type="submit"
          >
            Submit
          </button>
          <a
            href="{{ route('events') }}"
            class="btn btn-secondary btn-lg-full-width"
          >
            Reset
          </a>
        </form>
        @include('shared.validation-errors')
      </div>
    </div>
    <div class="col-lg-9" id="eventsContainer">
    @if (count($events) > 0)
    @foreach ($events as $event)
      <div class="card mb-4">
        <span
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger likes-count"
        >
          <i class="bi bi-hand-thumbs-up-fill"></i><br />
          {{ $event->likes_count }}
          <span class="visually-hidden">unread messages</span>
        </span>
        <div class="row event-card-internal g-0">
          <div class="col-md-4 d-flex align-items-center">
            <img
              src="{{ $event->image ? Storage::url($event->image) : asset('/img/event-placeholder.webp') }}"
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
                      <span class="time-component"
                        >{{ $event->start_datetime }} UTC</span
                      >
                      · {{ $event->city_name }}
                    </div>
                    <div class="text-muted">
                      Uploaded
                      <span class="time-component"
                        >{{ $event->created_datetime }} UTC</span
                      >
                    </div>
                  </div>
                  @auth
                    @if (Auth::user()->hasRole('USER'))
                    <button
                        type="submit"
                        id="followEventButton"
                        data-action="{{ route('event.follow') }}"
                        data-id-event="{{ $event->id_event }}"
                        class="btn btn-primary add-favourite-btn"
                        aria-label="Add or remove from followed"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Toggle followed"
                    >
                        <i class="bi {{ !!$event->is_followed ? 'bi-star-fill' : 'bi-star' }}"></i>
                    </button>
                    @endif
                    @endauth
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    <nav
    class="d-flex justify-content-between align-items-center"
    aria-label="Page pagination"
    >
    <p>
        @php
              $itemsPageCount = $events->perPage() * $events->currentPage();
        @endphp
          {{ $itemsPageCount - $events->perPage() + 1 }}-{{ $itemsPageCount }} of {{ $events->total() }} results
        </p>
        {{ $events->links() }}
    </nav>
    @else
    <p class="h3 text-center">Items not found...</p>
    @endif
    </div>
  </div>
@endsection

@push('scripts')
<!-- Page specific JS-->
<script type="module" src="{{ asset('js/events.js') }}"></script>
@endpush