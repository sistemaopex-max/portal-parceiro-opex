<?php

namespace App\View\Components;

use App\Support\BreadcrumbTrail;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app', [
            'breadcrumbs' => BreadcrumbTrail::forCurrentRoute(),
        ]);
    }
}
