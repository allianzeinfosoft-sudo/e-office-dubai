<?php

namespace App\View\Components;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Feedback;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeedbackAssignForm extends Component
{
    public $feedbacks;
    public $employees;
    public $departments;

    public function __construct()
    {
        if(auth()->user()->hasAnyRole(['G4','G3']))
        {
            $user_department = auth()->user()->employee?->department_id;
            $this->feedbacks = Feedback::where('department_id',$user_department)->get();
            $this->employees = Employee::where('department_id',$user_department)->get();
        }
        elseif(auth()->user()->hasAnyRole(['Developer','HR','G1','G2']))
        {
            $this->departments = Department::get();
            $this->feedbacks = Feedback::get();
            $this->employees = Employee::get();
        }
        else
        {
            $this->departments = collect();
            $this->feedbacks = collect();
            $this->employees = collect();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.feedback-assign-form');
    }
}
