@extends('layouts.ticketi')

@section('title', 'Frequently Asked Questions')
@section('description', 'Frequently Asked Questions to Ticketi service')

@section('content')
<div class="container px-5 mb-5">
    @include('shared.alerts')
    <div class="row mb-5">
        <h3 class="fw-bolder">@yield('title')</h3>
        <p class="lead fw-normal text-muted mb-0">How can we help you?</p>
    </div>
    <div class="row gx-5">
        <div class="col-xl-8">
            <!-- FAQ Accordion 1-->
            <div class="accordion mb-5" id="accordionExample">
                <div class="accordion-item">
                    <h3 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Jak wysyłane są bilety?
                        </button>
                    </h3>
                    <div class="accordion-collapse collapse show" id="collapseOne" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <strong>Tickets are sent after purchase to your email as a .pdf
                                files.</strong>
                            You can find them also in
                            <a href="user-tickets.html">user profile</a>.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            How the ticker ownership is verified?
                        </button>
                    </h3>
                    <div class="accordion-collapse collapse" id="collapseTwo" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Each ticket has it's own unique QR code identyfing the
                            owner.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h3 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            How can I make a return?
                        </button>
                    </h3>
                    <div class="accordion-collapse collapse" id="collapseThree" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Use the contact form in
                            <a href="user-contact.html">user profile</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 bg-light">
                <div class="card-body p-4 py-lg-5">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <div class="h6 fw-bolder">Have more questions?</div>
                            <p class="text-muted mb-4">
                                Use contact form
                                <br />
                                <a href="user-contact.html" class="btn btn-outline-primary">Contact form</a>
                            </p>
                            <div class="h6 fw-bolder">Follow us</div>
                            <a class="fs-5 px-2 link-dark" href="#!"><i class="bi-twitter"></i></a>
                            <a class="fs-5 px-2 link-dark" href="#!"><i class="bi-facebook"></i></a>
                            <a class="fs-5 px-2 link-dark" href="#!"><i class="bi-linkedin"></i></a>
                            <a class="fs-5 px-2 link-dark" href="#!"><i class="bi-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection