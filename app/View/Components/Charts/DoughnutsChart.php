<?php

namespace App\View\Components\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DoughnutsChart extends Component
{
    public function __construct(
        public string $id,
        public array $labels,
        public array $donutsData,
        public array $backgroundColors,
        public array $colors = [],
        public bool $isRtl = false,
        public string $height = '300px'
    ) {}

    public function render()
    {
        return view('components.charts.doughnuts-chart')->with([
            'id'            => $this->id,
            'labels'        => $this->labels,
            'donutsData'    => $this->donutsData,
            'backgroundColors'  => $this->backgroundColors,
            'colors'        => $this->colors,
            'isRtl'         => $this->isRtl,
            'height'        => $this->height,
        ]);
    }
}