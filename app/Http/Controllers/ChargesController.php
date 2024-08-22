<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ChargesController extends Controller
{
    /**
     * Display a listing of the charges.
     */
    public function index()
    {
        $charges = Charge::all();
        return response()->json($charges, 200);
    }

    /**
     * Store a newly created charge in the database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required',
            'type' => 'required|in:static,variable',
            'description' => 'nullable',
            'valeur' => 'required|numeric',
            'repetition' => 'required|integer',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $charge = Charge::create($request->all());
        return response()->json($charge, 201);
    }

    /**
     * Display the specified charge.
     */
    public function show($id)
    {
        $charge = Charge::find($id);

        if (is_null($charge)) {
            return response()->json(['message' => 'Charge not found'], 404);
        }

        return response()->json($charge, 200);
    }

    /**
     * Update the specified charge in the database.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'titre' => 'required',
            'type' => 'required|in:static,variable',
            'description' => 'nullable',
            'valeur' => 'required|numeric',
            'repetition' => 'required|integer',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $charge = Charge::find($id);

        if (is_null($charge)) {
            return response()->json(['message' => 'Charge not found'], 404);
        }

        $charge->update($request->all());
        return response()->json($charge, 200);
    }

    /**
     * Remove the specified charge from the database.
     */
    public function destroy($id)
    {
        $charge = Charge::find($id);
        if (is_null($charge)) {
            return response()->json(['message' => 'Charge not found'], 404);
        }

        $charge->delete();
        return response()->json(['message' => 'Charge deleted successfully'], 200);
    }
}
