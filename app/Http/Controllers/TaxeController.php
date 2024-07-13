<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxes = Tax::all();
        return response()->json($taxes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        $tax = Tax::create($request->all());
        return response()->json($tax, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tax = Tax::find($id);

        if (is_null($tax)) {
            return response()->json(['message' => 'Tax not found'], 404);
        }

        return response()->json($tax);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tax = Tax::find($id);

        if (is_null($tax)) {
            return response()->json(['message' => 'Tax not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'rate' => 'sometimes|required|numeric|min:0|max:100',
        ]);

        $tax->update($request->all());
        return response()->json($tax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tax = Tax::find($id);

        if (is_null($tax)) {
            return response()->json(['message' => 'Tax not found'], 404);
        }

        $tax->delete();
        return response()->json(['message' => 'Tax deleted successfully']);
    }
}
