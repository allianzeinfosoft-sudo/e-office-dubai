<?php

namespace App\View\Components;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LeaveApprovalFrom extends Component
{
    /**
     * Create a new component instance.
     */
    public $users;
    public $departments;

    public function __construct()
    {
        $this->users = Employee::get();
        $this->departments = Department::get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.leave-approval-from');
    }
}
