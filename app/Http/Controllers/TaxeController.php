<?php

namespace App\Http\Controllers;

use App\Models\Historique;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {


            $tax = Tax::create($request->all());
            Historique::create([
                'table' => 'Tax',
                'id_record' => $tax->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $tax->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            return response()->json($tax, 201);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
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
        try {

            $tax = Tax::find($id);

            if (is_null($tax)) {
                return response()->json(['message' => 'Tax not found'], 404);
            }

            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'rate' => 'sometimes|required|numeric|min:0|max:100',
            ]);

            $dataBefore = $tax->getOriginal();
            $tax->update($request->all());

            Historique::create([
                'table' => 'Tax',
                'id_record' => $tax->id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $tax->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);


            return response()->json($tax);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tax = Tax::find($id);

            if (is_null($tax)) {
                return response()->json(['message' => 'Tax not found'], 404);
            }

            $dataBefore = $tax;


            $tax->delete();

            Historique::create([
                'table' => 'Tax',
                'id_record' => $dataBefore->id,
                'action' => 'DELETE',
                'data_before' => $dataBefore,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
            return response()->json(['message' => 'Tax deleted successfully']);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }


    public function search(Request $request)
    {
        Log::error('Search results:', ['results' => $request->all()]);

        $query = $request->input('query');
        $limit = $request->input('limit', 30);

        if ($query) {
            $results = Tax::where('name', 'LIKE', '%' . $query . '%')->limit($limit)->get();
        } else {
            $results = Tax::limit($limit)->get();
        }
        return response()->json($results);
    }
}
