<?php

namespace App\View\Components;

use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemMaster;
use App\Models\AssetType;
use App\Models\AssetVendors;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AssetAllocationForm extends Component
{

    public $vendors;
    public $assetItems;
    public $assetClassifications;
    public $assetCategories;
    public $assetTypes;

    public function __construct()
    {
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
        return view('components.forms.asset-allocation-form');
    }
}
