<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\AssetVendors;
use App\Models\AssetItemMaster;

class ScrapRegisterForm extends Component
{
    public $vendors;
     public $assetItems;
    /**
     * Create a new component instance.
     */
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
        return view('components.forms.scrap-register-form');
    }
}
