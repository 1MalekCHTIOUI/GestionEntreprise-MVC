<?php

use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AccessoiresController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'prefix' => 'categories'
], function ($router) {

    Route::get('', [CategoriesController::class, 'index'])->name('categories.index');
    Route::get('create', [CategoriesController::class, 'getCreateView'])->name('categories.create');
    Route::get('edit/{id}', [CategoriesController::class, 'getEditView'])->name('categories.edit');
});


Route::group([
    'prefix' => 'produits'
], function ($router) {

    Route::get('', [ProduitsController::class, 'index'])->name('produits.index');
    Route::get('create', [ProduitsController::class, 'getCreateView'])->name('produits.create');
    Route::get('edit/{id}', [ProduitsController::class, 'getEditView'])->name('produits.edit');
    Route::get('{id}', [ProduitsController::class, 'getProductView'])->name('produits.show');
});

Route::group([
    'prefix' => 'accessoires'
], function ($router) {

    Route::get('', [AccessoiresController::class, 'index'])->name('accessoires.index');
    Route::get('create', [AccessoiresController::class, 'getCreateView'])->name('accessoires.create');
    Route::get('edit/{id}', [AccessoiresController::class, 'getEditView'])->name('accessoires.edit');
});

Route::get('historiques', function (Request $request) {
    $historiques = Historique::orderBy('created_at', 'desc')->paginate(10);

    if ($request->ajax()) {
        return view('historiques.liste', compact('historiques'))->render();
    }
    return view('historiques.index', compact('historiques'));
})->name('historiques.index');


Route::get('exceptions', function (Request $request) {
    $exceptions = DB::table('logs')->orderBy('created_at', 'desc')->paginate(10);
    if ($request->ajax()) {
        return view('exceptions.liste', compact('exceptions'))->render();
    }
    return view('exceptions.index', compact('exceptions'));
})->name('exceptions.index');


Route::get(
    'historiques/search',
    function (Request $request) {
        $historiques = Historique::where('table', 'like', '%' . $request->search_string . '%')
            ->orWhere('action', 'like', '%' . $request->search_string . '%')
            ->orWhere('id_record', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($historiques->count() >= 1) {
            return view('historiques.liste', compact('historiques'))->render();
        } else {
            return response()->json([
                'status' => 'nothing found'
            ]);
        }
    }
)->name('historiques.search');

Route::get('historiques.sort', function (Request $request) {
    $sortDirection = $request->get('sort', 'asc');
    $historiques = Historique::orderBy('created_at', $sortDirection)->paginate(10);
    if ($request->ajax()) {
        return view('historiques.liste', compact('historiques'))->render();
    }
    return view('historiques.index', compact('historiques'));
})->name('historiques.sort');


Route::get(
    'exceptions/search',
    function (Request $request) {
        $exceptions = DB::table('logs')->where('message', 'like', '%' . $request->search_string . '%')
            ->orWhere('level', 'like', '%' . $request->search_string . '%')
            ->orWhere('context', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($exceptions->total() >= 1) {
            return view('exceptions.liste', compact('exceptions'))->render();
        } else {
            return response()->json([
                'status' => 'nothing found'
            ]);
        }
    }
)->name('exceptions.search');


Route::get('exceptions.sort', function (Request $request) {
    $sortDirection = $request->get('sort', 'asc');
    $exceptions = DB::table('logs')->orderBy('created_at', $sortDirection)->paginate(10);
    if ($request->ajax()) {
        return view('exceptions.liste', compact('exceptions'))->render();
    }
    return view('exceptions.index', compact('exceptions'));
})->name('exceptions.sort');
