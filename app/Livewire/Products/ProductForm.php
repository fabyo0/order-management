<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Number;
use Livewire\Component;
use Masmerise\Toaster\Toastable;

class ProductForm extends Component
{
    use Toastable;

    public ?Product $product = null;

    public string $name = '';

    public string $description = '';

    public ?float $price;

    public ?int $country_id;

    public bool $editing = false;

    public array $categories = [];

    public array $listsForFields = [];

    // Validate Property
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'price' => ['required'],
            'categories' => ['required', 'array'],
        ];
    }

    public function mount(Product $product)
    {
        $this->initListsForFields();

        // Product exits
        if (!is_null($this->product)) {
            $this->editing = true;
            $this->product = $product;
            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->price = number_format($this->product->price / 100, 2);
            $this->country_id = $this->product->country_id;
            $this->categories = $this->product->categories()->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $this->validate();

        // Create product
        if (is_null($this->product)) {
            $this->product = Product::create(
                array_merge(
                    $this->only('name', 'description', 'country_id'),
                    ['price' => $this->price * 100]
                )
            );
            $this->reset('name','description','country_id');

            $this->success('Product created successfully 🤙');

        } else {
            // Update product
            $this->product->update(
                array_merge(
                    $this->only('name', 'description', 'country_id'),
                    ['price' => $this->price * 100]
                ));

            $this->success('Product created successfully 🤙');

            return Redirect::route('products.index');
        }
        // Pivot table sync categories
        $this->product->categories()->sync($this->categories);
    }

    public function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::active()->pluck('name', 'id')->toArray();
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
