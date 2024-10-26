<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

readonly class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $productIDs) {}

    public function headings(): array
    {
        return [
            'Name',
            'Categories',
            'Country',
            'Price',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->categories->pluck('name', 'id'),
            $row->country->name,
            Number::currency($row->price, in: 'USD'),
        ];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        if (! empty($this->productIDs)) {
            return Product::with('categories:id,name', 'country:id,name')->find($this->productIDs);
        }

        return Product::with('categories', 'country')->get();
    }
}
