@extends('layouts.ticketi-admin')

@section('content')
<h1 class="mt-4">Dashboard</h1>
<div class="row mt-4">
    <div class="col-xl-3 col-md-6">
    <div class="card bg-primary text-white mb-4">
        <div class="card-body">Total sold</div>
        <div
        class="card-footer d-flex align-items-center justify-content-between"
        >
        {{ $ordersStats->orders_count }} tickets
        </div>
    </div>
    </div>
    <div class="col-xl-3 col-md-6">
    <div class="card bg-warning text-white mb-4">
        <div class="card-body">Events available</div>
        <div
        class="card-footer d-flex align-items-center justify-content-between"
        >
        {{ $availableEventsCount }} events
        </div>
    </div>
    </div>
    <div class="col-xl-3 col-md-6">
    <div class="card bg-success text-white mb-4">
        <div class="card-body">Total revenue</div>
        <div
        class="card-footer d-flex align-items-center justify-content-between"
        >
        {{ $ordersStats->total_revenue }}&euro;
        </div>
    </div>
    </div>
    <div class="col-xl-3 col-md-6">
    <div class="card border-0 mb-4 d-none d-sm-block">
        <img
        src="{{ asset('img/undraw-analysis-illustration.svg') }}"
        class="img-fluid"
        loading="lazy"
        />
    </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
        <div class="row">
            <div class="col-sm-6 my-3">
            <i class="fas fa-chart-area me-1"></i>
            Revenue Chart
            </div>
            <div class="col-sm-6">
            <select id="revenueChartTypeSelect" class="form-select form-select-sm mt-3">
                <option value="daily" selected>Daily</option>
                <option value="monthly">Monthly</option>
                <option value="annual">Annual</option>
            </select>
            </div>
        </div>
        </div>
        <div class="card-body">
        <canvas id="revenueChart" data-url="{{ route('stats.revenue', ['']) }}" width="100%" height="40"></canvas>
        </div>
    </div>
    </div>
    <div class="col-xl-6">
    <div class="card mb-4">
        <div class="card-header">
        <div class="row">
            <div class="col-sm-6 my-3">
                <i class="fas fa-chart-bar me-1"></i>
                Solds By Category Chart
            </div>
        </div>
        </div>
        <div class="card-body">
        <canvas id="categoriesChart" data-url="{{ route('stats.categories') }}" width="100%" height="40"></canvas>
        </div>
    </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">
    <i class="fas fa-table me-1"></i>
    25 recent orders
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
        <thead>
            <tr>
            <th>#</th>
            <th>Event name</th>
            <th>Person</th>
            <th>Email</th>
            <th>Ticket price</th>
            <th>Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
            <th>#</th>
            <th>Event name</th>
            <th>Person</th>
            <th>Email</th>
            <th>Ticket price</th>
            <th>Date</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id_event }}</td>
                <td>
                <a href="{{ route('event.page', [$order->event_url]) }}">{{ $order->event_name }}</a>
                </td>
                <td>{{ $order->user_name }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ $order->ticket_price }}&euro;</td>
                <td>
                <span class="time-component"
                    >{{ $order->created_datetime }} UTC</span
                >
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ mix('/js/build/revenue-chart.js') }}"></script>
<script src="{{ mix('/js/build/categories-chart.js') }}"></script>
@endpush