<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        $clients = Client::all();

        return response()->json([
            'clients' => $clients
        ]);
    }
    public function getClientsPaginate(Request $request)
    {
        try {
            // Log the request parameters for debugging
            Log::info('Request parameters:', $request->all());

            // Fetch clients with pagination
            $clients = Client::paginate(10);

            // Log the query result for debugging
            Log::info('Clients fetched:', $clients->toArray());

            if ($clients->isEmpty()) {
                return response()->json(['message' => 'Client not found'], 404);
            }

            return response()->json($clients, 200);
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error fetching clients:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'An error occurred while fetching clients'], 500);
        }
    }

    public function findClientPaginate(Request $request): JsonResponse
    {
        $clients = Client::where('nom', 'LIKE', '%' . $request->search . '%')
            ->orWhere('prenom', 'LIKE', '%' . $request->search . '%')
            ->orWhere('nom_societe', 'LIKE', '%' . $request->search . '%')
            ->orWhere('tel1', 'LIKE', '%' . $request->search . '%')
            ->orWhere('email', 'LIKE', '%' . $request->search . '%')
            ->orWhere('adresse', 'LIKE', '%' . $request->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination.perPage'));

        return response()->json(
            $clients
        );
    }

    public function show($id): JsonResponse
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Client not found'
            ], 404);
        }

        return response()->json([
            'client' => $client
        ]);
    }


    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());
        $clientData = $request->validated();
        // Handle logo upload if exists
        if ($request->hasFile('logo')) {
            $clientData['logo'] = $this->uploadLogo($request);
        }
        $client = Client::create($clientData);
        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }

    public function update(Request $request, Client $client): JsonResponse
    {
        Log::info($request->all());

        // $clientData = $request->validated();
        $clientData = $request->all();
        // Handle logo upload if exists
        if ($request->hasFile('logo')) {
            Log::info("Logo: " . $clientData['logo']);
            $clientData['logo'] = $this->uploadLogo($request);
        }

        $client->update($clientData);
        return response()->json([
            'message' => 'Client updated successfully',
            'client' => $client
        ]);
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully'
        ]);
    }

    private function uploadLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|mimes:png,jpg,jpeg,gif'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please fix the errors',
                'errors' => $validator->errors()
            ], 422);
        }
        $fileName = time() . '_' . $request->file('logo')->getClientOriginalName();

        $request->file('logo')->storeAs('assets/images/clients', $fileName, 'public');
        $validatedData['logo'] = $fileName;
        return $fileName;
    }

    public function search(Request $request): JsonResponse
    {
        $query = Client::query();

        if ($request->filled('nom')) {
            $query->where('nom', 'LIKE', '%' . $request->nom . '%');
        }

        if ($request->filled('prenom')) {
            $query->where('prenom', 'LIKE', '%' . $request->prenom . '%');
        }

        if ($request->filled('nom_societe')) {
            $query->where('nom_societe', 'LIKE', '%' . $request->nom_societe . '%');
        }

        if ($request->filled('tel1')) {
            $query->where('tel1', 'LIKE', '%' . $request->tel1 . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('adresse')) {
            $query->where('adresse', 'LIKE', '%' . $request->adresse . '%');
        }

        $clients = $query->get();

        return response()->json([
            'clients' => $clients
        ]);
    }
}
