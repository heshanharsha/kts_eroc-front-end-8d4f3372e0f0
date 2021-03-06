<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $activationLink;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($link)
    {
        $this->activationLink = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('prabuddha@imperium.lk')
                ->view('email.verification')
                ->with([
                    'activationLink' => $this->activationLink
                ]);
    }
}
