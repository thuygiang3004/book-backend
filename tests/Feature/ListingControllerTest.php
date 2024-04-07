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

    #[Test]
    public function it_returns_listings(): void
    {
        $listing = Listing::factory()->hasBooks(2)->create();

        $response = $this->get(route('listings.index'))->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'books',
                    'price',
                    'status',
                    'images',
                ]
            ]
        ])->assertJsonFragment([
            'id' => $listing->id,
            'title' => $listing->title,
            'price' => $listing->price,
            'status' => $listing->status,
            'images' => $listing->images,
        ]);

        $this->assertEquals($listing->books->load('comments')->toArray(), $response->json('data.0.books'));
    }

    #[Test]
    public function it_can_create_a_listing_if_logged_in(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $postData = [
            'title' => 'my title',
            'books' => [$book->id],
            'price' => 100,
            'status' => 'new',
        ];
        $response = $this->actingAs($user)->post(route('listing.store'), $postData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('listings', Arr::except($postData, 'books'));

        $this->assertDatabaseHas('book_listing', [
            'book_id' => $book->id,
            'listing_id' => $response->json('listing.id'),
        ]);
    }

    #[Test]
    public function it_redirect_to_login_route_if_not_logged_in(): void
    {
        $book = Book::factory()->create();
        $postData = [
            'title' => 'my title',
            'books' => [$book->id],
            'price' => 100,
            'status' => 'new',
        ];
        $response = $this->post(route('listing.store'), $postData);
        $response->assertRedirectToRoute('login');
    }

    #[Test]
    public function it_shows_a_listing(): void
    {
        $listing = Listing::factory()->hasBooks()->hasComments()->create();
        $response = $this->get(route('listing.show', $listing));

        $response->assertJsonFragment([
            'id' => $listing->id,
            'title' => $listing->title,
            'price' => $listing->price,
            'status' => $listing->status,
            'images' => $listing->images,
        ]);

        $this->assertEquals($listing->books->load('comments')->toArray(), $response->json('books'));
        $this->assertEquals($listing->comments->toArray(), $response->json('comments'));
    }

    #[Test]
    public function it_shows_not_found_error_if_listing_does_not_exist(): void
    {
        $response = $this->get(route('listing.show', 999));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Listing not found']);
    }
}
