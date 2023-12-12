<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenLibraryImportTest extends TestCase
{
    public function test_http_client_get(): void
    {
        $response = Http::get('http://127.0.0.1:8000/api/listings');

        $this->assertEquals(200, $response->status());
        $data = $response->json();
        $this->assertIsArray($data);
//        $this->assertCount(12, $data);
        foreach ($data as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('price', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('book_id', $item);
        }

    }

    public function test_get_a_book_list_with_author(): void
    {
        $books = $this->get(route('books.import'));
        $books->assertJsonStructure([
            '*' => [
                'title',
                'authors',
            ],
        ]);
        //        dd($books->json());
    }
}
