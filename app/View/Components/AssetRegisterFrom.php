<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\AssetVendors;
use App\Models\AssetItemMaster;
use App\Models\AssetClassification;
use App\Models\AssetCategory;
use App\Models\AssetType;


class AssetRegisterFrom extends Component
{
    public $vendors;
    public $assetItems;
    public $assetClassifications;
    public $assetCategories;
    public $assetTypes;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->vendors = AssetVendors::all();
        $this->assetItems = AssetItemMaster::all();
        $this->assetClassifications = AssetClassification::all();
        $this->assetCategories = AssetCategory::all();
        $this->assetTypes = AssetType::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.asset-register-from');
    }
}
