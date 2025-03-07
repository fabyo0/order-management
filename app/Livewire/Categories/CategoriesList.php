<?php

namespace App\Livewire\Categories;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toastable;

class CategoriesList extends Component
{
    use Toastable;
    use WithPagination;

    public ?Category $category = null;

    public CategoryForm $form;

    public bool $showModal = false;

    public int $editedCategoryId = 0;

    #[Validate('required|string|min:3')]
    public ?string $name = '';

    #[Validate('nullable|string')]
    public ?string $slug = '';

    public ?Collection $categories;

    public ?array $active;

    public int $currentPage = 1;

    public int $perPage = 10;

    public function save(): void
    {
        //TODO: category boş olmasa bile null olarak dönüyor
        if (is_null($this->category)) {
            $position = Category::max('position') + 1;
            Category::create(array_merge($this->only('name', 'slug'), ['position' => $position]));
            $this->success('Category created successfully  🤙');
        } else {
            $this->category->update($this->only('name', 'slug'));
            $this->success('Category updated successfully  🤙');
        }
        $this->reset('showModal', 'editedCategoryId');
        $this->resetValidation();
    }

    //TODO: update hooks dinleyerek slugable işlemini yaptık
    public function updatedSlug(): void
    {
        if (! $this->slug) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function openModal(): void
    {
        $this->showModal = true;
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

    public function editCategory($id): void
    {
        $this->editedCategoryId = $id;
        $category = Category::findOrFail($id);

        $this->name = $category->name;
        $this->slug = $category->slug;
    }

    //TODO: Güncelleme kontrolü null durumana göre yapılmalıdır
    public function updateCategory($id): void
    {
        $category = Category::findOrFail($id);
        $category->update($this->only('name', 'slug'));

        $this->resetValidation();
        $this->reset('showModal', 'editedCategoryId','name','slug');

        $this->success('Category updated successfully  🤙');
    }

    public function cancelCategoryEdit(): void
    {
        $this->resetValidation();
        $this->reset('editedCategoryId');
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

    public function deleteConfirm(string $method, $id = null): void
    {
        $this->dispatch('swal:confirm', [
            'type' => 'warning',
            'title' => 'Are you sure?',
            'text' => '',
            'id' => $id,
            'method' => $method,
        ]);
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        $this->success('Category deleted successfully  🤙');
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
            'links' => $links,
        ]);
    }
}
