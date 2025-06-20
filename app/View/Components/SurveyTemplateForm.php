<?php

namespace App\View\Components;

use App\Models\Department;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SurveyTemplateForm extends Component
{
    public $departments;
    public function __construct()
    {
        $this->departments = Department::get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.survey-template-form');
    }
}
