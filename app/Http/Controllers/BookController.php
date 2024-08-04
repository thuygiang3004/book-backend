<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

/**
 * @method sendError(string $string, \Illuminate\Support\MessageBag $errors)
 */
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BookResource::collection(Book::all());
    }

    public function import()
    {
        $books = $this->getBooksFromOpenLibrary();
        $this->createBookInDB($books);

        return $books;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }
        $book = Book::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Book created',
            'book' => $book,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (Book::where('id', $id)->exists()) {
            $book = Book::find($id);

            // Check if the title is present in the request
            if ($request->has('title')) {
                $book->title = $request->title;
            }

            // Update other fields if they are present in the request
            if ($request->has('author')) {
                $book->author = $request->author;
            }

            if ($request->has('publisher')) {
                $book->publisher = $request->publisher;
            }

            $book->save();

            return response()->json([
                'message' => 'Book updated',
            ], 200);
        }

        return response()->json([
            'message' => 'Book not found',
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Book::where('id', $id)->exists()) {
            $book = Book::find($id);
            $book->delete();

            return response()->json([
                'message' => 'Book deleted',
            ], 200);
        }

        return response()->json([
            'message' => 'Book not found',
        ], 404);
    }

    public function getBooksFromOpenLibrary(): mixed
    {
        $books = Http::get('https://openlibrary.org/subjects/culture.json?published_in=1500-2020')->json()['works'];

        return $books;
    }

    public function createBookInDB(mixed $books): void
    {
        foreach (collect($books) as $bookData) {
            $existingBook = Book::where('title', $bookData['title'])->first();

            if ($existingBook) {
                $existingBook->title = $bookData['title'];
                $existingBook->author = $bookData['authors'][0]['name'] ?? null;
                $existingBook->save();
            } else {
                Book::create([
                    'title' => $bookData['title'],
                    'author' => $bookData['authors'][0]['name'] ?? null,
                    'publisher' => 'unknown',
                ]);
            }
        }
    }
}
