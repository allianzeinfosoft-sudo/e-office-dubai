<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Employee;

class QuickNoteFrom extends Component
{
    public $employees;
    
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->employees = Employee::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.quick-note-from');
    }
}
