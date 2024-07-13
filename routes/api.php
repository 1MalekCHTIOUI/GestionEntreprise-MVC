<?php

use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AccessoiresController;
use App\Http\Controllers\ExceptionsController;
use App\Http\Controllers\FacturesController;
use App\Http\Controllers\HistoriquesController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\TaxeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__ . '/Auth.php';


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
    'prefix' => 'devis'
], function ($router) {

    Route::get('', [DevisController::class, 'index']);
    Route::get('allPaginate', [DevisController::class, 'getDevisPaginate']);
    Route::get('{id}', [DevisController::class, 'show']);

    Route::post('create', [DevisController::class, 'store']);
    Route::put('edit/{id}', [DevisController::class, 'update']);
    Route::delete('delete/{id}', [DevisController::class, 'destroy']);
});

Route::get('clients', [DevisController::class, 'getClients']);


Route::group([
    'prefix' => 'produits'
], function ($router) {

    Route::get('', [ProduitsController::class, 'findAll']);
    Route::get('allPaginate', [ProduitsController::class, 'findAllPaginate']);
    Route::post('create', [ProduitsController::class, 'createProduit']);
    Route::put('addQte', [ProduitsController::class, 'addProduitQte']);

    Route::get('{id}', [ProduitsController::class, 'findProduit']);
    Route::get('/find/{ref}/ref', [ProduitsController::class, 'findByRef']);
    Route::get('/find/{title}/titre', [ProduitsController::class, 'findByTitle']);
    Route::put('edit/{id}', [ProduitsController::class, 'editProduit']);
    Route::delete('delete/{id}', [ProduitsController::class, 'deleteProduit']);
});

Route::group([
    'prefix' => 'accessoires',
], function ($router) {

    Route::get('', [AccessoiresController::class, 'findAll']);
    Route::get('allPaginate', [AccessoiresController::class, 'findAllPaginate']);
    Route::post('create', [AccessoiresController::class, 'createAccessoire']);
    Route::put('addQte', [AccessoiresController::class, 'addAccessoireQte']);
    Route::get('/find/{title}/titre', [AccessoiresController::class, 'findByTitle']);

    Route::get('{id}', [AccessoiresController::class, 'findAccessoire']);
    Route::put('edit/{id}', [AccessoiresController::class, 'editAccessoire']);
    Route::delete('delete/{id}', [AccessoiresController::class, 'deleteAccessoire']);
});


Route::post('factures/create/{devisId}', [FacturesController::class, 'createFacture']);


Route::group([
    'prefix' => 'historiques'
], function ($router) {
    Route::get('', [HistoriquesController::class, 'getHistoriquesPaginate']);
    Route::get(
        'search',
        [HistoriquesController::class, 'search']
    );
    Route::get('sort', [HistoriquesController::class, 'sort']);
});


Route::group([
    'prefix' => 'exceptions'
], function ($router) {
    Route::get('', [ExceptionsController::class, 'getExceptionsPaginate']);
    Route::get(
        'search',
        [ExceptionsController::class, 'search']
    );
    Route::get('sort', [ExceptionsController::class, 'sort']);
});

Route::group([
    'prefix' => 'factures'
], function ($router) {
    Route::get('allPaginate', [FacturesController::class, 'getFacturesPaginate']);
    Route::get('', [FacturesController::class, 'getFactures']);
    Route::get('{id}', [FacturesController::class, 'getFacture']);
    Route::get('devis/{id}', [FacturesController::class, 'getFactureByDevis']);

    Route::post('/create/{id}', [FacturesController::class, 'createFacture']);
});


Route::apiResource('parameters', ParameterController::class);
Route::apiResource('taxes', TaxeController::class);
