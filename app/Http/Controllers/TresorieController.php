<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Factures;
use App\Models\Historique;
use App\Models\Tresorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TresorieController extends Controller
{


    // List all tresorie records
    public function index()
    {
        $tresorieRecords = Tresorie::with('facture')->get();
        return response()->json($tresorieRecords);
    }

    public function getTresoriePaginate(Request $request)
    {
        return Tresorie::with('facture')->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
    }

    // Store a new tresorie record
    public function store(Request $request)
    {
        try {


            $request->validate([
                'montant' => 'required|numeric',
                'type_paiement' => 'required|string',
                'date' => 'required|date',
                'numFacture' => 'nullable|exists:factures,ref',
                'date_cheque' => 'nullable|date',
                'paye' => 'required|boolean',
                'notes' => 'nullable|string',
            ]);
            $tresorie = Tresorie::create([
                'montant' => $request->montant,
                'type_paiement' => $request->type_paiement,
                'date' => $request->date,
                'numFacture' => $request->numFacture ?? null,
                'date_cheque' => $request->date_cheque ?? null,
                'paye' => $request->paye,
                'notes' => $request->notes,
            ]);

            Historique::create([
                'table' => 'Tresorie',
                'id_record' => $tresorie->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $tresorie->getAttributes(),
                'changed_at' => now(),
                'changed_by' => null,
            ]);

            $totalPayments = Tresorie::where('numFacture', $request->numFacture)->sum('montant');
            $facture = Factures::where('ref', $request->numFacture)->first();

            $remainingBalance = $facture->totalTTC - $totalPayments;
            if ($remainingBalance <= 0) {
                $remainingBalance = 0;
            }

            $credit = Credit::create([
                'client_id' => $facture->devis->client_id,
                'numFacture' => $facture->ref,
                'montant' => $remainingBalance,
                'date' => $request->date,
                'status' => $remainingBalance <= 0 ? 'paid' : 'partially_paid',
            ]);

            Historique::create([
                'table' => 'Credit',
                'id_record' => $credit->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $credit->getAttributes(),
                'changed_at' => now(),
                'changed_by' => null,
            ]);


            if ($remainingBalance <= 0) {
                $facture->status = 'Paid';
            } elseif ($remainingBalance < $facture->totalTTC) {
                $facture->status = 'Partially Paid';
            }

            $facture->montant_restant = $remainingBalance;
            $tresorie->save();
            $facture->save();

            return response()->json(['message' => 'Payment recorded successfully.', 'remaining_balance' => $remainingBalance]);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }


    // Show a specific tresorie record
    public function show($id)
    {
        $tresorie = Tresorie::with('facture')->find($id);

        if (!$tresorie) {
            return response()->json(['message' => 'Tresorie record not found.'], 404);
        }

        return response()->json($tresorie);
    }

    // Update a specific tresorie record
    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'montant' => 'required|numeric',
                'type_paiement' => 'required|string',
                'date' => 'required|date',
                'numFacture' => 'required|exists:factures,ref',
                'date_cheque' => 'nullable|date',
                'paye' => 'required|boolean',
                'notes' => 'nullable|string',
            ]);

            $tresorie = Tresorie::find($id);

            if (!$tresorie) {
                return response()->json(['message' => 'Tresorie record not found.'], 404);
            }


            $dataBefore = $tresorie->getOriginal();
            $tresorie->update($request->all());
            // $tresorie->save();


            Historique::create([
                'table' => 'Tresorie',
                'id_record' => $tresorie->id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $tresorie->getAttributes(),
                'changed_at' => now(),
                'changed_by' => null,
            ]);

            return response()->json(['message' => 'Tresorie record updated successfully.', 'tresorie' => $tresorie]);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    // Delete a specific tresorie record
    public function destroy($id)
    {
        try {

            $tresorie = Tresorie::find($id);

            if (!$tresorie) {
                return response()->json(['message' => 'Tresorie record not found.'], 404);
            }

            $dataBefore = $tresorie;
            $tresorie->delete();
            Historique::create([
                'table' => 'Tresorie',
                'id_record' => $dataBefore->id,
                'action' => 'DELETE',
                'data_before' => $dataBefore,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' => null,
            ]);


            return response()->json(['message' => 'Tresorie record deleted successfully.']);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    // Autocomplete facture IDs
    public function autocompleteFacture(Request $request)
    {
        $search = $request->get('search', '');

        $factures = Factures::where('ref', 'LIKE', '%' . $search . '%')
            ->take(10)
            ->get();

        Log::info($factures);

        return response()->json($factures);
    }


    public function search(string $searchQuery)
    {

        // $tresories = Tresorie::where('numFacture', 'LIKE', '%' . $searchQuery . '%')->paginate(config('global.pagination.perPage'));

        $tresories = Tresorie::query()
            ->where('numFacture', 'LIKE', '%' . $searchQuery . '%')
            ->orWhereHas('facture.devis.client', function ($query) use ($searchQuery) {
                $query->where('nom', 'LIKE', '%' . $searchQuery . '%')->orWhere('prenom', 'LIKE', '%' . $searchQuery . '%');
            })
            ->paginate(config('global.pagination.perPage'));
        return response()->json($tresories);
    }

    public function getFacturePaiements(string $numFacture)
    {
        $totalPayments = Tresorie::where('numFacture', $numFacture)->sum('montant');
        $facture = Factures::with('devis')->where('ref', $numFacture)->first();

        $remainingBalance = $facture->devis->totalTTC - $totalPayments;
        if ($remainingBalance <= 0) {
            $remainingBalance = 0;
        }

        return response()->json(['remaining' => $remainingBalance, 'paid' => $totalPayments]);
    }
}
