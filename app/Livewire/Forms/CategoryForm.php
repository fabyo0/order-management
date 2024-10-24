<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Masmerise\Toaster\Toastable;

class CategoryForm extends Form
{
    use Toastable;

    public ?Category $category;

    #[Validate('required|string|min:3')]
    public ?string $name;

    #[Validate('nullable|string')]
    public ?string $slug;

    public bool $showModal = false;

    public ?bool $isActive;

    public function createCategory()
    {
        Category::create($this->only(['name', 'slug']));

        $this->reset('showModal','name','slug');
    }
}
