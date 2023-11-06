<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @method sendError(string $string, \Illuminate\Support\MessageBag $errors)
 */
class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Listing::all();
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
           'title' => 'required',
           'book_id' => 'required',
           'price' => 'required',
           'status' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());
        }
        $request->user()->listings()->create($input);
        return response()->json([
            'success' => true,
            'message' => 'Listing created',
            'listing' => $input
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
        if(Listing::where('id', $id)->exists()){
            $listing = Listing::find($id);
            $listing->title = $request->title;
            $listing->book_id = $request->book_id;
            $listing->price = $request->price;
            $listing->status = $request->status;
            $listing->save();
            return response()->json([
                'message' => 'Listing Updated'
            ], 200);
        }
        return response()->json([
            'message' => 'Listing not found'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(Listing::where('id', $id)->exists()){
            $listing = Listing::find($id);
            $listing->delete();
            return response()->json([
                'message' => 'Listing deleted'
            ], 200);
        }
        return response()->json([
            'message' => 'Listing not found'
        ], 404);
    }
}
