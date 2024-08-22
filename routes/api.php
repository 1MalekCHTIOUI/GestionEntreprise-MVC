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
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\ChargesController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\CutController;
use App\Http\Controllers\ExceptionsController;
use App\Http\Controllers\FacturesController;
use App\Http\Controllers\HistoriquesController;
use App\Http\Controllers\MailingController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\TaxeController;
use App\Http\Controllers\TresorieController;

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

Route::apiResource('parameters', ParameterController::class);
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
    Route::get('search/{searchQuery}', [DevisController::class, 'search']);

    Route::post('create', [DevisController::class, 'store']);
    Route::put('edit/{id}', [DevisController::class, 'update']);
    Route::delete('delete/{id}', [DevisController::class, 'destroy']);
    Route::post('send-pdf/{clientId}', [MailingController::class, 'sendPdfToClient']);
    Route::patch('{id}/status', [DevisController::class, 'updateStatus']);
});

Route::get('clients/allPaginate', [ClientController::class, 'getClientsPaginate']);
Route::get('clients/findPaginate', [ClientController::class, 'findClientPaginate']);
Route::get('clients/search/{name}', [DevisController::class, 'searchClientByName']);
Route::apiResource('clients', ClientController::class);


Route::group([
    'prefix' => 'produits'
], function ($router) {

    Route::get('', [ProduitsController::class, 'findAll']);
    Route::get('allPaginate', [ProduitsController::class, 'findAllPaginate']);
    Route::post('create', [ProduitsController::class, 'createProduit']);
    Route::put('addQte', [ProduitsController::class, 'addProduitQte']);
    Route::get('search', [ProduitsController::class, 'search']);

    Route::get('{id}', [ProduitsController::class, 'findProduit']);
    Route::get('/find/{ref}/ref', [ProduitsController::class, 'findByRef']);
    Route::get('/find/{title}/titre', [ProduitsController::class, 'findByTitle']);
    Route::get('findByRefAndTitle/{term}', [ProduitsController::class, 'findByRefAndTitle']);
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
    Route::get('remainingBalance/{numFacture}', [FacturesController::class, 'getRemainingBalance']);
    Route::post('create/{devisId}', [FacturesController::class, 'createFacture']);
});



Route::group([
    'prefix' => 'tresorie'
], function ($router) {
    Route::get('allPaginate', [TresorieController::class, 'getTresoriePaginate']);

    Route::get('', [TresorieController::class, 'index']);
    Route::post('', [TresorieController::class, 'store']);
    Route::get('{id}', [TresorieController::class, 'show']);
    Route::put('{id}', [TresorieController::class, 'update']);
    Route::delete('/{id}', [TresorieController::class, 'destroy']);
    Route::get('autocomplete-facture', [TresorieController::class, 'autocompleteFacture']);

    Route::get('searchByClientName/{clientName}', [TresorieController::class, 'searchByClientName']);
    Route::get('searchByFactureRef/{factureRef}', [TresorieController::class, 'searchByFactureRef']);
    Route::get('search/{searchQuery}', [TresorieController::class, 'search']);
    Route::get('facturePaiements/{numFacture}', [TresorieController::class, 'getFacturePaiements']);
});

Route::group([
    'prefix' => 'credits'
], function ($router) {
    Route::get('searchByClientName/{clientName}', [CreditController::class, 'searchByClientName']);
    Route::get('searchByFactureRef/{factureRef}', [CreditController::class, 'searchByFactureRef']);
    Route::get('allPaginate', [CreditController::class, 'getCreditPaginate']);
    Route::get('', [CreditController::class, 'index']);
});

Route::get('send-devis/{id}', [MailingController::class, 'sendDevis']);
Route::post('send-promotion/{id}', [MailingController::class, 'sendPromotion']);
Route::group([
    'prefix' => 'stats'
], function ($router) {
    Route::get('devis-comparison-range', [StatistiquesController::class, 'getDevisComparison']);
    Route::get('devis-status-range', [StatistiquesController::class, 'getDevisStatus']);
    Route::get('devis-range', [StatistiquesController::class, 'getDevisByDateRange']);
    Route::get('products-success-range', [StatistiquesController::class, 'getMostSuccessfulProducts']);
    Route::get('profits-range', [StatistiquesController::class, 'getProfitsReport']);
    Route::get('profits', [StatistiquesController::class, 'getProfits']);
    Route::get('clients-range', [StatistiquesController::class, 'getMostSuccessfulClients']);
    Route::get('charges-range', [StatistiquesController::class, 'getChargesReport']);
    Route::get('factures-range', [StatistiquesController::class, 'getFacturesReport']);
});



Route::get('taxes/search', [TaxeController::class, 'search']);
Route::apiResource('taxes', TaxeController::class);
Route::apiResource('promotions', PromotionsController::class);
Route::apiResource('charges', ChargesController::class);



Route::post('/optimize-cuts', [CutController::class, 'optimize']);
