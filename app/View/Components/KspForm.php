<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Employee;
use App\Models\KspCategory;

class KspForm extends Component
{
    public $createdBy;
    public $category;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->createdBy = Employee::all();
        $this->category = KspCategory::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.ksp-form');
    }
}
