<?php

namespace App\View\Components\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ApexBarChart extends Component
{
    public $elementId;
    public $series;
    public $categories;

    public function __construct($elementId = 'barChart', $series = [], $categories = [])
    {
        $this->elementId = $elementId;
        $this->series = $series;
        $this->categories = $categories;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.charts.apex-bar-chart');
    }
}
