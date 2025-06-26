<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\VendorCategory;

class VendorForm extends Component
{
   public $vendorCategories; 
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->vendorCategories = VendorCategory::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.vendor-form');
    }
}
