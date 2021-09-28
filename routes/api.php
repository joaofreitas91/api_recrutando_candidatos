<?php

use App\Http\Controllers\api\CandidacyController;
use App\Http\Controllers\api\CandidateController;
use App\Http\Controllers\api\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/v1')->group(function () {

    Route::prefix('/vagas')->group(function () {
        Route::apiResource('/', JobController::class);
        Route::get('/{idJob}/candidaturas/ranking', [CandidacyController::class, 'ranking']);
    });
    Route::apiResource('pessoas', CandidateController::class);
    Route::apiResource('vagas', JobController::class);
    Route::apiResource('candidaturas', CandidacyController::class);
});
