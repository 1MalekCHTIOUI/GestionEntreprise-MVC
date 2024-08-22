<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $labels
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $label = Label::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Label created successfully.',
            'data' => $label
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $label = Label::find($id);

        if (!$label) {
            return response()->json([
                'success' => false,
                'message' => 'Label not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $label
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $label = Label::find($id);

        if (!$label) {
            return response()->json([
                'success' => false,
                'message' => 'Label not found.'
            ], 404);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $label->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Label updated successfully.',
            'data' => $label
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $label = Label::find($id);

        if (!$label) {
            return response()->json([
                'success' => false,
                'message' => 'Label not found.'
            ], 404);
        }

        $label->delete();

        return response()->json([
            'success' => true,
            'message' => 'Label deleted successfully.'
        ]);
    }
}
