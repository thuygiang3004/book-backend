<?php

namespace App\Http\Controllers;

use App\Filters\ByPrice;
use App\Filters\ByTitle;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Pipeline;

class ListingSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $pipes = [
            ByTitle::class,
            ByPrice::class,
        ];
        return Pipeline::send(Listing::query())
            ->through($pipes)
            ->thenReturn()
            ->paginate();
    }
}
