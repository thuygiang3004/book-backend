<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Book::query()->select('title', 'author', 'publisher')->get();
    }

//    public function view(): \Illuminate\Contracts\View\View
//    {
//        return view('books.download', [
//            'books' => Book::all()
//        ]);
//    }

    public function headings(): array
    {
        return ['title', 'author', 'publisher'];
    }
}
