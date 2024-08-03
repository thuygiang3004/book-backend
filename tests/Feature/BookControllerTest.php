<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

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
