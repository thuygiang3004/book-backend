<?php

namespace App\Http\Controllers;

use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingMeilisearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->query();
        $listings = Listing::search($query['title'])->get();
        return ListingResource::collection($listings);
    }
}
