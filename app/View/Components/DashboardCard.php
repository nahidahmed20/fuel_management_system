<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $title, $value, $color, $icon;

    public function __construct($title, $value, $color = 'info', $icon = 'fa-solid fa-chart-line')
    {
        $this->title = $title;
        $this->value = $value;
        $this->color = $color;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard-card');
    }
}
