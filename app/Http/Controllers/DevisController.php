<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Produits;
use Illuminate\Http\Request;
use App\Models\DevisProduits;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;

class DevisController extends Controller
{
    public function index()
    {
        $devis = Devis::with(['client', 'produits.accessoires', 'taxes'])->orderBy('created_at', 'desc')->get();
        // $devis = Devis::with('produits')->get();
        return response()->json($devis);
    }

    public function getDevisPaginate()
    {
        $devis = Devis::with(['produits', 'client', 'taxes'])->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($devis);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idClient' => 'required|exists:clients,id',
            'date' => 'required|date',
            'valid_until' => 'required|date',
            'items' => 'required|array',
            'items.*.produit.id' => 'required|exists:produits,id',
            'items.*.quantity' => 'required|integer|min:1',
            'taxes' => 'required|array',

        ]);

        // Log all request data

        $devis = Devis::create([
            'client_id' => $request->idClient,
            'date' => $request->date,
            'valid_until' => $request->valid_until,
        ]);

        $taxIds = collect($request->taxes)->pluck('tax.id')->toArray();
        Log::info($request->taxes);

        $devis->taxes()->attach($taxIds);

        foreach ($request->items as $item) {
            DevisProduits::create([
                'idDevis' => $devis->id,
                'idProduit' => $item['produit']['id'],
                'qte' => $item['quantity'],
            ]);
        }

        return response()->json($devis->load('produits'), 201);
    }


    public function show($id)
    {
        // $devis = Devis::with('client', 'produits')->findOrFail($id);
        $devis = Devis::with(['produits.accessoires', 'client', 'client.state', 'client.state.country', 'taxes'])->findOrFail($id);
        return response()->json($devis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'idClient' => 'required|integer',
            'date' => 'required|date',
            'valid_until' => 'required|date',
            'items' => 'required|array',
            'items.*.produit.id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'taxes' => 'required|array',

        ]);

        $devis = Devis::findOrFail($id);
        $devis->update([
            'client_id' => $request->idClient,
            'date' => $request->date,
            'valid_until' => $request->valid_until,
        ]);



        $taxIds = collect($request->taxes)->pluck('tax.id')->toArray();

        $devis->taxes()->sync($taxIds);

        // Detach existing products
        $devis->produits()->detach();

        // Attach updated products
        foreach ($request->items as $item) {
            $devis->produits()->attach($item['produit']['id'], ['qte' => $item['quantity']]);
        }

        return response()->json(['message' => 'Devis updated successfully']);
    }

    public function destroy($id)
    {
        $devis = Devis::findOrFail($id);
        $devis->delete();

        return response()->json(null, 204);
    }

    public function getClients()
    {
        $client = Client::all();
        return response()->json($client);
    }
}
