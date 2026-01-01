<?php

namespace App\View\Components;

use App\Models\Training;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;

class TrainingTestForm extends Component
{
    /**
     * Create a new component instance.
     */
    public $trainings;
    public function __construct()
    {
        $now = Carbon::now();
        $this->trainings = Training::where('end_date_time', '<', $now)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.training-test-form');
    }
}
