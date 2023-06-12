@extends('layouts.ticketi-admin')

@section('content')
{{-- <h1 class="mt-4">Add moderator</h1>
<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ route('admin.addModerator') }}" class="my-4 row gy-3 gx-0 needs-validation" novalidate>
            @csrf
            <div class="mb-1">
                <label for="emailInput" class="form-label">{{ __('Email') }}</label>
                <input type="email" class="form-control" name="email" id="emailInput" maxlength="150" value="{{ old('email') }}" required autofocus />
                <div class="invalid-feedback">Invalid email format</div>
            </div>
            <div class="mb-1">
                <label for="nameInput" class="form-label">{{ __('Name') }}</label>
                <input type="text" class="form-control" name="name" id="nameInput" maxlength="45" value="{{ old('name') }}" required />
                <div class="invalid-feedback">This field is required</div>
            </div>
            <div class="mb-1">
                <label for="surnameInput" class="form-label">{{ __('Surname') }}</label>
                <input type="text" class="form-control" name="surname" id="surnameInput" maxlength="65" value="{{ old('surname') }}" required />
                <div class="invalid-feedback">This field is required</div>
            </div>
            <div class="mb-1">
                <label for="passwordInput" class="form-label">{{ __('Password') }}</label>
                <input type="password" class="form-control" name="password" id="passwordInput" aria-describedby="passwordHelp" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required />
                <div id="passwordHelp" class="form-text">
                    Min. 8 characters, 1 lower, 1 upper, 1 digit, 1 special
                    character.
                </div>
                <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div class="mb-1">
                <label for="passwordConfirmationInput" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" class="form-control" name="password_confirmation" id="passwordConfirmationInput" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required />
                <div class="invalid-feedback">Invalid password format</div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary btn-md-full-width">
                    Register
                </button>
            </div>
        </form>
        @if ($errors->any())
            <hr />
            <div class="alert alert-danger text-center" role="alert">
                <ol class="list-group list-group-flush list-group-numbered">
                    @foreach ($errors->all() as $error)
                    <li class="list-group-item list-group-item-danger">
                        {{ $error }}
                    </li>
                    @endforeach
                </ol>
            </div>
        @endif
    </div>
</div> --}}
<h1 class="mt-4">Edit event</h1>
<div class="row">
  <div class="col-md-6">
    <form
      method="POST"
      action="{{ route('admin.editEvent', [$id]) }}"
      class="my-4 row gy-3 gx-0 needs-validation"
      id="createEventForm"
      enctype="multipart/form-data"
      novalidate
    >
      <div class="mb-1">
        <label for="emailInput" class="form-label">Name</label>
        <input
          type="email"
          class="form-control"
          id="essa"
          value="Lorem Ipsum"
          disabled
        />
        <div class="invalid-feedback">Invalid email format</div>
      </div>
      <div class="mb-5">
        <div id="descriptionInput">
          <p>Hello World!</p>
          <p>Some initial <strong>bold</strong> text</p>
          <p><br /></p>
        </div>
        <input type="hidden" id="descriptionContentInput" />
      </div>
      <div class="mb-1">
        <label for="exampleInputPassword1" class="form-label"
          >Category</label
        >
        <select
          class="form-select"
          aria-label="Default select example"
        >
          <option value="">Open this select menu</option>
          <option value="1">One</option>
          <option value="2" selected>Two</option>
          <option value="3">Three</option>
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
        <input
          type="hidden"
          id="tagsContentInput"
          value='["test1","test2","test3"]'
        />
        <div id="tagsList" class="d-flex gap-1 flex-wrap mt-3">
          <span class="badge bg-secondary tag-badge"
            >test1<i
              class="bi bi-x tag-delete"
              aria-hidden="true"
            ></i
            ><span class="visually-hidden">Delete tag</span></span
          >
          <span class="badge bg-secondary tag-badge"
            >test2<i
              class="bi bi-x tag-delete"
              aria-hidden="true"
            ></i
            ><span class="visually-hidden">Delete tag</span></span
          >
          <span class="badge bg-secondary tag-badge"
            >test3<i
              class="bi bi-x tag-delete"
              aria-hidden="true"
            ></i
            ><span class="visually-hidden">Delete tag</span></span
          >
        </div>
      </div>
      <div class="mb-1">
        <label for="emailInput" class="form-label">Street</label>
        <input
          type="email"
          class="form-control"
          name="email"
          id="essa"
          maxlength="65"
          value="ul. Półtusk 13b"
          required
        />
        <div class="invalid-feedback">Invalid email format</div>
      </div>
      <div class="mb-1">
        <label for="ice-cream-choice" class="form-label"
          >City</label
        >
        <input
          list="cityDatalist"
          class="form-control"
          id="cityInput"
          name="ice-cream-choice"
          placeholde="Type to create..."
          maxlength="45"
          minlength="3"
          aria-describedby="cityHelp"
          value="Władysławowo"
          required
        />
        <div id="cityHelp" class="form-text">
          Input min. 3 letters to get hint
        </div>
        <datalist id="cityDatalist">
          <option value="Chocolate"></option>
          <option value="Coconut"></option>
          <option value="Mint"></option>
          <option value="Strawberry"></option>
          <option value="Żanilla"></option>
        </datalist>
      </div>
      <div class="mb-1">
        <label for="emailInput" class="form-label"
          >Postal code</label
        >
        <input
          type="email"
          class="form-control"
          name="email"
          id="essa"
          maxlength="150"
          value="12-345"
          required
        />
        <div class="invalid-feedback">Invalid email format</div>
      </div>
      <div class="mb-1">
        <label for="emailInput" class="form-label"
          >Ticket price</label
        >
        <div class="input-group">
          <input
            type="number"
            name="ticketPrice"
            class="form-control"
            max="10000.0"
            min="1.0"
            step="0.01"
            aria-label="Price (in euro)"
            value="120.50"
            required
          />
          <span class="input-group-text">&euro;</span>
          <div class="invalid-feedback">Invalid email format</div>
        </div>
      </div>
      <div class="mb-1">
        <label for="emailInput" class="form-label"
          >Add tickets</label
        >
        <span class="badge rounded-pill bg-info text-dark"
          >Current tickets count: 320</span
        >
        <input
          type="number"
          class="form-control"
          name="email"
          id="essa"
          min="1"
          max="99999"
        />
        <div class="invalid-feedback">Min. 1 Max. 99999</div>
      </div>
      <div class="mb-1">
        <label for="birthdateInput" class="form-label"
          >Start date</label
        >
        <input
          type="datetime-local"
          class="form-control"
          name="birthdate"
          id="startDatetimeInput"
          required
        />
        <input
          type="hidden"
          name="startDatetime"
          id="startDatetimeContentInput"
          value="2023-06-02T13:40Z"
        />
        <div class="invalid-feedback">
          Start date cannot be in past
        </div>
      </div>
      <div class="mb-3">
        <label for="formFileMultiple" class="form-label"
          ><span class="fw-bold">Override</span> multiple files
          input example</label
        >
        <input
          class="form-control"
          type="file"
          name="images[]"
          id="imagesUploadInput"
          aria-describedby="imagesUploadHelp"
          accept="image/jpeg,image/jpg,image/png,image/gif"
          multiple
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
        <label for="formFile" class="form-label"
          ><span class="fw-bold">Override</span> video file input
          example</label
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
          name="rememberMe"
          class="form-check-input"
          id="rememberMeCheckbox"
          checked
        />
        <label class="form-check-label" for="rememberMeCheckbox"
          >Adult only</label
        >
      </div>
      <div class="mb-1 form-check">
        <input
          type="checkbox"
          name="rememberMe"
          class="form-check-input"
          id="rememberMe"
        />
        <label class="form-check-label" for="rememberMe"
          >Is draft</label
        >
      </div>
      <div>
        <button
          type="submit"
          name="submit"
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