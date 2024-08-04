<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_all_books()
    {
        $books = Book::factory()->count(2)->create();
        $response = $this->getJson(route('books.index'))
            ->assertOk();
        $this->assertCount(2, $books);
        $this->assertEquals($books->toArray(), $response->json()['data']);
    }
    #[Test]
    public function it_stores_a_book()
    {
        $user = User::factory()->create();

        $book = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'publisher' => 'Test Publisher',
        ];
        $this->actingAs($user)
            ->postJson(route('books.store', $book))
            ->assertOk();

        $this->assertDatabaseHas('books', $book);
    }
}
