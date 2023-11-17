<?php

use App\Models\Book;
use App\Models\Listing;
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
Route::get('books/import', [\App\Http\Controllers\BookController::class, 'import'])->name('books.import');

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

Route::post('user', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('listing', [\App\Http\Controllers\ListingController::class, 'store']);
    Route::put('listing/{id}', [\App\Http\Controllers\ListingController::class, 'update']);
    Route::delete('listing/{id}', [\App\Http\Controllers\ListingController::class, 'destroy']);
});
