<?php

use App\Models\Book;
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

Route::get('/books', function (){
   return \App\Http\Resources\BookResource::collection(Book::all());
});

Route::get('/book/{id}', function ($id){
   return new \App\Http\Resources\BookResource(Book::findOrFail($id));
});

Route::post('book', [\App\Http\Controllers\BookController::class, 'store']);
Route::put('book/{id}', [\App\Http\Controllers\BookController::class, 'update']);
Route::delete('book/{id}', [\App\Http\Controllers\BookController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
