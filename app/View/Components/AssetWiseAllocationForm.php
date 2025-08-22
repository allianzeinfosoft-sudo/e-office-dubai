<?php

namespace App\View\Components;

use App\Models\AssetCategory;
use App\Models\AssetClassification;
use App\Models\AssetItemMaster;
use App\Models\AssetType;
use App\Models\AssetVendors;
use App\Models\Project;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AssetWiseAllocationForm extends Component
{
    public $asset_wise_vendors;
    public $asset_wise_assetItems;
    public $asset_wise_assetClassifications;
    public $asset_wise_assetCategories;
    public $asset_wise_assetTypes;
    public $asset_wise_projects;

    public function __construct()
    {
        $this->asset_wise_vendors = AssetVendors::all();
        $this->asset_wise_assetItems = AssetItemMaster::with('asset_items')->get();
        $this->asset_wise_assetClassifications = AssetClassification::all();
        $this->asset_wise_assetCategories = AssetCategory::all();
        $this->asset_wise_assetTypes = AssetType::all();
        $this->asset_wise_projects = Project::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.asset-wise-allocation-form');
    }
}
