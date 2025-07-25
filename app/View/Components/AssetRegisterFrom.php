<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\AssetVendors;
use App\Models\AssetItemMaster;
use App\Models\AssetClassification;
use App\Models\AssetCategory;
use App\Models\AssetRegister;
use App\Models\AssetType;


class AssetRegisterFrom extends Component
{
    public $vendors;
    public $assetItems;
    public $assetClassifications;
    public $assetCategories;
    public $assetTypes;
    public $batch_no;
    /**;
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->vendors = AssetVendors::all();
        $this->assetItems = AssetItemMaster::all();
        $this->assetClassifications = AssetClassification::all();
        $this->assetCategories = AssetCategory::all();
        $this->assetTypes = AssetType::all();

        // Generate next asset number as AST-00001 format
        $lastAsset = AssetRegister::orderBy('id', 'desc')->first();
        $lastNumber = $lastAsset ? intval(preg_replace('/[^0-9]/', '', $lastAsset->asset_number)) : 0;
        $this->batch_no = 'AST-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.asset-register-from');
    }
}
