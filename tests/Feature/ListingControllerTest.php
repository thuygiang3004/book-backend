<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    #[Test]
    public function it_returns_listings(): void
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

    #[Test]
    public function it_can_create_a_listing()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $postData = [
            'title' => 'my title',
            'books' => [$book->id],
            'price' => 100,
            'status' => 'new',
        ];
        $response = $this->actingAs($user)->post('/api/listing', $postData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('listings', Arr::except($postData, 'books'));

        $this->assertDatabaseHas('book_listing', [
            'book_id' => $book->id,
            'listing_id' => $response->json('listing.id'),
        ]);
    }
}
