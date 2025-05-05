<?php

namespace App\View\Components;

use App\Models\Branch;
use App\Models\Department;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DepartmentForm extends Component
{
    /**
     * Create a new component instance.
     */
    public $branches;
    public $departments;

    public function __construct()
    {
        $this->branches = Branch::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.department-form');
    }
}
