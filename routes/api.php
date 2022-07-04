<?php

use App\Http\Controllers\ChooseController;
use App\Http\Controllers\PollingController;
use App\Http\Controllers\UserController;
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

Route::post("/v1/users/login", [UserController::class, "login"]);

Route::group(["middleware" => "jwt.verify"], function(){
    Route::post("/v1/pollings", [PollingController::class, "store"]);
    
});
Route::group(["middleware" => "jwt.verify.admin"], function(){
    Route::get("/v1/pollings", [PollingController::class, "index"]);
    Route::get("/v1/pollings/{id}", [PollingController::class, "show"]);
    Route::delete("/v1/pollings/{id}", [PollingController::class, "destroy"]);
    Route::put("/v1/pollings/{id}", [PollingController::class, "update"]);
    
    Route::post("/v1/users", [UserController::class, "store"]);
    Route::get("/v1/users", [UserController::class, "index"]);
    Route::get("/v1/users/{id}", [UserController::class, "show"]);
    Route::delete("/v1/users/{id}", [UserController::class, "destroy"]);
    Route::put("/v1/users/{id}", [UserController::class, "update"]);
    
    Route::post("/v1/chooses", [ChooseController::class, "store"]);
});