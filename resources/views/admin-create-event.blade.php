@extends('layouts.ticketi-admin')

@section('content')
<h1 class="mt-4">Create event</h1>
<div class="row">
    <div class="col-md-6">
    <form
        method="POST"
        action="{{ route('admin.createEvent') }}"
        class="my-4 row gy-3 gx-0 needs-validation"
        id="createEventForm"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf
        <div class="mb-1">
        <label for="nameInput" class="form-label"
            >Name</label
        >
        <input
            type="text"
            class="form-control"
            name="name"
            id="nameInput"
            maxlength="150"
            value="{{ old('name') }}"
            required
            autofocus
        />
        <div class="invalid-feedback">Name is required</div>
        </div>
        <div class="mb-5">
        <div id="descriptionInput">
            {!! old('description', '
                <p>Hello World!</p>
                <p>Some initial <strong>bold</strong> text</p>
            ') !!}
        </div>
        <input type="hidden" name="description" id="descriptionContentInput" required />
        </div>
        <div class="mb-1">
        <label for="categorySelect" class="form-label"
            >Category</label
        >
        <select
            class="form-select"
            id="categorySelect"
            name="idCategory"
            aria-label="Category select"
            required
        >
            <option value>Select category...</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id_category }}" @if (old('idCategory') == $category->id_category) selected @endif>{{ $category->name }}</option>
            @endforeach
        </select>
        </div>
        <div class="mb-1">
        <label for="tagsInput" class="form-label">Tags</label>
        <input
            type="text"
            class="form-control"
            id="tagsInput"
            aria-describedby="tagsHelp"
        />
        <div id="tagsHelp" class="form-text">
            Click 'Enter' to input tag
        </div>
        <input type="hidden" name="tags" id="tagsContentInput" value="{{ old('tags', '[]') }}" />
        <div
            id="tagsList"
            class="d-flex gap-1 flex-wrap mt-3"
        ></div>
        </div>
        <div class="mb-1">
            <label for="cityInput" class="form-label"
            >City</label
            >
            <input
            list="cityDatalist"
            class="form-control"
            id="cityInput"
            name="city"
            maxlength="45"
            minlength="3"
            data-url="{{ route('stats.cities') }}"
            value="{{ old('city') }}"
            aria-describedby="cityHelp"
            required
            />
            <div id="cityHelp" class="form-text">
                Input min. 3 letters to get hint
            </div>
            <datalist id="cityDatalist">
            </datalist>
        </div>
        <div class="mb-1">
            <label for="streetInput" class="form-label">Street</label>
            <input
                type="text"
                class="form-control"
                name="street"
                id="streetInput"
                maxlength="65"
                value="{{ old('street') }}"
                required
            />
            <div class="invalid-feedback">Street is required</div>
        </div>
        <div class="mb-1">
        <label for="postalCodeInput" class="form-label"
            >Postal code</label
        >
        <input
            type="text"
            class="form-control"
            name="postalCode"
            id="postalCodeInput"
            maxlength="6"
            value="{{ old('postalCode') }}"
            required
        />
        <div class="invalid-feedback">Postal code is required</div>
        </div>
        <div class="mb-1">
        <label for="ticketPriceInput" class="form-label"
            >Ticket price</label
        >
        <div class="input-group">
            <input
            type="number"
            id="ticketPriceInput"
            name="ticketPrice"
            class="form-control"
            max="9999.0"
            min="1.0"
            step="0.01"
            aria-label="Price (in euro)"
            value="{{ old('ticketPrice') }}"
            required
            />
            <span class="input-group-text">&euro;</span>
            <div class="invalid-feedback">Ticket price is required</div>
        </div>
        </div>
        <div class="mb-1">
        <label for="ticketCountInput" class="form-label"
            >Ticket count</label
        >
        <input
            type="number"
            id="ticketCountInput"
            class="form-control"
            name="ticketCount"
            min="1"
            max="99999"
            value="{{ old('ticketCount') }}"
            required
        />
        <div class="invalid-feedback">Ticket count is required</div>
        </div>
        <div class="mb-1">
        <label for="startDatetimeInput" class="form-label"
            >Start datetime</label
        >
        <input
            type="datetime-local"
            class="form-control"
            id="startDatetimeInput"
            required
        />
        <input
            type="hidden"
            name="startDatetime"
            id="startDatetimeContentInput"
            value="{{ old('startDatetime') }}"
        />
        <div class="invalid-feedback">
            Start datetime cannot be in past
        </div>
        </div>
        <div class="mb-3">
        <label for="imagesUploadInput" class="form-label"
            >Images upload</label
        >
        <input
            class="form-control"
            type="file"
            name="images[]"
            id="imagesUploadInput"
            aria-describedby="imagesUploadHelp"
            accept="image/jpeg,image/jpg,image/png,image/gif"
            multiple
            required
        />
        <div id="imagesUploadHelp" class="form-text">
            Image can be jpeg, png or gif, max 1mb, max 4 images
        </div>
        <div class="invalid-feedback">Invalid file format</div>
        <div
            id="imagesThumbnailsContainer"
            class="d-flex flex-wrap flex-lg-nowrap align-items-start mt-3"
        ></div>
        </div>
        <div class="mb-3">
        <label for="videoUploadInput" class="form-label"
            >Video upload</label
        >
        <input
            class="form-control"
            type="file"
            name="video"
            id="videoUploadInput"
            accept="video/mp4,video/mov,.mp4,.mov"
            aria-describedby="videoUploadHelp"
        />
        <div id="videoUploadHelp" class="form-text">
            Video can be .mov or .mp4, max 10mb
        </div>
        <div class="invalid-feedback">Invalid video format</div>
        </div>
        <div class="mb-1 form-check">
        <input
            type="checkbox"
            name="childAllowed"
            value="true"
            class="form-check-input"
            id="childAllowedCheckbox"
            @if (old('childAllowed')) checked @endif
        />
        <label class="form-check-label" for="childAllowedCheckbox"
            >Child allowed</label
        >
        </div>
        <div class="mb-1 form-check">
        <input
            type="checkbox"
            name="isDraft"
            value="true"
            class="form-check-input"
            id="isDraftCheckbox"
            checked
        />
        <label class="form-check-label" for="isDraftCheckbox"
            >Is draft</label
        >
        </div>
        <div>
        <button
            type="submit"
            class="btn btn-primary btn-md-full-width"
        >
            Create
        </button>
        </div>
    </form>
    @include('shared.validation-errors')
    </div>
    <div
    id="create-event-illustration"
    class="col-md-6 d-md-none d-lg-block bg-image"
    ></div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/adm-styles.css') }}" rel="stylesheet" />
<link href="{{ asset('css/quill.snow.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
@endpush

@push('scripts')
<!-- Page specific JS-->
<script src="{{ asset('js/quill.js') }}"></script>
<script type="module" src="{{ asset('js/create-event.js') }}"></script>
@endpush