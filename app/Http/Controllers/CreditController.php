<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Factures;
use Illuminate\Http\Request;

class CreditController extends Controller
{

    public function index()
    {
        $credits = Credit::with('facture')->get();
        return response()->json($credits);
    }

    public function getCreditPaginate(Request $request)
    {
        return Credit::with('facture', 'client')->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
    }



    public function searchByClientName($clientName)
    {

        $credits = Credit::with('facture', 'client')->whereHas('facture.devis.client', function ($query) use ($clientName) {
            $query->where('nom', 'LIKE', '%' . $clientName . '%')->orwhere('prenom', 'LIKE', '%' . $clientName . '%');
        })->paginate(config('global.pagination.perPage'));;

        return response()->json($credits);
    }

    public function searchByFactureRef($factureRef)
    {
        $credits = Credit::with('facture', 'client')->where('numFacture', 'LIKE', '%' . $factureRef . '%')->paginate(config('global.pagination.perPage'));
        return response()->json($credits);
    }
}
