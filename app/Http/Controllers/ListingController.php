<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Listing::all();

        return $listings->map(function ($listing) {
            return [
                'id' => $listing->id,
                'title' => $listing->title,
                'price' => $listing->price,
                'status' => $listing->status,
                'books' => $listing->books,
            ];
        });
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
        $validated = $request->validate([
            'title' => 'required',
            'books' => 'required|array',
            'price' => 'required',
            'status' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
        ]);

        if ($request['image']) {
            $imageName = uniqid() . '.' . $request->image->extension();
            $request->image->storeAs('images', $imageName, 'public');
            $validated['images'] = 'images/' . $imageName;
        }

        $listing = $request->user()->listings()->create($validated);
        $listing->books()->attach($validated['books']);

        return response()->json([
            'success' => true,
            'message' => 'Listing created',
            'listing' => $listing,
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

        if (!$request->user()->is($listing->user)) {
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
            'listing' => $listing,
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
                'message' => 'Listing not found',
            ], 404);
        }

        // Check if the authenticated user is the owner of the listing
        if (!$request->user()->is($listing->user)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to delete this listing.',
            ], 403);
        }

        $listing->delete();

        return response()->json([
            'message' => 'Listing deleted successfully',
        ], 200);
    }

    public function sendError($message, $data = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
