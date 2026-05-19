<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PayrollSettingController extends Controller
{
     public function index()
     {
          $settings = [
               'pf_employee_percent' => Setting::get('payroll_pf_employee_percent', 12),
               'pf_employer_percent' => Setting::get('payroll_pf_employer_percent', 12),
               'pf_wage_limit' => Setting::get('payroll_pf_wage_limit', 15000),
               'esi_employee_percent' => Setting::get('payroll_esi_employee_percent', 0.75),
               'esi_employer_percent' => Setting::get('payroll_esi_employer_percent', 3.25),
               'esi_wage_limit' => Setting::get('payroll_esi_wage_limit', 21000),
               'pf_employee_base' => explode(',', Setting::get('payroll_pf_employee_base', 'Basic Salary')),
               'pf_employer_base' => explode(',', Setting::get('payroll_pf_employer_base', 'Basic Salary, DA')),
               'pf_employer_use_fixed' => Setting::get('payroll_pf_employer_use_fixed', 1),
               'esi_employee_base' => explode(',', Setting::get('payroll_esi_employee_base', 'Gross')),
               'esi_employer_base' => explode(',', Setting::get('payroll_esi_employer_base', 'Gross')),
               'esi_employer_use_fixed' => Setting::get('payroll_esi_employer_use_fixed', 0),
          ];

          $allComponents = \App\Models\SalaryComponent::pluck('name')->toArray();
          // Add system components if not present
          $systemComponents = ['Gross', 'Incentive', 'OT']; // Standard options
          $availableOptions = array_unique(array_merge($systemComponents, $allComponents));

          return view('payroll.settings.index', compact('settings', 'availableOptions'));
     }

     public function update(Request $request)
     {
          $data = $request->validate([
               'pf_employee_percent' => 'required|numeric|min:0',
               'pf_employer_percent' => 'required|numeric|min:0',
               'pf_wage_limit' => 'required|numeric|min:0',
               'esi_employee_percent' => 'required|numeric|min:0',
               'esi_employer_percent' => 'required|numeric|min:0',
               'esi_wage_limit' => 'required|numeric|min:0',
               'pf_employee_base' => 'required|array',
               'pf_employer_base' => 'required|array',
               'pf_employer_use_fixed' => 'required|boolean',
               'esi_employee_base' => 'required|array',
               'esi_employer_base' => 'required|array',
               'esi_employer_use_fixed' => 'required|boolean',
          ]);

          foreach ($data as $key => $value) {
               if (is_array($value)) {
                    $value = implode(',', $value);
               }
               
               $type = in_array($key, ['pf_employee_base', 'pf_employer_base', 'esi_employee_base', 'esi_employer_base']) ? 'string' : (str_contains($key, 'use_fixed') ? 'boolean' : 'numeric');
               Setting::updateOrCreate(
                    ['key' => 'payroll_' . $key],
                    ['value' => $value, 'type' => $type]
               );
          }

          return redirect()->back()->with('success', 'Payroll settings updated successfully.');
     }
}
