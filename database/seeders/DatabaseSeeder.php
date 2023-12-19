<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        User::factory()->create(['email' => 'giang2@gmail.com']);
        Book::factory(20)->create();
        Listing::factory(10)->create();

        foreach (Listing::all() as $listing) {
            $books = Book::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $listing->books()->attach($books);
        }

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
