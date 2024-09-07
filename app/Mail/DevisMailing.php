<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Number;
use Ramsey\Uuid\Type\Decimal;
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

    public static function calculateTotal($promo=false, $devis)
    {
        $totalHT = 0;
        
        foreach ($devis->produits as $produit) {
            $totalHT += self::calculateTotalProd($promo ? true : false, $produit, $produit->pivot->qte);
        }
        $totalHT += $devis->totalFraisLivraison;
        $totalHT += $devis->totalServices;
        $totalTTC = $totalHT;

        foreach ($devis->taxes as $tax) {
            $totalTTC += $totalHT * ($tax['rate'] / 100);
        }


        return (object) ['totalHT' => $totalHT, 'totalTTC' => $totalTTC];
    }
    public static function PrixGrosOrVente($produit, $qte) {
        Log::info($produit);
        return $produit['qteMinGros'] < $qte ? $produit['prixGros'] : $produit['prixVente'];
    }
      
    public static function calculateTotalProd($promo=false, $produit, $quantity)
    {
        $total = $quantity * self::PrixGrosOrVente($produit,$quantity);;

        if ($promo && $produit->promo) {
            $total -= $total * ($produit->promo / 100);
        }

        return $total;
    }

    public function returnImg($image)
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
    public static function calculateValue($number, $rate): float {
        $rateInDecimal = $rate / 100;
        return (float) number_format($number * $rateInDecimal, 2);
    }
    public static function productsWithPromo($devis) {
        $productsArray = $devis->produits->toArray();

        return array_filter($productsArray, function($product) {
            return isset($product['promo']) && $product['promo'];
        });
    }


    public function build()
    {
        // $totalAvecPromoSansTva = null;
        // if ($this->devis->produits !== null) {
        //     foreach ($this->devis->produits as $produit) {
             
        //         $totalAvecPromoSansTva += $this->calculateTotalProd($produit, $produit->pivot->qte);
        //     }
        // }


        // Log::info( $this->productsWithPromo());

        $taxes= [
            [
                'name' => 'Droit Timbre',
                'rate' => null,
            ],
            [
                'name' => 'TVA',
                'rate' => $this->parameters['tva'],
            ],
            [
                'name' => 'Fodec',
                'rate' => $this->parameters['fodec'],
            ]];
                
            $devisTaxesArray = $this->devis->taxes->toArray();
            $this->devis->taxes = array_merge($devisTaxesArray, $taxes);
            
        return $this->view('Email.devis2')
            ->subject('Votre Devis')->with([
                'devis' => $this->devis,
                'parameters' => $this->parameters,
                // 'totalAvecPromoTVAHT' => $this->calculateTotal()->totalHT,
                // 'totalAvecPromoTVATTC' => $this->calculateTotal()->totalTTC,
                // 'totalAvecPromoSansTva' => $totalAvecPromoSansTva,
                'totalLettres' => $this->convertToWords($this->devis->totalTTC),
                'fraisLivraison' => $this->devis->totalFraisLivraison,
                "productsWithPromo" => $this->productsWithPromo($this->devis),

            ]);
    }
}
