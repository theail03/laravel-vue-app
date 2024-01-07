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
    Route::resource('/survey', \App\Http\Controllers\SurveyController::class);
    Route::resource('/matrix', \App\Http\Controllers\MatrixController::class);
    
    // Image routes

    // Route to retrieve all images for a specific matrix
    Route::get('/matrix/{matrix}/images', [ImageController::class, 'getImages']);

    // Route to retrieve a specific image
    Route::get('/matrix/{matrix}/image/{row}/{column}', [ImageController::class, 'getImage']);

    // Route to save (create or update) a specific image
    Route::put('/matrix/{matrix}/image/{row}/{column}', [ImageController::class, 'saveImage']);

    // Route to delete a specific image
    Route::delete('/matrix/{matrix}/image/{row}/{column}', [ImageController::class, 'deleteImage']);

    
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
});

Route::get('/survey-by-slug/{survey:slug}', [\App\Http\Controllers\SurveyController::class, 'showForGuest']);
Route::post('/survey/{survey}/answer', [\App\Http\Controllers\SurveyController::class, 'storeAnswer']);

Route::get('/user', [AuthController::class, 'getUser']);

Route::get('/google-auth/redirect', [AuthController::class, 'googleAuthRedirect']);
 
Route::get('/google-auth/callback', [AuthController::class, 'googleAuthCallback']);