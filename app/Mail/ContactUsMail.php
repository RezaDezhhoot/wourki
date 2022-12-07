<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $message;

    /**
     * Create a new message instance.
     *
     * @param $email
     * @param $message
     */
    public function __construct($email, $message)
    {
        $this->email = $email;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('ایمیل تماس با وورکی')
            ->to('wourki@yahoo.com')
            ->markdown('frontend.emails.contactUs')
            ->with('message' , $this->message);
    }
}
