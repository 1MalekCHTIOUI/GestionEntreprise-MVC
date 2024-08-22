<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Rmunate\Utilities\SpellNumber;

class DevisMailing extends Mailable
{
    use Queueable, SerializesModels;
    public $devis;
    public $parameters;
    /**
     * Create a new message instance.
     */
    public function __construct($devis, $parameters)
    {
        $this->devis = $devis;
        $this->parameters = $parameters;
    }

    private function calculateTotalAvecPromoTVA($devis)
    {
        $totalHT = 0;
        foreach ($devis->produits as $produit) {
            $totalHT += $this->calculateTotalProduitAvecPromoSansTVA($produit, $produit->pivot->qte);
        }
        $totalTTC = $totalHT;
        foreach ($devis->taxes as $tax) {
            $totalTTC += $totalHT * ($tax->rate / 100);
        }


        return (object) ['totalHT' => $totalHT, 'totalTTC' => $totalTTC];
    }

    private function calculateTotalProduitAvecPromoSansTVA($produit, $quantity)
    {
        $total = $quantity >= $produit->qteMinGros ? $quantity * $produit->prixGros : $quantity * $produit->prixVente;

        if ($produit->promo) {
            $total -= $total * ($produit->promo / 100);
        }

        return $total;
    }

    private function returnImg($image)
    {
        return asset('storage/assets/images/parameters/' . $image);
    }


    public function convertToWords($value)
    {
        return SpellNumber::value($value)->locale('fr')->toLetters();
    }

    public function getFraisLivraison()
    {
        $fraisLivraison = 0;

        foreach ($this->devis->produits as $prod) {
            $fraisLivraison += $prod->fraisTransport;
        }

        return $fraisLivraison;
    }

    public function build()
    {
        $totalAvecPromoSansTva = null;
        if ($this->devis->produits !== null) {
            foreach ($this->devis->produits as $produit) {
                Log::info($totalAvecPromoSansTva);
                $totalAvecPromoSansTva += $this->calculateTotalProduitAvecPromoSansTVA($produit, $produit->pivot->qte);
            }
        }

        return $this->view('Email.devis')
            ->subject('Votre Devis')->with([
                'devis' => $this->devis,
                'parameters' => $this->parameters,
                'totalAvecPromoTVAHT' => $this->calculateTotalAvecPromoTVA($this->devis)->totalHT,
                'totalAvecPromoTVATTC' => $this->calculateTotalAvecPromoTVA($this->devis)->totalTTC,
                'totalAvecPromoSansTva' => $totalAvecPromoSansTva,
                'totalLettres' => $this->convertToWords($this->calculateTotalAvecPromoTVA($this->devis)->totalTTC),
                'fraisLivraison' => $this->getFraisLivraison(),

            ]);
    }
}
