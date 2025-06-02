<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;
use App\Models\Department;

class ProjectForm extends Component
{
    public $action;
    public $method;
    public $project;
    public $users;
    public $departments;
    

    public function __construct($action, $method = 'post', $project = null)
    {
        $this->action = $action;
        $this->method = $method;
        $this->project = $project;
        $this->users = User::all();
        $this->departments = Department::all();
    }

    public function render()
    {
        return view('components.forms.project-form');
    }
}
