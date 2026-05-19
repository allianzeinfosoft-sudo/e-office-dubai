<?php

namespace App\Http\Controllers;

use App\Models\CompanyBankConfiguration;
use Illuminate\Http\Request;

class CompanyBankConfigurationController extends Controller
{
    public function index()
    {
        $data['config'] = CompanyBankConfiguration::first();
        return view('company-bank-configurations.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string',
            'branch' => 'required|string',
            'ifsc' => 'required|string',
            'account_no' => 'required|string',
        ]);

        CompanyBankConfiguration::updateOrCreate(['id' => 1], $validated);

        return redirect()->back()->with('success', 'Company Bank configuration saved.');
    }
}
