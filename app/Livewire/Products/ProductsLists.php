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
            ->with('categories')
            ->filterByName($this->searchColumns['name'])
            ->filterByPrice($this->searchColumns['price'][0], $this->searchColumns['price'][1])
            ->filterByCategory($this->searchColumns['category_id'])
            ->filterByCountry($this->searchColumns['country_id'])
            ->paginate();

        return view('livewire.products.products-lists', [
            'products' => $products
        ]);
    }
}
