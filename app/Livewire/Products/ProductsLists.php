<?php

namespace App\Livewire\Products;

use App\Exports\ProductsExport;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toastable;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductsLists extends Component
{
    use WithPagination;
    use Toastable;

    public ?array $categories = [];

    public ?array $countries = [];

    public ?array $selected = [];

    // Arama ve filtereleme propertylerini array olarak aldık
    public array $searchColumns = [
        'name' => '',
        'price' => ['', ''],
        'description' => '',
        'category_id' => 0,
        'country_id' => 0
    ];

    #[Url(history: true)]
    public string $sortColumn = 'products.name';

    #[Url(history: true)]
    public string $sortDirection = 'asc';

    public function mount()
    {
//        ['id' => 'value']
        $this->categories = Category::pluck('name', 'id')->toArray();
        $this->countries = Country::pluck('name', 'id')->toArray();
    }

    public function sortByColumn(string $column): void
    {
        if ($this->searchColumns == $column) {
            $this->sortColumn = $this->sortDirection ? 'desc' : 'asc';
        } else {
            $this->reset('sortColumn');
            $this->sortColumn = $column;
        }
    }

    #[On('delete')]
    public function delete(Product $product): void
    {
        $product->delete();
        $this->success('Product deleted successfully  🤙');
    }

    public function deleteConfirm(string $method, $id = null): void
    {
        //TODO: Tüm confirm işlemleri için event tetiklenecek
        $this->dispatch('swal:confirm', [
            'type'  => 'warning',
            'title' => 'Are you sure?',
            'text'  => '',
            'id'    => $id,
            'method' => $method,
        ]);
    }

    #[On('deleteSelected')]
    public function deleteSelected(): void
    {
        $products = Product::whereIn('id',$this->selected)->get();
        $products->each->delete();
        $this->success('Selected products deleted 🤙');
        $this->reset('selected');
    }

    //TODO: check todo count
    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    // export pdf/csv/xlsx
    public function export(string $format): BinaryFileResponse
    {
        // Check format
        abort_if(!in_array($format,['csv', 'xlsx', 'pdf']),Response::HTTP_NOT_FOUND);
        // Download file
        return Excel::download(new ProductsExport($this->selected), 'products.' . $format);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $products = Product::query()
            ->select(['products.*', 'countries.id as countryId', 'countries.name as countryName'])
            ->join('countries', 'countries.id', '=', 'products.country_id')
            ->with('categories:id,name')
            ->filterByName($this->searchColumns['name'])
            ->filterByPrice($this->searchColumns['price'][0], $this->searchColumns['price'][1])
            ->filterByCategory($this->searchColumns['category_id'])
            ->filterByCountry($this->searchColumns['country_id'])
            ->orderBy($this->sortColumn, $this->sortDirection);

        return view('livewire.products.products-lists', [
            'products' => $products->paginate()
        ]);
    }
}
