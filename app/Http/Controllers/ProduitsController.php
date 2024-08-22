<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Produits;
use App\Models\Categories;
use App\Models\Historique;
use App\Models\Accessoires;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exceptions\DatabaseException;
use App\Models\Client;
use App\Models\ProduitsAccessoires;
use Exception;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Storage;


class ProduitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $produits = Produits::with(['accessoires', 'images', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(config('global.pagination.perPage'));
        if ($request->ajax()) {
            return view('produits.liste', compact('produits'))->render();
        }
        return view('produits.index', compact('produits'));
    }


    public function getProductView($id)
    {
        $produit = Produits::with(['accessoires', 'images', 'categories'])
            ->where('id', $id)
            ->first();
        return view('produits.show', compact('produit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getCreateView()
    {
        $accessoires = Accessoires::where('qte', '>', 0)->get();
        $categories = Categories::all();
        return view('produits.create', compact('accessoires', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function getEditView(string $id)
    {
        $produit = Produits::with(['accessoires', 'images', 'categories'])
            ->where('id', $id)
            ->first();
        $accessoires = Accessoires::where('qte', '>', 0)->get();
        $categories = Categories::all();
        return view('produits.edit', compact('produit', 'accessoires', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createProduit(Request $request)
    {
        $validatedData = $request->validate([
            'titre' => 'required',
            'ref' => 'required',
            'prixCharge' => 'required|numeric',
            'prixVente' => 'required|numeric',
            'qte' => 'required|integer',
            'qteMinGros' => 'required|integer',
            'prixGros' => 'required|numeric',
            'promo' => 'nullable|numeric',
            'longueur' => 'nullable|numeric',
            'largeur' => 'nullable|numeric',
            'hauteur' => 'nullable|numeric',
            'profondeur' => 'nullable|numeric',
            'tempsProduction' => 'nullable|integer',
            'matiers' => 'nullable',
            'description' => 'nullable',
            'descriptionTechnique' => 'nullable',
            'ficheTechnique' => 'nullable|file',
            'publicationSocial' => 'nullable',
            'fraisTransport' => 'nullable|numeric',
            'idCategorie' => 'required',
            'imagePrincipale' => 'nullable|image',
            'active' => 'nullable|boolean',
            'accessoires' => 'required',
            'images' => 'array'
        ], [
            'idCategorie.required' => 'Category is required.',
        ]);

        if ($request->hasFile('imagePrincipale')) {
            $fileName = time() . '_' . $request->file('imagePrincipale')->getClientOriginalName();

            $request->file('imagePrincipale')->storeAs('assets/images/produits', $fileName, 'public');
            $validatedData['imagePrincipale'] = $fileName;
        }

        if ($request->hasFile('ficheTechnique')) {
            $fileName = time() . '_' . $request->file('ficheTechnique')->getClientOriginalName();

            $request->file('ficheTechnique')->storeAs('assets/fichiers/produits', $fileName, 'public');
            $validatedData['ficheTechnique'] = $fileName;
        }


        try {
            $produit = Produits::create($validatedData);

            $accessoires = json_decode($request->input('accessoires'), true);


            if ($request->has('images')) {
                Log::info($request->file('images'));
                Log::info('--------');
                Log::info($request->images);
                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('assets/images/produits', $fileName, 'public');
                    Images::create([
                        'idProduit' => $produit->id,
                        'titreImg' => $fileName,
                        'date' => now(),
                    ]);
                }
            }

            foreach ($accessoires as $accessoire) {
                $produit->accessoires()->attach($accessoire['idAccessoire'], ['qte' => $accessoire['qte']]);
                // $accessory = Accessoires::find($accessoire['idAccessoire']);
                // $accessory->qte -= $accessoire['qte'];

                // $accessory->save();
            }
            Historique::create([
                'table' => 'Produits',
                'id_record' => $produit->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $produit->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
            return response()->json(['message' => 'Produit saved successfully'], 200);
        } catch (\Exception $th) {

            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);

            $existingProduit = Produits::where('ref', $validatedData['ref'])->first();
            if ($existingProduit) {
                return response()->json(['message' => 'Product with the same reference already exists'], 400);
            } else {
                return response()->json(['message' => 'Failed to save product'], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteProduit(string $id)
    {
        $product = Produits::find($id);
        try {
            if ($product) {
                $produit = $product;
                $product->accessoires()->detach();

                $product->delete();
                Historique::create([
                    'table' => 'Produits',
                    'id_record' => $produit->id,
                    'action' => 'DELETE',
                    'data_before' => $produit,
                    'data_after' => null,
                    'changed_at' => now(),
                    'changed_by' =>  null,
                ]);
                return response()->json(['message' => 'Product deleted successfully', 200]);
            } else {
                return response()->json(['message' => 'Product not found', 404]);
            }
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Failed to delete produit'], 500);
        }
    }

    public function findAll()
    {
        $produits = Produits::all();
        return response()->json($produits);
    }


    public function findAllPaginate()
    {
        $produits = Produits::with(['accessoires', 'images', 'categories'])->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
        return response()->json($produits);
    }

    public function findProduit(string $id)
    {
        $produit = Produits::with(['accessoires', 'images', 'categories'])
            ->where('id', $id)
            ->get();
        return response()->json($produit);
    }

    public function editProduit(string $id, Request $request)
    {
        $produit = Produits::find($id);
        if (!$produit) {
            return response()->json(['message' => 'Produit not found'], 404);
        }
        $validatedData = $request->validate([
            'titre' => 'required',
            'ref' => 'required',
            'prixCharge' => 'required|numeric',
            'prixVente' => 'required|numeric',
            'qte' => 'required|integer',
            'qteMinGros' => 'required|integer',
            'prixGros' => 'required|numeric',
            'promo' => 'nullable|numeric',
            'longueur' => 'nullable|numeric',
            'largeur' => 'nullable|numeric',
            'hauteur' => 'nullable|numeric',
            'profondeur' => 'nullable|numeric',
            'tempsProduction' => 'nullable|integer',
            'matiers' => 'nullable',
            'description' => 'nullable',
            'descriptionTechnique' => 'nullable',
            'ficheTechnique' => 'nullable',
            'publicationSocial' => 'nullable',
            'fraisTransport' => 'nullable|numeric',
            'idCategorie' => 'required',
            'imagePrincipale' => 'nullable',
            'active' => 'nullable|boolean',
            'accessoires' => 'required',
            'images' => 'nullable',
            'existing_images' => 'array'
        ], [
            'idCategorie.required' => 'Category is required.',
        ]);

        if ($request->hasFile('imagePrincipale')) {
            $fileName = time() . '_' . $request->file('imagePrincipale')->getClientOriginalName();

            $request->file('imagePrincipale')->storeAs('assets/images/produits', $fileName, 'public');
            $validatedData['imagePrincipale'] = $fileName;
        }

        if ($request->hasFile('ficheTechnique')) {
            $fileName = time() . '_' . $request->file('ficheTechnique')->getClientOriginalName();

            $request->file('ficheTechnique')->storeAs('assets/fichiers/produits', $fileName, 'public');
            $validatedData['ficheTechnique'] = $fileName;
        }


        $existingImages = $request->input('existing_images', []);
        $produitImages = $produit->images;

        foreach ($produitImages as $image) {
            if (!in_array($image->id, $existingImages)) {

                Storage::disk('public')->delete(str_replace('/storage/', '', $image->titreImg));
                $image->delete();
            }
        }

        $existingAccessoires = $produit->accessoires->pluck('id')->toArray();
        $requestedAccessoires = collect(json_decode($request->accessoires, true))->pluck('idAccessoire')->toArray();

        $removedAccessoires = array_diff($existingAccessoires, $requestedAccessoires);

        $produit->accessoires()->detach($removedAccessoires);
        $accessoiresData = [];
        foreach (json_decode($request->accessoires, true) as $accessoire) {
            $idAccessoire = $accessoire['idAccessoire'];
            $qte = $accessoire['qte'];

            $accessoiresData[$idAccessoire] = ['qte' => $qte];
        }

        $produit->accessoires()->sync($accessoiresData);

        try {
            $produit->update($validatedData);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('assets/images/produits', $fileName, 'public');
                    try {
                        $test = new Images();
                        $test->idProduit = $produit->id;
                        $test->titreImg = $fileName;
                        $test->date = now();
                        $test->save();
                    } catch (\Exception $th) {
                        Log::channel('database')->error($th->getMessage(), [
                            'class' => __CLASS__,
                            'function' => __FUNCTION__
                        ]);
                    }
                }
            }
            $dataBefore = $produit->getOriginal();
            Historique::create([
                'table' => 'Produits',
                'id_record' => $id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $produit->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            return response()->json(['message' => 'produit enregistrer'], 200);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            $existingProduit = Produits::where('ref', $validatedData['ref'])->first();
            if ($existingProduit) {
                return response()->json(['message' => 'Un produit avec la même référence existe déjà'], 400);
            } else {
                return response()->json(['message' => "Échec de l'enregistrement du produit"], 500);
            }
        }
    }

    // public function addProduitQte(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:produits,id',
    //         'quantity' => 'required|integer|min:1',
    //     ]);

    //     $product = Produits::find($request->product_id);
    //     $product->qte += $request->quantity;
    //     $product->save();

    //     $accessories = $product->accessoires;

    //     try {
    //         foreach ($accessories as $accessory) {

    //             $newQte = $accessory->pivot->qte * $request->quantity;
    //             $accessory->qte -= $newQte;
    //             $accessory->save();
    //         }

    //         return response()->json(['message' => 'Product and associated accessories updated successfully.']);
    //     } catch (Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }
    public function addProduitQte(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produits,id',
            'quantity' => 'required|integer|not_in:0',
        ]);

        $product = Produits::find($request->product_id);
        $newProductQte = $product->qte + $request->quantity;

        if ($newProductQte < 0) {
            return response()->json(['message' => 'Insufficient product stock.'], 400);
        }

        $product->qte = $newProductQte;
        $product->save();

        $accessories = $product->accessoires;

        try {
            foreach ($accessories as $accessory) {
                $newQte = $accessory->pivot->qte * abs($request->quantity);

                if ($request->quantity > 0) {
                    $accessory->qte -= $newQte;
                } else {
                    $accessory->qte += $newQte;
                }

                if ($accessory->qte < 0) {
                    return response()->json(['message' => 'Insufficient accessory stock.'], 400);
                }

                $accessory->save();
            }

            return response()->json(['message' => 'Product and associated accessories updated successfully.']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function findByRef($ref)
    {
        $devis = Produits::where('ref', $ref)->with('accessoires')->get();
        return response()->json($devis);
    }

    public function findByTitle($titre)
    {
        $devis = Produits::where('titre', 'like', '%' . $titre . '%')->with('accessoires')->get();
        return response()->json($devis);
    }




    public function findByRefAndTitle($term)
    {
        $devis = Produits::where('ref', 'like', '%' . $term . '%')
            ->orWhere('titre', 'like', '%' . $term . '%')
            ->with(['accessoires', 'categories'])
            ->paginate(config('global.pagination.perPage'));
        return response()->json($devis);
    }









    public function search(Request $request)
    {
        Log::error('Search results:', ['results' => $request->all()]);

        $query = $request->input('query');
        $limit = $request->input('limit', 30);

        if ($query) {
            $results = Produits::where('ref', 'LIKE', '%' . $query . '%')->limit($limit)->get();
        } else {
            $results = Produits::limit($limit)->get();
        }
        return response()->json($results);
    }
}
