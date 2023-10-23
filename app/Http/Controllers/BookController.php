<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
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
        return Book::all();
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
        $input = $request -> all();
        $validator = Validator::make($input, [
            'title'=>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());
        }
        $book = Book::create($input);
        return response()->json([
           'success' => true,
            'message' => 'Book created',
            'book' => $book
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
        if(Book::where('id', $id)->exists()){
            $book = Book::find($id);
            $book->title = $request->title;
            $book->author = $request->author;
            $book->publisher = $request->publisher;
            $book->save();
            return response()->json([
                'message' => 'Book updated'
            ], 200);
        }
        return response()->json([
            'message' => 'Book not found'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Book::where('id', $id)->exists()){
            $book = Book::find($id);
            $book->delete();
            return response()->json([
                'message' => 'Book deleted'
            ], 200);
        }
        return response()->json([
            'message' => 'Book not found'
        ], 404);
    }
}
