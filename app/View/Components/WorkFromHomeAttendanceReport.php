<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Employee;
use App\Models\Project;



class WorkFromHomeAttendanceReport extends Component{

    public $employees;
    public $projects;
    public $type;

    /**
     * Create a new component instance.
     */
    public function __construct($type = 'wfh')
    {
        //
        $this->employees = Employee::all();
        $this->projects = Project::all();
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.work-from-home-attendance-report');
    }
}
