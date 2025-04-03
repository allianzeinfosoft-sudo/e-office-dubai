<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\Employee;

class ProductivityTargetForm extends Component{
    /**
     * Create a new component instance.
     */
    public $projects;
    public $projectTasks;
    public $employees;
    public $productivityTarget;
    public $method;
    public $action;

    public function __construct($action, $method = 'post', $productivityTarget = null)
    {
        //
        $this->productivityTarget = $productivityTarget;
        $this->projects = Project::all();
        $this->employees = Employee::all();
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.productivity-target-form');
    }
}
