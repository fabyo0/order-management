<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsLists extends Component
{
    use WithPagination;

    public ?array $categories = [];

    public ?array $countries = [];

    // Arama ve filtereleme propertylerini array olarak aldık
    public array $searchColumns = [
        'name' => '',
        'price' => ['', ''],
        'description' => '',
        'category_id' => 0,
        'country_id' => 0
    ];

    public function mount()
    {
//        ['id' => 'value']
        $this->categories = Category::pluck('name', 'id')->toArray();
        $this->countries = Country::pluck('name', 'id')->toArray();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $products = Product::query()
            ->select(['products.*', 'countries.id as countryId', 'countries.name as countryName'])
            ->join('countries', 'countries.id', '=', 'products.country_id')
            ->with('categories');

        foreach ($this->searchColumns as $column => $value) {
            if (!empty($value)) {
                // Price
                $products->when($column === 'price', function ($products) use ($value) {
                    if (is_numeric($value[0])) {
                        $products->where('products.price', '>=', $value[0] * 100);
                    }

                    if (is_numeric($value[0])) {
                        $products->where('products.price', '<=', $value[0] * 100);
                    }
                })
                    // Category
                    ->when($column == 'category_id', fn($products) => $products->whereRelation('categories', 'id', $value))
                    // Country
                    ->when($column == 'country_id', fn($products) => $products->whereRelation('country', 'id', $value))
                    // Product Search
                    ->when($column == 'name', fn($products) => $products->where('products.' . $column, 'LIKE', '%' . $value . '%'));
            }
        }

        return view('livewire.products.products-lists', [
            'products' => $products->paginate(),
        ]);
    }
}
