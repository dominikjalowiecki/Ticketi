<div id="alertsContainer" class="row my-4 pt-3">
    @if (!empty($status))
        <div class="col-lg-4 px-3 pb-3">
            <div class="alert alert-success text-center mb-0" role="alert">
                {{ $status }}
            </div>
        </div>
    @endif
    @if (session()->has('status'))
        <div class="col-lg-4 px-3 pb-3">
            <div class="alert alert-success text-center mb-0" role="alert">
                {{ session('status') }}
            </div>
        </div>
    @endif
    @if (!empty($info))
        <div class="col-lg-4 px-3 pb-3">
            <div class="alert alert-warning text-center mb-0" role="alert">
                {{ $info }}
            </div>
        </div>
    @endif
    @if (session()->has('info'))
        @if (is_array($info = session('info')))
            @foreach ($info as $msg)
                <div class="col-lg-4 px-3 pb-3">
                    <div class="alert alert-warning text-center mb-0" role="alert">
                        {{ $msg }}
                    </div>
                </div>
            @endforeach
        @else
        <div class="col-lg-4 px-3 pb-3">
            <div class="alert alert-warning text-center mb-0" role="alert">
                {{ $info }}
            </div>
        </div>
        @endif
    @endif
</div>