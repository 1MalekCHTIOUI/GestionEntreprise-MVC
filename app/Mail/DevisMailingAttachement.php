<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DevisMailingAttachement extends Mailable
{
    use Queueable, SerializesModels;
    public $devis;
    public $client;
    public $parameters;
    public $pdfContent;
    /**
     * Create a new message instance.
     */
    public function __construct($client, $pdfContent)
    {
        // $this->devis = $devis;
        // $this->parameters = $parameters;
        $this->client = $client;
        $this->pdfContent = $pdfContent;
    }



    public function build()
    {
        return $this->view('Email.devisPdf')
            ->attachData($this->pdfContent, "devis {$this->client->nom} {$this->client->prenom}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
