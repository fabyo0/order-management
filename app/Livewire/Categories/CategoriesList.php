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
        $this->categoryForm->slug = Str::slug($this->name);
    }

    public function openModal(): void
    {
        $this->categoryForm->showModal = true;
    }

    public function render()
    {
        return view('livewire.categories.categories-list', [
            'categories' => Category::select(['id', 'name', 'slug'])
                ->orderByDesc('created_at')
                ->paginate()
        ]);
    }
}
