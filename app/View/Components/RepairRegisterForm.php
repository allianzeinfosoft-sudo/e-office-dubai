<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\AssetVendors;
use App\Models\AssetItemMaster;

class RepairRegisterForm extends Component
{
    /**
     * Create a new component instance.
     */
    public $vendors;
    public $assetItems;

    public function __construct()
    {
        //
        $this->vendors = AssetVendors::all();
        $this->assetItems = AssetItemMaster::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.repair-register-form');
    }
}
