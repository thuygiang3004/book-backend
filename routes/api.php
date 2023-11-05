<?php

use App\Models\Book;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/books', [\App\Http\Controllers\BookController::class, 'index']);

Route::get('/book/{id}', function ($id){
   return new \App\Http\Resources\BookResource(Book::findOrFail($id));
});

Route::post('book', [\App\Http\Controllers\BookController::class, 'store']);
Route::put('book/{id}', [\App\Http\Controllers\BookController::class, 'update']);
Route::delete('book/{id}', [\App\Http\Controllers\BookController::class, 'destroy']);

Route::get('listings', [\App\Http\Controllers\ListingController::class, 'index']);
//
Route::get('/listing/{id}', function ($id){
    return new \App\Http\Resources\ListingResource(Listing::findOrFail($id));
});

Route::post('listing', [\App\Http\Controllers\ListingController::class, 'store']);
Route::put('listing/{id}', [\App\Http\Controllers\ListingController::class, 'update']);
Route::delete('listing/{id}', [\App\Http\Controllers\ListingController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
