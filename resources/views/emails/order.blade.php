@component('mail::message')
# Order

Thank you for purchase. Your tickets are attached to this mail.

@component('mail::table')
| #             | Name         | Price    |
|:------------- |:------------ |:-------- |
@foreach ($orders as $order)
| {{ $order->id_event }} | {{ $order->event_name }} | {{ $order->ticket_price }}€ |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('user-tickets')])
View Your Tickets
@endcomponent

Have a nice event,<br />
Team Ticketi
***
This message is auto-generated. Please do not respond!
@endcomponent