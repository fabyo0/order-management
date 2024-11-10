<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Number;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

readonly class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $ordersID)
    {
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Product',
            'Order Date',
            'SubTotal',
            'Taxes',
            'Total'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (!empty($this->ordersID)) {
            return Order::with('users:id,name', 'products')->find($this->ordersID);
        }

        return Order::with('products', 'users:id,name')->get();
    }

    public function map($row): array
    {
        return [
            $row->users->name,
            $row->products->pluck('name')->join(', '),
            $row->order_date->format('d/m/Y'),
            Number::currency($row->subtotal, 'USD'),
            Number::currency($row->taxes, 'USD'),
            Number::currency($row->total, 'USD'),
        ];
    }
}
