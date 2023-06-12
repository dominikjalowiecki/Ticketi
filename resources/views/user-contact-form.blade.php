@extends('layouts.user-profile')

@section('user-profile-content')
<form
    method="POST"
    action="{{ route('user-contact-form') }}"
    class="row flex-column gy-3 gx-0 needs-validation"
    novalidate
    >
    @csrf
    <div class="mb-1 col-lg-4">
        <label for="subjectSelect" class="form-label"
        >Subject</label
        >
        <select
        class="form-select"
        id="subjectSelect"
        name="subject"
        aria-label="Select mail subject"
        required
        value="{{ old('subject') }}"
        >
        <option value="" selected>Open this select menu</option>
        <option value="1">Information</option>
        <option value="2">Tickets returns</option>
        <option value="3">Technical support</option>
        </select>
        <div class="invalid-feedback">Subject is required</div>
    </div>
    <div class="mb-1 col-lg-4">
        <label for="email" class="form-label"
        >Email</label
        >
        <input
        type="text"
        id="email"
        class="form-control"
        placeholder="{{ Auth::user()->email }}"
        disabled
        />
    </div>
    <div class="mb-1 col-lg-8">
        <label for="contentTextarea" class="form-label"
        >Content</label
        >
        <textarea
        class="form-control"
        id="contentTextarea"
        name="content"
        rows="6"
        maxlength="300"
        placeholder="Message content..."
        value="{{ old('content') }}"
        required
        ></textarea>
        <div class="invalid-feedback">Content is required</div>
    </div>
    <div>
        <button
        type="submit"
        class="btn btn-primary btn-md-full-width"
        >
        Send
        </button>
    </div>
    </form>
    <div class="row">
        <div class="col-lg-6">
            @include('shared.validation-errors')
        </div>
    </div>
@endsection