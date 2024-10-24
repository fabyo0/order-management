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

    public function save()
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
        Category::where('id', $categoryId)->update([
            'is_active' => $this->categoryForm->isActive[$categoryId]
        ]);
    }


    public function render()
    {
        $categories = Category::select(['id', 'name', 'slug'])
            ->orderByDesc('created_at')
            ->paginate();

        //TODO: category id ile is_active(key-value) olarak array döndürür
        $this->categoryForm->isActive = $categories->mapWithKeys(
            fn(Category $item) => [$item['id'] => (bool)$item['is_active']]
        )->toArray();

        return view('livewire.categories.categories-list', [
            'categories' => $categories,
        ]);
    }
}
