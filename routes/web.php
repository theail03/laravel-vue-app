<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/matrix', \App\Http\Controllers\MatrixController::class)->only(['store', 'update', 'destroy']);
    Route::resource('/matrix', \App\Http\Controllers\MatrixController::class)->only(['index', 'show'])->withoutMiddleware('auth');
    
    // Route to retrieve all images for a specific matrix
    Route::get('/matrix/{matrix}/images', [ImageController::class, 'getImages'])->withoutMiddleware('auth');;

    // Route to retrieve a specific image
    Route::get('/matrix/{matrix}/image/{row}/{column}', [ImageController::class, 'getImage'])->withoutMiddleware('auth');;

    // Route to save (create or update) a specific image
    Route::put('/matrix/{matrix}/image/{row}/{column}', [ImageController::class, 'saveImage']);

    // Route to delete a specific image
    Route::delete('/matrix/{matrix}/image/{row}/{column}', [ImageController::class, 'deleteImage']);    
});

Route::get('/user', [AuthController::class, 'getUser']);
Route::get('/google-auth/redirect', [AuthController::class, 'googleAuthRedirect']);
Route::get('/google-auth/callback', [AuthController::class, 'googleAuthCallback']);
Route::get('/matrices/dashboard', [\App\Http\Controllers\DashboardController::class, 'getMatricesDashboard']);
