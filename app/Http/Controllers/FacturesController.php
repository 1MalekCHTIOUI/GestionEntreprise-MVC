<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Factures;
use Illuminate\Http\Request;

class FacturesController extends Controller
{
    //
    public function createFacture(Request $request, $devisId)
    {
        $devis = Devis::with('produits')->findOrFail($devisId);
        $request->validate([
            'ref' => 'required',
            'totalTTC' => 'required',
            'totalHT' => 'required',
            'date' => 'required',
        ]);
        $total = 0;
        foreach ($devis->produits as $produit) {
            $quantity = $produit->pivot->qte;
            if ($produit->qte < $quantity)
                return response()->json(['message' => 'Quantité insuffisante pour le produit ' . $produit->titre], 400);

            $price = ($quantity >= $produit->qteMinGros) ? $produit->prixGros : $produit->prixVente;
            $productTotal = $quantity * $price;

            if ($produit->promo) {
                $productTotal -= $productTotal * ($produit->promo / 100);
            }

            $total += $productTotal;
        }

        $facture = Factures::create([
            'idDevis' => $devis->id,
            'ref' => $request->ref,
            'totalTTC' => $request->totalTTC,
            'totalHT' => $request->totalHT,
            'date' => $request->date,
        ]);

        foreach ($devis->produits as $produit) {
            $produit->decrement('qte', $produit->pivot->qte);
            foreach ($produit->accessoires as $accessoire) {
                $accessoire->decrement('qte', $produit->pivot->qte * $accessoire->pivot->qte);
            }
        }
        try {
            $devis->update(['status' => true]);
            $devis->save();
        } catch (\Exception $th) {

            return response()->json(['message' => 'Error updating devis status'], 400);
        }


        return response()->json(['message' => 'Facture created successfully', 'facture' => $facture], 201);
    }

    public function getFacturesPaginate()
    {
        $factures = Factures::with(['devis.client', 'devis.produits.accessoires'])->paginate(10);
        return response()->json($factures);
    }
    public function getFactures()
    {
        $factures = Factures::with('devis.client')->get();
        return response()->json($factures);
    }

    public function getFacture($id)
    {
        $facture = Factures::with('client')->findOrFail($id);
        return response()->json($facture);
    }

    public function getFactureByDevis($idDevis)
    {
        $facture = Factures::with(['devis.produits.accessoires', 'devis.client', 'devis.client.state', 'devis.client.state.country'])->where('idDevis', $idDevis)->first();
        return response()->json($facture);
    }
}
