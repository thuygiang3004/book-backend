<?php

namespace App\Http\Controllers;

use App\Events\ListingCreated;
use App\Filters\ByPrice;
use App\Filters\ByTitle;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;
use Illuminate\Support\Facades\Validator;

class ListingController extends Controller
{
    public function index()
    {
        $pipes = [
            ByTitle::class,
            ByPrice::class,
        ];
        $listings = Pipeline::send(Listing::query())
            ->through($pipes)
            ->thenReturn()
            ->paginate(10);

        return ListingResource::collection($listings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'books' => 'required|array',
            'books.*' => 'exists:books,id', //check book exists
            'price' => 'required',
            'status' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
        ]);

        if ($request['image']) {
            $imageName = uniqid() . '.' . $request->image->extension();
            $request->image->storeAs('images', $imageName, 'public');
            $validated['images'] = 'images/' . $imageName;
        } else $validated['images'] = null;

        $bookInput = [
            'title' => $validated['title'],
            'price' => $validated['price'],
            'status' => $validated['status'],
            'images' => $validated['images'],
        ];
        $booksWithOrder = collect($request->input('books'))
            ->map(function ($bookId, $index) {
                return [
                    'book_id' => $bookId,
                    'order' => $index,
                ];
            });

        $listing = $request->user()->listings()->create($bookInput);
        $listing->books()->attach($booksWithOrder);
        $listing->load('books');
        $listing->load('user');

        event(new ListingCreated($listing));

        return response()->json([
            'success' => true,
            'message' => 'Listing created',
            'listing' => $listing,
        ], 201);
    }

    public function show(string $id)
    {
        $listing = Listing::query()->find($id);
        if (!$listing) {
            return response()->json([
                'message' => 'Listing not found'
            ], 404);
        }
        return response()->json(
            ListingResource::make($listing)
        );
    }

    public function edit(string $id)
    {
        //
    }

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
