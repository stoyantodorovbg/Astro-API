<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ApiTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/get-data', [DataController::class, 'getData'])->middleware('check-api-token');
Route::get('/get-api-token', [ApiTokenController::class, 'getApiToken'])->middleware(['domain-filter', 'get-api-token']);
