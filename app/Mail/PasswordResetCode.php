<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetCode extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $code;
    public function __construct($code)
    {
        //
                $this->code = $code;

    }

    // public function build()
    // {
    //     return $this->subject('Password Reset Code')
    //                 ->view('emails.password_reset')
    //                 ->with(['code' => $this->code]);
    // }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // return new Content(
        //     view: 'view.name',
        // );
         return new Content(
            view: 'emails.passwordReset',
            with: [
                'code' => $this->code,
            ],
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
