<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\BooksCategory;


class BooksCategoryForm extends Component
{
    public $parent_categories;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->parent_categories = BooksCategory::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.books-category-form');
    }
}
