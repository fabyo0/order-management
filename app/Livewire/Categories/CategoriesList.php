<?php

namespace App\Livewire\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toastable;

class CategoriesList extends Component
{
    use WithPagination;
    use Toastable;

    public CategoryForm $categoryForm;

    public ?array $active;

    public function save(): void
    {
        $this->validate();
        $this->categoryForm->createCategory();
        $this->success('Category created successfully  🤙');
    }

    //TODO: update hooks dinleyerek slugable işlemini yaptık
    public function updatedSlug(): void
    {
        $this->categoryForm->slug = Str::slug($this->categoryForm->name);
    }

    public function openModal(): void
    {
        $this->categoryForm->showModal = true;
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


    public function render()
    {
        $categories = Category::select(['id', 'name', 'slug','is_active'])
            ->orderByDesc('created_at')
            ->paginate();

        //TODO: category id ile is_active(key-value) olarak array döndürür
        $this->active = $categories->mapWithKeys(
            fn(Category $item) => [$item['id'] => (bool)$item['is_active']]
        )->toArray();

        return view('livewire.categories.categories-list', [
            'categories' => $categories,
        ]);
    }
}
