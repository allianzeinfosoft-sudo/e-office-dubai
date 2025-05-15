<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Department;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Tasks;

class ProjectTaskForm extends Component{

    public $action;
    public $method;
    public $projectTask;
    public $reportingTo;
    public $departments;
    public $projects;
    public $form_id;
    public $tasks;

    /**
     * Create a new component instance.
     */
    public function __construct($action, $method = 'post', $form_id = null, $projectTask = null)
    {
        $this->form_id      = $form_id;
        $this->action       = $action;
        $this->method       = $method;
        $this->projectTask  = $projectTask;
        $reportingToIds     = Employee::whereNotNull('reporting_to') ->distinct()->pluck('reporting_to');
        $this->reportingTo  = Employee::whereIn('user_id', $reportingToIds)->get();
        $this->departments  = Department::all();
        $this->projects     = Project::all();
        $this->tasks        = Tasks::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.project-task-form');
    }
}
