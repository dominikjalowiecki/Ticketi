@component('mail::message')
# Contact Form

@component('mail::panel')
*{{ $content }}*
@endcomponent

{{ $user->name() }},<br />
{{ $user->email }}

***
Sended from {{ config('app.name') }} contact form.
@endcomponent