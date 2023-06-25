@component('mail::message')
# Contact Form Response

Thank you for contact! We will respond soon.

Team Ticketi

@component('mail::panel')
* Subject: *"{{ $contactMailSubject }}"*<br />
* Your message: *"{{ $content }}"*
@endcomponent

***
This message is auto-generated. Please do not respond!
@endcomponent