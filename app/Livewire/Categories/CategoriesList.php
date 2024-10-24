<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.categories.categories-list',[
            'categories' => Category::select(['id','name','slug'])->paginate()
        ]);
    }
}
