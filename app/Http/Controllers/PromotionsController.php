<?php

namespace App\Http\Controllers;

use App\Models\Historique;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PromotionsController extends Controller
{
    public function index()
    {
        return Promotion::with('produits')->get();
    }

    public function show($id)
    {
        return Promotion::with('produits')->findOrFail($id);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required',
                'promo' => 'required|numeric',
                'image_footer' => 'nullable|image',
                'produits' => 'array|required'
            ]);

            $promotion = Promotion::create($request->only('description', 'promo'));


            if ($request->hasFile('image_footer')) {
                $fileName = time() . '_' . $request->file('image_footer')->getClientOriginalName();

                $path = $request->file('image_footer')->storeAs('assets/images/promotions', $fileName, 'public');
                $promotion['image_footer'] =  $fileName;
            }

            if ($request->has('produits')) {
                $productIds = [];
                foreach ($request->produits as $produit) {
                    $productIds[] = $produit;
                }
                $promotion->produits()->sync($productIds);
            }

            $promotion->save();

            Historique::create([
                'table' => 'Promotion',
                'id_record' => $promotion->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $promotion->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'description' => 'required',
                'promo' => 'required|numeric',
                'image_footer' => 'nullable',
                'produits' => 'array|required'
            ]);

            $promotion = Promotion::findOrFail($id);


            if ($request->hasFile('image_footer')) {
                $fileName = time() . '_' . $request->file('image_footer')->getClientOriginalName();

                $request->file('image_footer')->storeAs('assets/images/promotions', $fileName, 'public');
                $promotion['image_footer'] = $fileName;
            }

            $promotion->update($request->only('description', 'promo'));


            $dataBefore = $promotion->getOriginal();
            $promotion->update($request->all());

            Historique::create([
                'table' => 'Promotion',
                'id_record' => $promotion->id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $promotion->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);

            if ($request->has('produits')) {
                $promotion->produits()->sync($request->produits);
            }

            return response()->json($promotion);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            // Promotion::destroy($id);

            $promo = Promotion::find($id);

            if (is_null($promo)) {
                return response()->json(['message' => 'Promotion not found'], 404);
            }


            $dataBefore = $promo;


            $promo->delete();

            Historique::create([
                'table' => 'Promotion',
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
}
