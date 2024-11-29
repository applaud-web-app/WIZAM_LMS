<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $data;
    public function __construct($param)
    {
        $this->data = $param;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: env('MAIL_FROM_NAME').' : Forgot Password',
        );
    }
    
    public function content(): Content
    {
        return new Content(
            view: 'mail.forgot-password',
            with: [
                'encryptUrl' => $this->data, // Pass data as an associative array
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
