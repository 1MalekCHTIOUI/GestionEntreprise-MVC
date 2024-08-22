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
    public $parameters;
    public $pdfContent;
    /**
     * Create a new message instance.
     */
    public function __construct($pdfContent)
    {
        // $this->devis = $devis;
        // $this->parameters = $parameters;
        $this->pdfContent = $pdfContent;
    }



    public function build()
    {
        return $this->view('Email.devisPdf')
            ->attachData($this->pdfContent, 'tableToPdf.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
