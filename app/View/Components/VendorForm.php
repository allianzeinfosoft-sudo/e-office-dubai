<?php

namespace App\View\Components;

use App\Models\AssetVendors;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\VendorCategory;

class VendorForm extends Component
{
    public $vendorCategories;
    public $vendorCode;

    public function __construct()
    {

        $this->vendorCategories = VendorCategory::all();
        $lastVendor = AssetVendors::latest('id')->first();
        $lastId = $lastVendor ? $lastVendor->id : 0;
        $this->vendorCode = 'v-' . ($lastId + 1);

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.vendor-form');
    }
}
