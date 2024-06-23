<?php

use App\Http\Controllers\AccessoiresController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\CategoriesController;
use App\Models\Historique;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'prefix' => 'categories'
], function ($router) {

    Route::get('', [CategoriesController::class, 'findAllParents']);
    Route::get('allPaginate', [CategoriesController::class, 'getAllCategoriesPaginate']);
    Route::get('all', [CategoriesController::class, 'getAllCategories']);
    Route::get('children', [CategoriesController::class, 'findAllChildren']);
    Route::post('create', [CategoriesController::class, 'createCategory']);

    Route::get('{id}', [CategoriesController::class, 'findCategory']);
    Route::put('edit/{id}', [CategoriesController::class, 'editCategory']);
    Route::delete('delete/{id}', [CategoriesController::class, 'deleteCategory']);
});

Route::group([
    'prefix' => 'produits'
], function ($router) {

    Route::get('', [ProduitsController::class, 'findAll']);
    Route::get('allPaginate', [ProduitsController::class, 'findAllPaginate']);
    Route::post('create', [ProduitsController::class, 'createProduit']);

    Route::get('{id}', [ProduitsController::class, 'findProduit']);
    Route::put('edit/{id}', [ProduitsController::class, 'editProduit']);
    Route::delete('delete/{id}', [ProduitsController::class, 'deleteProduit']);
});

Route::group([
    'prefix' => 'accessoires'
], function ($router) {

    Route::get('', [AccessoiresController::class, 'findAll']);
    Route::get('allPaginate', [AccessoiresController::class, 'findAllPaginate']);
    Route::post('create', [AccessoiresController::class, 'createAccessoire']);

    Route::get('{id}', [AccessoiresController::class, 'findAccessoire']);
    Route::put('edit/{id}', [AccessoiresController::class, 'editAccessoire']);
    Route::delete('delete/{id}', [AccessoiresController::class, 'deleteAccessoire']);
});


Route::get('historiques', function () {
    $historiques = Historique::orderBy('created_at', 'desc')->paginate(10);
    return response()->json($historiques, 200);
});

Route::get(
    'historiques/search',
    function (Request $request) {
        $historiques = Historique::where('table', 'like', '%' . $request->search_string . '%')
            ->orWhere('action', 'like', '%' . $request->search_string . '%')
            ->orWhere('id_record', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($historiques->count() >= 1) {
            return response()->json(
                $historiques,
                200
            );
        } else {
            return response()->json(
                'Nothing found',
                404
            );
        }
    }
);

Route::get('historiques/sort', function (Request $request) {
    $sortDirection = $request->get('sort', 'asc');
    $historiques = Historique::orderBy('created_at', $sortDirection)->paginate(10);
    return response()->json(
        $historiques,
        200
    );
});

Route::get('exceptions', function () {
    $exceptions = DB::table('logs')->orderBy('created_at', 'desc')->paginate(10);
    return response()->json($exceptions, 200);
});




Route::get(
    'exceptions/search',
    function (Request $request) {
        $exceptions = DB::table('logs')->where('message', 'like', '%' . $request->search_string . '%')
            ->orWhere('level', 'like', '%' . $request->search_string . '%')
            ->orWhere('context', 'like', '%' . $request->search_string . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($exceptions->total() >= 1) {
            return response()->json(
                $exceptions,
                200
            );
        } else {
            return response()->json(
                'Nothing found',
                404
            );
        }
    }
);


Route::get('exceptions/sort', function (Request $request) {
    $sortDirection = $request->get('sort', 'desc');
    $exceptions = DB::table('logs')->orderBy('created_at', $sortDirection)->paginate(10);
    return response()->json($exceptions, 200);
});
