<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Produits;
use Illuminate\Http\Request;
use App\Models\DevisProduits;
use App\Models\Historique;
use Illuminate\Http\Response;
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
        $devis = Devis::with(['produits', 'client', 'taxes'])->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
        return response()->json($devis);
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'idClient' => 'required|exists:clients,id',
                'date' => 'required|date',
                'items' => 'required|array',
                'items.*.produit.id' => 'required|exists:produits,id',
                'items.*.quantity' => 'required|integer|min:1',
                'taxes' => 'required|array',

            ]);

            $devis = Devis::create([
                'client_id' => $request->idClient,
                'date' => $request->date,
            ]);

            $devis->ref = $devis->generateDevisNumber();
            $devis->status = Devis::STATUS_STILL;
            $devis->save();
            Historique::create([
                'table' => 'Devis',
                'id_record' => $devis->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $devis->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
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
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => $th->getMessage()]);
        }
    }


    public function show($id)
    {
        // $devis = Devis::with('client', 'produits')->findOrFail($id);
        $devis = Devis::with(['produits.accessoires', 'client', 'client.state', 'client.state.country', 'taxes'])->findOrFail($id);
        return response()->json($devis);
    }

    public function update(Request $request, $id)
    {
        try {

            Log::info($request->all());

            $request->validate([
                'idClient' => 'required|integer',
                'date' => 'required|date',
                'status' => 'nullable|in:still,done,refused',
                'items' => 'required|array',
                'items.*.produit.id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
                'taxes' => 'required|array',

            ]);

            $devis = Devis::findOrFail($id);
            $dataBefore = $devis->getOriginal();
            $devis->update([
                'client_id' => $request->idClient,
                'date' => $request->date,
                'status' => $request->status,
            ]);

            Historique::create([
                'table' => 'Devis',
                'id_record' => $devis->id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $devis->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);


            $taxIds = collect($request->taxes)->pluck('tax.id')->toArray();

            $devis->taxes()->sync($taxIds);

            $devis->produits()->detach();


            foreach ($request->items as $item) {
                $devis->produits()->attach($item['produit']['id'], ['qte' => $item['quantity']]);
            }

            return response()->json(['message' => 'Devis updated successfully']);
        } catch (\Exception $th) {
            return response()->json(['message' => 'Devis failed: ' . $th->getMessage()]);
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function destroy($id)
    {
        try {

            $devis = Devis::findOrFail($id);
            $dataBefore = $devis;
            $devis->delete();

            Historique::create([
                'table' => 'Devis',
                'id_record' => $dataBefore->id,
                'action' => 'DELETE',
                'data_before' => $dataBefore,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            return response()->json(null, 204);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function getClients()
    {
        $client = Client::all();
        return response()->json($client);
    }

    public function searchClientByName($name)
    {
        $client = Client::where('nom', 'LIKE', '%' . $name . '%')->orWhere('prenom', 'LIKE', '%' . $name . '%')->get();
        return response()->json($client);
    }

    public function search(string $searchQuery)
    {

        $devis = Devis::query()
            ->whereHas('client', function ($query) use ($searchQuery) {
                $query->where('nom', 'LIKE', '%' . $searchQuery . '%')->orWhere('prenom', 'LIKE', '%' . $searchQuery . '%');
            })->with(['client', 'taxes'])
            ->paginate(config('global.pagination.perPage'));
        return response()->json($devis);
    }


    public function updateStatus(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'status' => 'required|in:still,done,refused',
        ]);

        // Find the devis by ID
        $devis = Devis::findOrFail($id);

        // Update the status
        $devis->status = $request->input('status');
        $devis->save();

        // Return a successful response
        return response()->json(
            $devis,
            Response::HTTP_OK
        );
    }
}
