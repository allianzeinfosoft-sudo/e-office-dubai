<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Graduation;
use App\Models\MinimumQualification;
use App\Models\Designation;
use App\Models\Project;
use App\Models\Skills;
use App\Models\KeyworsRrf;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Workshift;

class RecuitmentForm extends Component
{
    public $action;
    public $graduations;
    public $minimumQualifications;
    public $positions;
    public $projects;
    public $skills;
    public $keywords;
    public $experiance;
    public $projectLeaders;
    public $branches;
    public $divisions;
    public $noOfPersons;
    public $shifts;
    public $interViewer;
    public $seekApprover;

    /**
     * Create a new component instance.
     */
    public function __construct($action){
        //
        $this->action           = $action;
        $this->graduations      = Graduation::all();
        $this->minimumQualifications  = MinimumQualification::all();
        $this->positions        = Designation::all();
        $this->projects         = Project::all();
        $this->skills           = Skills::all();
        $this->keywords         = KeyworsRrf::all();
        $this->experiance       = @config('optionData.experience');
        $reportingToIds         = Employee::whereNotNull('reporting_to') ->distinct()->pluck('reporting_to');
        $this->projectLeaders   = Employee::whereIn('id', $reportingToIds)->get();
        $this->branches         = Branch::all();
        $this->divisions        = Department::all();
        $this->noOfPersons      = @config('optionData.noOfPersons');
        $this->shifts           = Workshift::all();
        $this->interViewer      = Employee::whereIn('id', $reportingToIds)->get();
        $this->seekApprover      = Employee::whereIn('id', $reportingToIds)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string{
        return view('components.forms.recuitment-form');
    }
}
