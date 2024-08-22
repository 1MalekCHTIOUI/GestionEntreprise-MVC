<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Devis;
use App\Models\Factures;
use App\Models\Produits;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class StatistiquesController extends Controller
{
    public function getDevisByDateRange(Request $request)
    {
        // Validate the date range parameters
        $request->validate([
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y'
        ]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');

        // Query to get devis within the date range
        $devis = Devis::whereBetween('devis.date', [$startDate, $endDate])
            ->join('devis_produits', 'devis.id', '=', 'devis_produits.idDevis')
            ->join('produits', 'devis_produits.idProduit', '=', 'produits.id')
            ->select('devis.*', DB::raw('SUM(produits.prixVente * devis_produits.qte) as total_amount'))
            ->groupBy('devis.id')
            ->get();

        $totalDevis = $devis->sum('total_amount');
        return response()->json([
            'devis' => $devis,
            'totalDevis' => $totalDevis
        ]);
    }

    public function getDevisComparison(Request $request)
    {

        $request->validate([
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y'
        ]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');


        $devis = Devis::whereBetween('date', [$startDate, $endDate])->get();

        $factureDevisIds = Factures::whereBetween('date', [$startDate, $endDate])
            ->pluck('idDevis')
            ->toArray();

        $devisSoldCount = $devis->filter(function ($devis) use ($factureDevisIds) {
            return in_array($devis->id, $factureDevisIds);
        })->count();

        $devisNotSoldCount = $devis->count() - $devisSoldCount;

        $devisSold = $devis->filter(function ($devis) use ($factureDevisIds) {
            return in_array($devis->id, $factureDevisIds);
        });
        $devisNotSold = $devis->filter(function ($devis) use ($factureDevisIds) {
            return !in_array($devis->id, $factureDevisIds);
        });

        return response()->json([
            'devisSold' => $devisSold,
            'devisNotSold' => $devisNotSold
        ]);

        return response()->json($devisSold);
    }


    //create a function that returns the most successful products
    public function getMostSuccessfulProducts(Request $request)
    {
        // Validate the date range parameters
        $request->validate([
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y'
        ]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');



        // Query to get the most successful products
        $successfulProductsQuery = DB::table('factures')
            ->join('devis', 'factures.idDevis', '=', 'devis.id')
            ->join('devis_produits', 'devis.id', '=', 'devis_produits.idDevis')
            ->join('produits', 'devis_produits.idProduit', '=', 'produits.id')
            ->whereBetween('factures.date', [$startDate, $endDate])
            ->where('factures.status', 'paid')
            ->select('produits.id', 'produits.titre', 'produits.ref', DB::raw('SUM(devis_produits.qte) as total_quantity_sold'), DB::raw('SUM(produits.prixVente * devis_produits.qte) as total_price'))
            ->groupBy('produits.id', 'produits.titre', 'produits.ref')
            ->orderByDesc('total_quantity_sold');


        Log::info('SQL Query: ' . $successfulProductsQuery->toSql());
        $successfulProducts = $successfulProductsQuery->get();

        Log::info('Successful Products: ' . $successfulProducts);

        return response()->json($successfulProducts);
    }

    public function getProfitsReport(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');


        // Fetch factures within the specified date range
        $factures = Factures::whereBetween('created_at', [$startDate, $endDate])->get();


        $totalRevenueHT = $factures->sum(function ($facture) {
            return $facture->devis->produits->sum(function ($item) {
                if ($item->qteMinGros < $item->qte) {
                    return $item->pivot->qte * $item->prixGros;
                } else {
                    return $item->pivot->qte * $item->prixVente;
                }
            });
        });

        // Get total revenue TTC
        $totalRevenueTTC = $factures->sum('totalTTC');

        // Get charges within the date range
        $charges = Charge::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalCharges = $charges->sum('valeur');

        // Calculate total profit
        $totalProfit = $totalRevenueHT - $totalCharges;

        return response()->json([
            'totalRevenueHT' => $totalRevenueHT,
            'totalRevenueTTC' => $totalRevenueTTC,
            'totalCharges' => $totalCharges,
            'totalProfit' => $totalProfit,
        ]);
    }




    public function getProfits(Request $request)
    {


        $fac = Factures::where('status', 'paid')->orderBy('totalTTC', 'desc')->get();


        return response()->json($fac);
    }

    //Create a function that returns the most successful clients
    public function getMostSuccessfulClients(Request $request)
    {
        // Validate the date range parameters
        $request->validate([
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y'
        ]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');

        $mostSuccessfulClientsQuery = DB::table('factures')
            ->join('devis', 'factures.idDevis', '=', 'devis.id')
            ->join('clients', 'devis.client_id', '=', 'clients.id')
            ->whereBetween('factures.date', [$startDate, $endDate])
            ->select('clients.id', 'clients.nom', 'clients.prenom', DB::raw('COUNT(factures.id) as total_devis_sold'), DB::raw('SUM(factures.totalTTC) as total_achats'))
            ->groupBy('clients.id', 'clients.nom', 'clients.prenom')
            ->orderByDesc('total_achats');

        $mostSuccessfulClients = $mostSuccessfulClientsQuery->get();

        return response()->json($mostSuccessfulClients);
    }


    public function getChargesReport(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');

        $charges = Charge::whereBetween('created_at', [$startDate, $endDate])
            ->select('id', 'titre', 'valeur')
            ->groupBy('id', 'titre', 'valeur')
            ->selectRaw('SUM(valeur) as total_valeur')
            ->get();

        $totalValeur = $charges->sum('total_valeur');

        return response()->json([
            'charges' => $charges,
            'totalValeur' => $totalValeur
        ]);

        return response()->json($charges);
    }

    public function getFacturesReport(Request $request)
    {
        // Validate the date range parameters
        $request->validate([
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y'
        ]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');


        $factures = Factures::whereBetween('date', [$startDate, $endDate])
            ->select('id', 'ref', 'date', 'totalTTC', 'totalHT')
            ->groupBy('id', 'ref', 'date', 'totalTTC', 'totalHT')
            ->selectRaw('SUM(totalTTC) as totalTTC')
            ->get();
        $total = $factures->sum('totalTTC');


        return response()->json([
            'factures' => $factures,
            'total' => $total
        ]);
    }


    public function getDevisStatus(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date_format:d/m/Y',
            'endDate' => 'required|date_format:d/m/Y'
        ]);

        $startDate = Carbon::createFromFormat('d/m/Y', $request->input('startDate'))->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $request->input('endDate'))->format('Y-m-d');

        // Calculate the counts for each status within the specified period
        $statistics = Devis::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // Define default values for missing statuses
        $statusLabels = [
            'still' => 'Still',
            'done' => 'Done',
            'refused' => 'Refused',
        ];

        $statistics = array_merge(
            array_fill_keys(array_keys($statusLabels), 0), // Ensure all statuses are present
            $statistics
        );

        return response()->json([
            'statistics' => $statistics
        ], Response::HTTP_OK);
    }
}
