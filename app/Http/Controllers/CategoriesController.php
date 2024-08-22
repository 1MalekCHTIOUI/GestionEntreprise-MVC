<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Historique;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{

    public function index(Request $request)
    {
        $categories = Categories::with('parent')->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
        if ($request->ajax()) {
            return view('categories.liste', compact('categories'))->render();
        }
        return view('categories.index', compact('categories'));
    }


    public function getEditView($id)
    {


        $category = Categories::find($id);
        // $parents = Categories::with('sousCategories')->whereNull('idParentCateg')->where('id', '!=', $id)->get();
        $parents = Categories::with('parent')->get();

        if (!$category) {

            return redirect()->route('categories.index')->with('error', 'Category not found');
        }

        return view('categories.edit', array('category' => $category, 'parents' => $parents));
    }


    public function getCreateView()
    {
        //$parents = Categories::with('sousCategories')->whereNull('idParentCateg')->get();
        $parents = Categories::whereNull('idParentCateg')->get();

        return view('categories.create', compact('parents'));
    }

    public function getAllCategoriesPaginate()
    {
        $categories = Categories::with('parent')->orderBy('created_at', 'desc')->paginate(config('global.pagination.perPage'));
        return response()->json($categories);
    }

    public function getAllCategories()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }

    public function findAllParents()
    {
        $query = Categories::query();
        $categories = $query->whereNull('idParentCateg')->get();
        return response()->json($categories);
    }

    public function findAllChildren()
    {
        $query = Categories::query();
        $categories = $query->whereNotNull('idParentCateg')->get();

        return response()->json($categories);
    }

    public function findCategory($id)
    {
        $category = Categories::find($id);
        return response()->json($category);
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'titreCateg' => 'required|string',
            'descriptionCateg' => 'required|string',
        ]);

        $category = new Categories();


        try {

            $new = $category->create([
                'titreCateg' => $request->titreCateg,
                'descriptionCateg' => $request->descriptionCateg,
                'idParentCateg' => $request->categorie,
            ]);
            Historique::create([
                'table' => 'Categories',
                'id_record' => $new->id,
                'action' => 'CREATE',
                'data_before' => null,
                'data_after' => $new->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
            return response()->json($new);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Error saving category'], 500);
        }
    }
    public function editCategory($id, Request $request)
    {
        $request->validate([
            'titreCateg' => 'required',
            'descriptionCateg' => 'required',
        ]);

        $cat = Categories::find($id);
        if (!$cat) {
            return response()->error("Not found");
        }


        // $existingSubcategories = $cat->sousCategories->pluck('id')->toArray();
        // $requestedSubcategories = collect($request->sousCategories)->pluck('id')->toArray();
        // $removedSousCategories = array_diff($existingSubcategories, $requestedSubcategories);
        // Categories::whereIn('id', $removedSousCategories)->update(['idParentCateg' => null]);

        // $newSousCategories = array_diff($requestedSubcategories, $existingSubcategories);
        // Categories::whereIn('id', $newSousCategories)->update(['idParentCateg' => $cat->id]);


        $cat->titreCateg = $request->titreCateg;
        $cat->descriptionCateg = $request->descriptionCateg;
        $cat->idParentCateg = $request->categorie;
        try {
            $dataBefore = $cat->getOriginal();
            $s = $cat->save();
            Historique::create([
                'table' => 'Categories',
                'id_record' => $cat->id,
                'action' => 'UPDATE',
                'data_before' => $dataBefore,
                'data_after' => $cat->getAttributes(),
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
            return response()->json($s);
        } catch (\Exception $th) {

            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->json(['message' => 'Error updating category'], 500);
        }
    }

    public function deleteCategory($id)
    {

        $cat = Categories::find($id);
        if (!$cat) {
            return response()->error("Not found: " . $id);
        }

        if ($cat->idParentCateg == null) {
            $children = Categories::where('idParentCateg', $id)->get();
            foreach ($children as $child) {
                $child->idParentCateg = null;
                $child->save();
            }
        }
        try {
            $dataBefore = $cat;
            $cat = $cat->delete();
            Historique::create([
                'table' => 'Categories',
                'id_record' => $dataBefore->id,
                'action' => 'DELETE',
                'data_before' => $dataBefore,
                'data_after' => null,
                'changed_at' => now(),
                'changed_by' =>  null,
            ]);
            return response()->json($cat, 200);
        } catch (\Exception $th) {
            Log::channel('database')->error($th->getMessage(), [
                'class' => __CLASS__,
                'function' => __FUNCTION__
            ]);
            return response()->error("Failed to delete category");
        }

        return response()->json($cat);
    }
}
