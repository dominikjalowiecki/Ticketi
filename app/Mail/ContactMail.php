<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(integer $idOrder)
    {
        $this->idOrder = $idOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // <div>
        //     Price: {{ $order->price }}
        // </div>
        // return $this
        //     ->from(config('mail.from.address'), 'Contact Form Ticketi')
        //     ->view('emails.contact')
        //     ->subject('Test 123')
        //     ->with([
        //         'orderName' => $this->order->name,
        //         'orderPrice' => $this->order->price,
        //     ])
        //     ->attach('/path/to/file', [
        //         'as' => 'name.pdf',
        //         'mime' => 'application/pdf',
        //     ])
        //     ->text('emails.contact_plain');

        // Mail::send('emails.welcome', $data, function ($message) {
        //     $message->to('foo@example.com', 'John Smith')
        //         ->replyTo('reply@example.com', 'Reply Guy')
        //         ->subject('Welcome!');
        // });
    }
}
