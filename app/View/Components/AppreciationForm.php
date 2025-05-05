<?php

namespace App\View\Components;

use App\Models\Project;
use App\Models\User;
use Closure;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppreciationForm extends Component
{

    public $users;
    public $projects;

    public function __construct()
    {
        $this->users = User::with('employee')->get();
        $this->projects = Project::get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.appreciation-form');
    }
}
