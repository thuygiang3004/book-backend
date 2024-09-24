<?php

namespace App\Exports;

use App\Models\Book;
use App\Models\BooksExportConfig;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BooksExport implements FromCollection, WithHeadings
{
    private array $columns = [
        ['field' => 'title', 'name' => 'Title'],
        ['field' => 'author', 'name' => 'Author'],
        ['field' => 'publisher', 'name' => 'Publisher']
    ];

    public function collection(): Collection
    {
        $columnsOrder = BooksExportConfig::query()->first()->config;

        return Book::query()->select($columnsOrder['columnsOrder'])->get();
    }

    public function headings(): array
    {
        $columnsOrder = BooksExportConfig::query()->first()->config;

        $mappedColumns = collect($columnsOrder['columnsOrder'])->map(function ($item) {
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
