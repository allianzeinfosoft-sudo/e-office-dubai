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

class AssetAllocationForm extends Component
{

    public $vendors;
    public $assetItems;
    public $assetClassifications;
    public $assetCategories;
    public $assetTypes;
    public $projects;

    public function __construct()
    {
        $this->vendors = AssetVendors::all();
        $this->assetItems = AssetItemMaster::with('asset_items')->get();
        $this->assetClassifications = AssetClassification::all();
        $this->assetCategories = AssetCategory::all();
        $this->assetTypes = AssetType::all();
        $this->projects = Project::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.asset-allocation-form');
    }
}
