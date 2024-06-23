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
            ->paginate(10);
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
            'promo' => 'required|numeric',
            'longueur' => 'required|numeric',
            'largeur' => 'required|numeric',
            'hauteur' => 'required|numeric',
            'profondeur' => 'required|numeric',
            'tempsProduction' => 'required|integer',
            'matiers' => 'required',
            'description' => 'required',
            'descriptionTechnique' => 'required',
            'ficheTechnique' => 'file',
            'publicationSocial' => 'required',
            'fraisTransport' => 'required|numeric',
            'idCategorie' => 'required',
            'imagePrincipale' => 'image',
            'active' => 'required|boolean',
            'accessoires' => 'required',
            'images' => 'array'
        ]);

        if ($request->hasFile('imagePrincipale')) {
            $fileName = time() . '_' . $request->file('imagePrincipale')->getClientOriginalName();

            $path = $request->file('imagePrincipale')->storeAs('assets/images/produits', $fileName, 'public');
            $validatedData['imagePrincipale'] = '/storage/' . $path;
        }

        if ($request->hasFile('ficheTechnique')) {
            $fileName = time() . '_' . $request->file('ficheTechnique')->getClientOriginalName();

            $path = $request->file('ficheTechnique')->storeAs('assets/fichiers/produits', $fileName, 'public');
            $validatedData['ficheTechnique'] = '/storage/' . $path;
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
                        'titreImg' => '/storage/' . $path,
                        'date' => now(),
                    ]);
                }
            }

            foreach ($accessoires as $accessoire) {
                $produit->accessoires()->attach($accessoire['idAccessoire'], ['qte' => $accessoire['qte']]);
                $accessory = Accessoires::find($accessoire['idAccessoire']);
                $accessory->qte -= $accessoire['qte'];

                $accessory->save();
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

            return response()->json(['message' => 'Failed to save produit'], 500);
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
        $produits = Produits::with(['accessoires', 'images', 'categories'])->orderBy('created_at', 'desc')->paginate(10);
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
            'promo' => 'required|numeric',
            'longueur' => 'required|numeric',
            'largeur' => 'required|numeric',
            'hauteur' => 'required|numeric',
            'profondeur' => 'required|numeric',
            'tempsProduction' => 'required|integer',
            'matiers' => 'required',
            'description' => 'required',
            'descriptionTechnique' => 'required',
            'ficheTechnique' => 'sometimes',
            'publicationSocial' => 'required',
            'fraisTransport' => 'required|numeric',
            'idCategorie' => 'required',
            'imagePrincipale' => 'sometimes',
            'active' => 'required|boolean',
            'accessoires' => 'required',
            'images' => 'array',
            'existing_images' => 'array'
        ]);

        if ($request->hasFile('imagePrincipale')) {
            $fileName = time() . '_' . $request->file('imagePrincipale')->getClientOriginalName();

            $path = $request->file('imagePrincipale')->storeAs('assets/images/produits', $fileName, 'public');
            $validatedData['imagePrincipale'] = '/storage/' . $path;
        }

        if ($request->hasFile('ficheTechnique')) {
            $fileName = time() . '_' . $request->file('ficheTechnique')->getClientOriginalName();

            $path = $request->file('ficheTechnique')->storeAs('assets/fichiers/produits', $fileName, 'public');
            $validatedData['ficheTechnique'] = '/storage/' . $path;
        }



        $existingAccessoires = $produit->accessoires->pluck('id')->toArray();
        $requestedAccessoires = collect(json_decode($request->accessoires, true))->pluck('idAccessoire')->toArray();

        $removedAccessoires = array_diff($existingAccessoires, $requestedAccessoires);
        foreach ($removedAccessoires as $removedAccessoireId) {
            $accessoire = Accessoires::find($removedAccessoireId);
            $pivotRow = $produit->accessoires()->where('idAccessoire', $removedAccessoireId)->first();
            if ($accessoire && $pivotRow) {
                $accessoire->qte += $pivotRow->pivot->qte;
                $accessoire->save();
            }
        }



        // Get the IDs of the existing images that should be kept
        $existingImages = $request->input('existing_images', []);

        // Get the existing images of the product
        $produitImages = $produit->images;

        // Iterate over the existing images
        foreach ($produitImages as $image) {
            if (!in_array($image->id, $existingImages)) {

                Storage::disk('public')->delete(str_replace('/storage/', '', $image->titreImg));
                $image->delete();
            }
        }




        $produit->accessoires()->detach($removedAccessoires);

        $accessoiresData = [];
        foreach (json_decode($request->accessoires, true) as $accessoire) {
            $idAccessoire = $accessoire['idAccessoire'];
            $qte = $accessoire['qte'];
            $accessoireModel = Accessoires::find($idAccessoire);
            if ($accessoireModel) {
                if (in_array($idAccessoire, $existingAccessoires)) {
                    $pivotRow = $produit->accessoires()->where('idAccessoire', $idAccessoire)->first();
                    if ($pivotRow) {
                        $difference = $pivotRow->pivot->qte - $qte;
                        $accessoireModel->qte += $difference;
                        $accessoireModel->save();
                    }
                } else {
                    $accessoireModel->qte -= $qte;
                    $accessoireModel->save();
                }
            }
            $accessoiresData[$idAccessoire] = ['qte' => $qte];
        }

        $produit->accessoires()->sync($accessoiresData);


        try {
            $produit->update($validatedData);
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $fileName = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('assets/images/produits', $fileName, 'public');
                    try {
                        // $test = Images::create([
                        //     'idProduit' => $produit->id,
                        //     'titreImg' => '/storage/' . $path,
                        //     'date' => now(),
                        // ]);
                        $test = new Images();
                        $test->idProduit = $produit->id;
                        $test->titreImg = '/storage/' . $path;
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
            return response()->json(['message' => 'Failed to edit produit'], 500);
        }
    }
}
