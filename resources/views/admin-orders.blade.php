@extends('layouts.ticketi-admin')

@section('content')
<h1 class="mt-4">Orders</h1>
<div class="row">
    <div class="col">
        <div class="card my-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Orders
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
    </div>
</div>
@endsection