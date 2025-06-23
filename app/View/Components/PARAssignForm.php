<?php

namespace App\View\Components;

use App\Models\Department;
use App\Models\Employee;
use App\Models\ParTemplate;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PARAssignForm extends Component
{
    public $templates;
    public $employees;
    public $departments;

    public function __construct()
    {
        if(auth()->user()->hasAnyRole(['G4','G3']))
        {
            $user_department = auth()->user()->employee?->department_id;
            $this->templates = ParTemplate::where('department_id',$user_department)->get();
            $this->employees = Employee::where('department_id',$user_department)->get();
        }
        elseif(auth()->user()->hasAnyRole(['Developer','HR','G1','G2']))
        {
            $this->departments = Department::get();
            $this->templates = ParTemplate::get();
            $this->employees = Employee::get();
        }
        else
        {
            $this->departments = collect();
            $this->templates = collect();
            $this->employees = collect();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.p-a-r-assign-form');
    }
}
