<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DownloadBooksController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingMeilisearchController;
use App\Http\Controllers\ListingSearchController;
use App\Http\Resources\BookResource;
use App\Models\Book;
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

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('books/import', [BookController::class, 'import'])->name('books.import');
Route::get('books/download', [DownloadBooksController::class, 'index'])->name('downloadBooks');

Route::get('/book/{id}', function ($id) {
    return new BookResource(Book::findOrFail($id));
});


Route::get('listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('listings/search', ListingSearchController::class)->name('listings.search');
Route::get('listings/search-ms', ListingMeilisearchController::class)->name('listings.search-ms');
Route::get('/listing/{listing}', [ListingController::class, 'show'])->name('listing.show');

Route::post('user', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('book', [BookController::class, 'store'])->name('books.store');
    Route::put('book/{id}', [BookController::class, 'update'])->name('book.update');
    Route::delete('book/{id}', [BookController::class, 'destroy'])->name('book.destroy');

    Route::post('listing', [ListingController::class, 'store'])->name('listing.store');
    Route::put('listing/{id}', [ListingController::class, 'update'])->name('listing.update');
    Route::delete('listing/{id}', [ListingController::class, 'destroy']);

    Route::resource('comment', CommentController::class)->only(['store', 'destroy']);
});
