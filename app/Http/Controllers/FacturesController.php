<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Factures;
use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FacturesController extends Controller
{
    //
    public function createFacture(Request $request, $devisId)
    {
        try {
            $devis = Devis::with('produits')->findOrFail($devisId);
            $request->validate([
                'totalTTC' => 'required',
                'totalHT' => 'required',
                'date' => 'required',
                'status' => 'nullable',

            ]);

            if (!property_exists($request, 'status') || !isset($request->status)) {
                $request->merge(['status' => 'Not Paid']);
            }

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

            $facture = new Factures();
            $facture->idDevis = $devis->id;
            $facture->totalTTC = $request->totalTTC;
            $facture->totalHT = $request->totalHT;
            $facture->date = $request->date;
            $facture->status = $request->status;
            $facture->montant_restant = $request->totalTTC;
            $facture->save();

            $facture->ref = $facture->generateInvoiceNumber();
            $facture->save();

            Historique::create([
                'table' => 'Facture',
                'id_record' => $facture->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $facture->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            foreach ($devis->produits as $produit) {
                $produit->decrement('qte', $produit->pivot->qte);
                foreach ($produit->accessoires as $accessoire) {
                    $accessoire->decrement('qte', $produit->pivot->qte * $accessoire->pivot->qte);
                }
            }
            try {
                $devis->update(['status' => Devis::STATUS_DONE]);
                $devis->save();
            } catch (\Exception $th) {

                return response()->json(['message' => 'Error updating devis status'], 400);
            }


            return response()->json(['message' => 'Facture created successfully', 'facture' => $facture], 201);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function getFacturesPaginate()
    {
        $factures = Factures::with(['devis.client', 'devis.produits.accessoires'])->paginate(config('global.pagination.perPage'));
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
        $facture = Factures::with([
            'devis.produits.accessoires', 'devis.client.state', 
            'devis.client.state.country', 'devis.taxes','devis.items'])->where('idDevis', $idDevis)->first();
        return response()->json($facture);
    }

    public function getRemainingBalance($numFacture)
    {
        $fac = Factures::where('ref', $numFacture)->first();
        $totalPayments = $fac->remainingBalance();
        return response()->json($totalPayments);
    }
}
