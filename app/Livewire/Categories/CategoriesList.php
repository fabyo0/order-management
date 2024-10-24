<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toastable;

class CategoriesList extends Component
{
    use WithPagination;
    use Toastable;

    public Category $category;

    public bool $showModal = false;

    public int $editedCategoryId = 0;

    #[Validate('required|string|min:3')]
    public ?string $name;

    #[Validate('nullable|string')]
    public ?string $slug;

    public Collection $categories;

    public ?array $active;

    public int $currentPage = 1;

    public int $perPage = 10;

    public function save(): void
    {
        $this->validate();
        $this->category->createCategory();
        $this->success('Category created successfully  🤙');
        $this->reset('showModal');
    }


    //TODO: update hooks dinleyerek slugable işlemini yaptık
    public function updatedSlug(): void
    {
        $this->category->slug = Str::slug($this->category->name);
    }

    public function openModal(): void
    {
        $this->category->showModal = true;
    }

    public function toggleIsActive($categoryId): void
    {
        $category = Category::findOrFail($categoryId);
        if ($category) {
            $category->is_active = $this->active[$categoryId];
            $category->update();
        } else {
            $this->error('Category not found');
        }
    }

    public function updateOrder($list): void
    {
        //value => category.id
        //order => target.id
        foreach ($list as $item) {
            $category = $this->categories->firstWhere('id', $item['value']);

            $order = $item['order'] + (($this->currentPage) - 1) * $this->perPage;

            // Position check
            if ($category['position'] != $order) {
                // Position update $item['order']
                Category::where('id', $item['value'])->update(['position' => $order]);
            }
        }
    }


    public function render()
    {
        $cats = Category::orderBy('position')->paginate(10);

        $links = $cats->links();

        //Collect paginated data
        $this->categories = collect($cats->items());

        //TODO: category id ile is_active(key-value) olarak array döndürür
        $this->active = $this->categories->mapWithKeys(
            fn (Category $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.categories.categories-list', [
            'links' => $links
        ]);
    }
}
