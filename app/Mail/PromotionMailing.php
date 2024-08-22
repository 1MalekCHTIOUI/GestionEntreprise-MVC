<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionMailing extends Mailable
{
    use Queueable, SerializesModels;

    public $promotion;
    public $client;


    /**
     * Create a new message instance.
     */
    public function __construct($promotion, $client)
    {
        $this->promotion = $promotion;
        $this->client = $client;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Promotion Mailing',
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

    /**
     * Build the message.
     */

    public function build()
    {
        return $this->view('Email.promotion')
            ->subject('Your promotions')->with([
                'promotion' => $this->promotion,
                'client' => $this->client,

            ]);
    }
}
