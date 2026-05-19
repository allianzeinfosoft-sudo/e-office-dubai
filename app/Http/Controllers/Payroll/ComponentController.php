<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
     public function index()
     {
          $components = SalaryComponent::latest()->paginate(10);
          return view('payroll.components.index', compact('components'));
     }

     public function create()
     {
          return view('payroll.components.create');
     }

     public function store(Request $request)
     {
          $request->validate([
               'name' => 'required|string|max:255',
               'type' => 'required|in:earning,deduction,employer_contribution',
               'is_statutory' => 'boolean',
               'is_variable' => 'boolean',
               'is_ctc_variable' => 'boolean',
               'is_attendance_based' => 'boolean',
               'status' => 'boolean'
          ]);

          SalaryComponent::create($request->all());

          return redirect()->route('payroll.components.index')
               ->with('success', 'Salary Component created successfully.');
     }

     public function edit(SalaryComponent $component)
     {
          return view('payroll.components.edit', compact('component'));
     }

     public function update(Request $request, SalaryComponent $component)
     {
          $request->validate([
               'name' => 'required|string|max:255',
               'type' => 'required|in:earning,deduction,employer_contribution',
               'is_statutory' => 'boolean',
               'is_variable' => 'boolean',
               'is_ctc_variable' => 'boolean',
               'is_attendance_based' => 'boolean',
               'status' => 'boolean'
          ]);

          $component->update($request->all());

          return redirect()->route('payroll.components.index')
               ->with('success', 'Salary Component updated successfully.');
     }

     public function destroy(SalaryComponent $component)
     {
          $component->delete();
          return redirect()->route('payroll.components.index')
               ->with('success', 'Salary Component deleted successfully.');
     }
}
