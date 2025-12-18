<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class RecoverPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address(
                env('MAIL_FROM_ADDRESS'),
                env('MAIL_FROM_NAME')
            ),
            subject: 'Recuperação de Senha'
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.recover_password'
        );
    }
}

