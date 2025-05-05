<?php

namespace App\View\Components\Charts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BarChart extends Component
{
    public string $id;
    public array $labels;
    public array $data;
    public array $colors;
    public string $barColor;
    public bool $isRtl;
    public int $maxY;
    public int $stepY;
    public string $height;

    public function __construct(
        string $id,
        array $labels,
        array $data,
        array $colors = [],
        string $barColor = '#28dac6',
        bool $isRtl = false,
        int $maxY = 300,
        int $stepY = 50,
        string $height = '300px'
    ) {
        $this->id       = $id;
        $this->labels   = $labels;
        $this->data     = $data;
        $this->colors   = $colors;
        $this->barColor = $barColor;
        $this->isRtl    = $isRtl;
        $this->maxY     = $maxY;
        $this->stepY    = $stepY;
        $this->height   = $height;
    }

    public function render()
    {
        return view('components.charts.bar-chart')->with([
            'id'        => $this->id,
            'labels'    => $this->labels,
            'data'      => $this->data,
            'colors'    => $this->colors,
            'barColor'  => $this->barColor,
            'isRtl'     => $this->isRtl,
            'maxY'      => $this->maxY,
            'stepY'     => $this->stepY,
            'height'    => $this->height,
        ]);

    }
}
