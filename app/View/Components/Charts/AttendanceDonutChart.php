<?php

namespace App\View\Components\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AttendanceDonutChart extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public array $labels,
        public array $donutsData,
        public array $backgroundColors,
        public array $colors = [],
        public bool $isRtl = false,
        public string $height = '300px'
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.charts.attendance-donut-chart');
    }
}
