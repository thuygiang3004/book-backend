<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;

class DownloadBooksController extends Controller
{
    public function index()
    {
        return Excel::download(new BooksExport, 'books_downloaded.xlsx');
    }
}
