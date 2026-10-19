<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchSettingController;

/*
|--------------------------------------------------------------------------
| Product Search Routes (Meilisearch Index Management)
|--------------------------------------------------------------------------
|
| Web UI: GET /search-settings
| These endpoints are used by that page (same origin, no /api prefix).
|
*/

Route::middleware('searchvars.auth')->group(function () {
Route::get('/indexes/{indexName}/info', [SearchSettingController::class, 'getIndexInfo'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.getIndexInfo');

Route::delete('/indexes/{indexName}/delete', [SearchSettingController::class, 'deleteIndex'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.deleteIndex');

Route::post('/indexes', [SearchSettingController::class, 'createIndex'])
	->name('productsearch.createIndex');

Route::patch('/indexes/{indexName}/settings/pagination', [SearchSettingController::class, 'updatePagination'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.updatePagination');

Route::put('/indexes/{indexName}/settings/filterable-attributes', [SearchSettingController::class, 'updateFilterableAttributes'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.updateFilterableAttributes');

Route::put('/indexes/{indexName}/settings/searchable-attributes', [SearchSettingController::class, 'updateSearchableAttributes'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.updateSearchableAttributes');

Route::put('/indexes/{indexName}/settings/sortable-attributes', [SearchSettingController::class, 'updateSortableAttributes'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.updateSortableAttributes');

Route::post('/indexes/{indexName}/setup', [SearchSettingController::class, 'setupIndex'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.setupIndex');

Route::put('/indexes/{indexName}/settings/custom-ranking', [SearchSettingController::class, 'updateCustomRanking'])
	->where('indexName', '[a-zA-Z0-9_-]+')
	->name('productsearch.updateCustomRanking');

Route::get('/index-products-csv', [SearchSettingController::class, 'indexProductsFromCsv'])
	->name('productsearch.indexProductsCsv');

Route::get('/index-pages', [SearchSettingController::class, 'indexPages'])
	->name('productsearch.indexPages');
});
