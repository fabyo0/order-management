<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsLists extends Component
{
    use WithPagination;

    #[Layout('layouts.app')]
    public function render()
    {
        $products = Product::with('country:id,name','categories:id,name')
            ->select('id', 'name', 'country_id', 'price', 'created_at')
            ->orderByDesc('created_at')
            ->paginate();

        return view('livewire.products.products-lists',[
            'products' => $products
        ]);
    }
}
