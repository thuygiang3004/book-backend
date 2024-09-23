<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    private $columns = [
        ['field' => 'title', 'name' => 'Title'],
        ['field' => 'author', 'name' => 'Author'],
        ['field' => 'publisher', 'name' => 'Publisher']
    ];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Book::query()->select(collect($this->columns)->pluck('name')->toArray())->get();
    }

//    public function view(): \Illuminate\Contracts\View\View
//    {
//        return view('books.download', [
//            'books' => Book::all()
//        ]);
//    }

    public function headings(): array
    {
        return collect($this->columns)->pluck('name')->toArray();
    }
}
