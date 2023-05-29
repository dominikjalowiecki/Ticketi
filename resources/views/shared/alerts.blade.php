<div id="alertsContainer" class="row my-4 pt-3">
    @if (session()->has('status'))
    <div class="col-lg-4 px-3 pb-3">
        <div class="alert alert-success text-center mb-0" role="alert">
            {{ session('status') }}
        </div>
    </div>
    @endif
    @if (session()->has('info'))
    <div class="col-lg-4 px-3 pb-3">
        <div class="alert alert-warning text-center mb-0" role="alert">
            {{ session('info') }}
        </div>
    </div>
    @endif
</div>