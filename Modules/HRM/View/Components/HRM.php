<?php

namespace Modules\HRM\View\Components;

use Illuminate\View\Component;

class HRM extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('hrm::components.hrm');
    }
}
