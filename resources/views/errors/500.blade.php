@extends('layouts.ticketi')

@section('title', 'Internal Server Error')

@section('content')
<div class="row">
    <div class="col text-center">
        <h3 class="display-3 fw-bold">500</h3>
        <p>Internal server error.</p>
        <small>{{ $exception->getMessage() }}</small>
    </div>
</div>
@endsection