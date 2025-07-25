<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\AssetVendors;
use App\Models\AssetItemMaster;
use App\Models\ScrapRegister;

class ScrapRegisterForm extends Component
{
    public $vendors;
    public $assetItems;
    public $batch_no;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->vendors = AssetVendors::all();
        $this->assetItems = AssetItemMaster::all();

        // Generate next asset number as AST-00001 format
        $lastAsset = ScrapRegister::orderBy('id', 'desc')->first();
        $lastNumber = $lastAsset ? intval(preg_replace('/[^0-9]/', '', $lastAsset->id)) : 0;
        $this->batch_no = 'SRP-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.scrap-register-form');
    }
}
