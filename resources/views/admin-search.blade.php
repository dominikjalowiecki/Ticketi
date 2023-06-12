@extends('layouts.ticketi-admin')

@section('content')
<h1 class="mt-4">Searching for: {{ Request::get('s') }}</h1>
<div class="row">
    <div class="col">
        @if (count($events) > 0)
        <div class="card my-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Search events
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Start date</th>
                            <th>Last update</th>
                            <th>Ticket price</th>
                            <th>Sold</th>
                            <th>Is adult only</th>
                            <th>Is draft</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Start date</th>
                            <th>Last update</th>
                            <th>Ticket price</th>
                            <th>Sold</th>
                            <th>Is adult only</th>
                            <th>Is draft</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->id_event }}</td>
                            <td>
                                <a href="{{ route('event.page', [$event->url]) }}">{{ $event->name }}</a>
                                <a href="{{ route('admin.editEvent', [$event->id_event]) }}" class="badge bg-primary link-light">Edit</a>
                            </td>
                            <td>{{ $event->category_name }}</td>
                            <td>
                                <span class="time-component">{{ $event->start_datetime }} UTC</span>
                            </td>
                            <td>
                                <span class="time-component">{{ $event->created_datetime }} UTC</span>
                            </td>
                            <td>{{ $event->ticket_price }}&euro;</td>
                            <td>{{ $event->ticket_count }}</td>
                            <td>
                                @if ($event->is_adult_only)
                                    <span class="badge bg-danger">True</span>
                                @else
                                    <span class="badge bg-success">False</span>
                                @endif
                            </td>
                            <td>
                                @if ($event->is_draft)
                                    <span class="badge bg-danger">True</span>
                                @else
                                    <span class="badge bg-success">False</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <p class="h3 text-center">Items not found...</p>
        @endif
    </div>
</div>
@endsection