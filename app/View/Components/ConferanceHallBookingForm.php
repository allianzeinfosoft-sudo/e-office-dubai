<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Employee;
use App\Models\Department;

class ConferanceHallBookingForm extends Component
{
    /**
     * Create a new component instance.
     */
    public $departments;
    public $employees;
    
    public function __construct(){
        //
        $this->departments  = Department::where('status', 1)->get();
        $this->employees    = Employee::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.conferance-hall-booking-form');
    }
}
