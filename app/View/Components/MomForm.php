<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Employee;

class MomForm extends Component
{
    public $createdBy;
    public $assignedTo;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->createdBy = Employee::all();
        $this->assignedTo = Employee::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.mom-form');
    }
}
