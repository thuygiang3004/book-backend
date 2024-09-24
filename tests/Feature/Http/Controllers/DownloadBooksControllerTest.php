<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Book;
use App\Models\BooksExportConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DownloadBooksControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itWillDownloadBooksIntoAnExcelFile()
    {
        Excel::fake();
        BooksExportConfig::factory()->create(['config' => ['columnsOrder' => ['author', 'publisher', 'title']]]);
        Book::factory(3)->create();
        $this->get(route('downloadBooks'))->assertStatus(200);
        Excel::assertDownloaded('books_downloaded.xlsx');
    }
}
