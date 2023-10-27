<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TalaqMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $partner;
    public $talaq;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $femalePartner,$talaq)
    {
        $this->user = $user;
        $this->partner = $femalePartner;
        $this->talaq = $talaq;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Talaq Email',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.talaq-email',
            with:['user'=>$this->user,'partner'=>$this->partner,'talaq'=>$this->talaq],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
