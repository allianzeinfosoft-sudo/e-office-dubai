<?php

namespace App\View\Components;

use App\Models\Department;
use App\Models\Employee;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TrainingForm extends Component
{
    /**
     * Create a new component instance.
     */
    public $departments;
    public $employees;
    public function __construct()
    {
        $this->departments = Department::get();
        $this->employees = Employee::with('user')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.training-form');
    }
}
