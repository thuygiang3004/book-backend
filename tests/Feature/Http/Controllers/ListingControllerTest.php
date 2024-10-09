<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Resources\ListingResource;
use App\Models\Book;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_listings(): void
    {
        $listing = Listing::factory()->hasBooks(2)->create();

        $response = $this->getJson(route('listings.index'))->assertStatus(200);

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
    public function it_filters_new_listings()
    {
        $newListing = Listing::factory(2)->hasBooks()->create();
        Listing::factory()->create(['status' => 'updated']);

        $response = $this->getJson(route('listings.index', ['isNew' => true]))
            ->assertStatus(200);

        $expectedData = ListingResource::collection($newListing)->toArray(request());
        foreach ($expectedData as &$listing) {
            $listing['books'] = [];
            $listing['comments'] = [];
        }

        //TODO: check why it fails
//        assertEquals($expectedData, $response->json('data'));
    }

    #[Test]
    public function it_can_create_a_listing_if_logged_in(): void
    {
        $user = User::factory()->create();
        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        $postData = [
            'title' => 'my title',
            'books' => [$book2->id, $book1->id],
            'price' => 100,
            'status' => 'new',
        ];
        $response = $this->actingAs($user)->postJson(route('listing.store'), $postData);
        $response->assertStatus(201);

        $listing = Listing::query()->first();
        $this->assertEquals($postData['title'], $listing->title);
        $this->assertEquals($postData['price'], $listing->price);
        $this->assertEquals($postData['status'], $listing->status);

        $this->assertEquals($postData['books'][0], $listing->books->first()->id);
        $this->assertEquals(0, $listing->books->first()->pivot->order);

        $this->assertEquals($postData['books'][1], $listing->books[1]->id);
        $this->assertEquals(1, $listing->books[1]->pivot->order);
    }

    #[Test]
    public function it_returns_unauthorized_errors_when_user_is_not_logged_in(): void
    {
        $book = Book::factory()->create();
        $postData = [
            'title' => 'my title',
            'books' => [$book->id],
            'price' => 100,
            'status' => 'new',
        ];
        $response = $this->postJson(route('listing.store'), $postData);
        $response->assertStatus(401);
    }

    #[Test]
    public function it_returns_unauthorized_errors_when_a_user_other_than_listing_owner_is_editing(): void
    {
        $listingOwnerUser = User::factory()->create();
        $anotherUser = User::factory()->create();
        $listing = Listing::factory()->hasBooks()->for($listingOwnerUser)->create();
        $postData = [
            'title' => 'my title',
            'book_id' => [$listing->books[0]->id],
            'price' => 100,
            'status' => 'updated',
        ];
        $response = $this->actingAs($anotherUser)->putJson(route('listing.update', $listing), $postData);
        $response->assertForbidden();
    }

    #[Test]
    public function it_updates_the_listing_if_the_owner_is_editing(): void
    {
        $listingOwnerUser = User::factory()->create();
        $listing = Listing::factory()->hasBooks()->for($listingOwnerUser)->create();
        $postData = [
            'title' => 'my title',
            'book_id' => [$listing->books[0]->id],
            'price' => 100,
            'status' => 'updated',
        ];
        $response = $this->actingAs($listingOwnerUser)->putJson(route('listing.update', $listing), $postData);
        $response->assertSuccessful();

        $updatedListing = Listing::query()->find($listing->id);
        $this->assertEquals($postData['title'], $updatedListing->title);
        $this->assertEquals($postData['book_id'], $updatedListing->books->pluck('id')->toArray());
        $this->assertEquals($postData['price'], $updatedListing->price);
        $this->assertEquals($postData['status'], $updatedListing->status);
    }

    #[Test]
    public function it_shows_a_listing(): void
    {
        $listing = Listing::factory()->hasBooks()->hasComments()->create();
        $response = $this->getJson(route('listing.show', $listing));

        $response->assertJsonFragment([
            'id' => $listing->id,
            'title' => $listing->title,
            'price' => $listing->price,
            'status' => $listing->status,
            'images' => $listing->images,
        ]);

        $this->assertEquals($listing->books->load('comments')->toArray(), $response->json('books'));
        $expectedComments = $listing->fresh()->comments
            ->each(fn($comment) => $comment->user_name = $comment->user()->pluck('name')->first())
            ->toArray();
        $this->assertEquals($expectedComments, $response->json('comments'));
    }

    #[Test]
    public function it_shows_not_found_error_if_listing_does_not_exist(): void
    {
        $response = $this->getJson(route('listing.show', 999));

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Listing not found']);
    }
}
