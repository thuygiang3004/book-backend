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
        ], 200);
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
        $listing = Listing::find($id);

        if (!$listing) {
            return $this->sendError('Listing not found');
        }

        if ($request->user()->id !== $listing->user_id) {
            return $this->sendError('Unauthorized');
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'book_id' => 'required',
            'price' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $listing->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Listing updated successfully',
            'listing' => $listing
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $listing = Listing::find($id);

        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }

        // Check if the authenticated user is the owner of the listing
        if ($request->user()->id !== $listing->user_id) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to delete this listing.'
            ], 403);
        }

        $listing->delete();

        return response()->json([
            'message' => 'Listing deleted successfully'
        ], 200);
    }
}
