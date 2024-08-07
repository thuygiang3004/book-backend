<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $listing = Listing::query()->findOrFail($request->input('listing'));
        $listing->comments()->create([
            'content' => $request->input('comment'),
            'user_id' => auth()->user()->id
        ]);
        return response()->json([], Response::HTTP_CREATED);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->user()->id) {
            return response()->json([], Response::HTTP_FORBIDDEN);
        }

        $comment->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
