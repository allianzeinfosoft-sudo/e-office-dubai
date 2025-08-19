<?php

namespace App\View\Components;

use App\Models\AssetCategory;
use App\Models\AssetVendors;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AssetExpiryForm extends Component
{
    public $vendors;
    public $categories;

    public function __construct()
    {
        $this->vendors = AssetVendors::all();
        $this->categories = AssetCategory::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.asset-expiry-form');
    }
}
