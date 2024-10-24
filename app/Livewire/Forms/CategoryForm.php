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
        $position = Category::max('position') + 1;
        Category::create(array_merge($this->only('name', 'slug'), ['position' => $position]));
    }
}
