<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
    public function it_only_allows_users_with_permissions_to_store_a_book()
    {
        $user = User::factory()->create();

        $book = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'publisher' => 'Test Publisher',
        ];

        $this->actingAs($user)
            ->postJson(route('books.store', $book))
            ->assertForbidden();
    }

    #[Test]
    public function it_stores_a_book()
    {
        //Create superAdmin role
        $role = Role::create(['name' => 'super-admin']);
        $permission = Permission::create(['name' => 'edit books']);
        $role->givePermissionTo($permission);

//        Create super admin user
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super-admin');

        $book = [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'publisher' => 'Test Publisher',
        ];

        $this->actingAs($superAdmin)
            ->postJson(route('books.store', $book))
            ->assertOk();

        $this->assertDatabaseHas('books', $book);
    }
}
