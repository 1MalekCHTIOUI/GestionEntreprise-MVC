<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ], 201);
    }
}
