<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Department;
use App\Models\Role;

class PolicyForm extends Component
{
    public $departments;
    public $roles;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->departments =Department::all();
        $this->roles = Role::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.policy-form');
    }
}
