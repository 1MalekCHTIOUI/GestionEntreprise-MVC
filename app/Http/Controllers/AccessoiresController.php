<?php

namespace App\Http\Controllers;

use App\Models\Historique;
use App\Models\Accessoires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exceptions\DatabaseException;

class AccessoiresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accessoires = Accessoires::orderBy('created_at', 'desc')->paginate(3);
        if ($request->ajax()) {
            return view('accessoires.liste', compact('accessoires'))->render();
        }
        return view('accessoires.index', compact('accessoires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getCreateView()
    {
        return view('accessoires.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createAccessoire(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'prixAchat' => 'required',
            'prixVente' => 'required',
            'qte' => 'required',
            'image' => 'nullable|image',
            'active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();

            $path = $request->file('image')->storeAs('assets/images/accessoires', $fileName, 'public');
            $validatedData['image'] = '/storage/' . $path;
        }

        try {
            $accessoire = Accessoires::create($validatedData);
            Historique::create([
                'table' => 'Accessoires',
                'id_record' => $accessoire->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $accessoire->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
            return response()->json(['message' => 'Accessoire saved successfully']);
        } catch (\Exception $th) {

            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Failed to save accessoire'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    public function findAllPaginate()
    {
        $accessoires = Accessoires::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($accessoires);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function getEditView(string $id)
    {
        try {
            $accessoire = Accessoires::find($id);
            return view('accessoires.edit', compact('accessoire'));
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Accessoire not found'], 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function editAccessoire(Request $request, string $id)
    {


        $accessoire = Accessoires::find($id);
        if (!$accessoire) {
            return response()->json(['message' => 'Accessoire not found'], 404);
        }

        $validatedData = $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'prixAchat' => 'required',
            'prixVente' => 'required',
            'qte' => 'required',
            'image' => 'sometimes',
            'active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();

            $path = $request->file('image')->storeAs('assets/images/accessoires', $fileName, 'public');
            $validatedData['image'] = '/storage/' . $path;
        }


        try {
            $dataBefore = $accessoire->getOriginal();
            $accessoire->update($validatedData);
            Historique::create([
                'table' => 'Accessoires',
                'id_record' => $accessoire->id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $accessoire->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);


            return response()->json(['message' => 'Accessoire updated successfully'], 200);
        } catch (\PDOException $th) {

            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Failed to update accessoire'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteAccessoire(string $id)
    {
        $accessoire = Accessoires::find($id);
        if (!$accessoire) {
            return response()->json(['message' => 'Accessoire not found'], 404);
        }
        try {
            $dataBefore = $accessoire;
            $accessoire->delete();
            Historique::create([
                'table' => 'Accessoires',
                'id_record' => $dataBefore->id,
                'action' => 'DELETE',
                'data_before' => $dataBefore,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            return response()->json(['message' => 'Accessoire deleted successfully']);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Failed to delete accessoire'], 500);
        }
    }

    public function findAll()
    {
        try {
            $accessoires = Accessoires::all();
            return response()->json($accessoires);
        } catch (\Throwable $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function findAccessoire(string $id)
    {
        try {
            $accessoire = Accessoires::find($id);
            return response()->json($accessoire);
        } catch (\Throwable $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function addAccessoireQte(Request $request)
    {
        $request->validate([
            'accessory_id' => 'required|exists:accessoires,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $accessoire = Accessoires::find($request->accessory_id);
        $accessoire->qte += $request->quantity;
        $accessoire->save();
    }

    public function findByTitle(string $title)
    {

        $accessoires = Accessoires::where('titre', 'like', '%' . $title . '%')->get();
        return response()->json($accessoires);
    }
}
