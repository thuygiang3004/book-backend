<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $listing = Listing::query()->findOrFail($request->input('listing'));
        $listing->comments()->create(['content' => $request->input('comment')]);
        return response()->json([], Response::HTTP_CREATED);
    }
}
