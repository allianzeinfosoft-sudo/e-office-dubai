<?php

namespace App\View\Components;

use App\Models\Employee;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserJobForm extends Component
{
    public $employees;
    public function __construct()
    {
        $this->employees = Employee::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.user-job-form');
    }
}
