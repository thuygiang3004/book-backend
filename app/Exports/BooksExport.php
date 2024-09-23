<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    private array $columns = [
        ['field' => 'title', 'name' => 'Title'],
        ['field' => 'author', 'name' => 'Author'],
        ['field' => 'publisher', 'name' => 'Publisher']
    ];

    private $columnsOrder = ['author', 'publisher', 'title'];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Book::query()->select($this->columnsOrder)->get();
    }

    public function headings(): array
    {
        $mappedColumns = collect($this->columnsOrder)->map(function ($item) {
            return collect($this->columns)->firstWhere('field', $item);
        });

        return $mappedColumns->pluck('name')->toArray();
    }

//    public function view(): \Illuminate\Contracts\View\View
//    {
//        return view('books.download', [
//            'books' => Book::all()
//        ]);
//    }

}
