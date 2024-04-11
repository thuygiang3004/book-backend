<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        return response()->json([], Response::HTTP_CREATED);
    }
}
