<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;
use App\Models\Department;
use App\Models\Tasks;
use App\Models\Employee;

class ProjectForm extends Component
{
    public $action;
    public $method;
    public $project;
    public $users;
    public $departments;
    public $tasks;
    public $reportingTo;
    

    public function __construct($action, $method = 'post', $project = null)
    {
        $this->action       = $action;
        $this->method       = $method;
        $this->project      = $project;
        $this->users        = User::all();
        $this->departments  = Department::all();
        $this->tasks        = Tasks::all();
        $reportingToIds     = Employee::whereNotNull('reporting_to') ->distinct()->pluck('reporting_to');
        $this->reportingTo  = Employee::whereIn('user_id', $reportingToIds)->get();
    }

    public function render()
    {
        return view('components.forms.project-form');
    }
}
