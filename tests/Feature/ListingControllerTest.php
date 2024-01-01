<?php

namespace Tests\Feature;

use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function tests_it_returns_listings(): void
    {
        $listing = Listing::factory()->hasBooks(2)->create();

        $response = $this->get('/api/listings')->assertStatus(200);
        $response->assertJson([
            [
                'id' => $listing->id,
                'title' => $listing->title,
                'books' => $listing->books->toArray(),
                'price' => $listing->price,
                'status' => $listing->status,
                'images' => $listing->images,
            ]
        ]);
    }
}
