<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StopLossMail extends Mailable
{
    use Queueable, SerializesModels;
    public string $share;
    public int $value;
    /**
     * Create a new message instance.
     */
    public function __construct($share, $value)
    {
        $this->share = $share;
        $this->value = $value;
    }

    public function build()
    {
        return $this->subject('Stop-Loss Benachrichtigung')
            ->view('emails.stop_loss')
            ->with([
                'share' => $this->share,
                'value' => $this->value,
            ]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stop Loss Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.stop_loss',
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
