<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <title>{{ $order->event_name }} Ticket</title>
    <style>
        * {
            text-align: center;
            font-family: "DejaVu Sans" !important;
        }
    </style>
</head>
<body>
    <a href="{{ config('app.url') }}">
        <h1>Ticketi</h1>
    </a>
    <h3>{{ $order->user_name }}</h3>
    <h5>{{ $order->birthdate }}</h5>
    <img src="data:image/svg+xml;base64,'{{ base64_encode( QrCode::size(300)->generate( URL::signedRoute('ticket', [$order->id_order]) ) ) }}'">
    <p><b>Event: {{ $order->id_event . '. ' . $order->event_name . ' ' . $order->start_datetime}} UTC</b></p>
    <small>Bought {{ $order->created_datetime }} UTC</small>
    <p>Dominik Jalowiecki © 2023</p>
</body>
</html>