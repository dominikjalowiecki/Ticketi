<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactResponseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contactMailSubject;
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $content)
    {
        $this->contactMailSubject = $subject;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('emails.contact_response')
            ->subject('Ticketi | Thank you for contact!');
    }
}
