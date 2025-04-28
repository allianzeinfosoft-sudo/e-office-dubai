<?php

namespace App\View\Components;

use App\Models\Employee;
use App\Models\LoginLimitedTime;
use App\Models\Workshift;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChangeShiftFrom extends Component
{
   public $users;
   public $shifts;
   public $loginlimitedtimes;
    public function __construct()
    {
        $this->users = Employee::all();
        $this->shifts = Workshift::all();
        $this->loginlimitedtimes = LoginLimitedTime::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.change-shift-from');
    }
}
