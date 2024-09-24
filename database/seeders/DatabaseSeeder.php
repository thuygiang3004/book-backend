<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\BooksExportConfig;
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
        Book::factory(20)->has(Listing::factory(2)->hasComments(2))->hasComments(3)->create();
        BooksExportConfig::factory()->create();
    }
}
