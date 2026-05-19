<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::latest()->paginate(10);
        return view('payroll.structures.index', compact('structures'));
    }

    public function create()
    {
        $allComponents = \App\Models\SalaryComponent::where('status', true)->get();
        return view('payroll.structures.create', compact('allComponents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'components' => 'nullable|array',
            'components.*.id' => 'exists:salary_components,id',
            'components.*.calculation_type' => 'required|in:fixed,percentage,earned_percentage',
            'components.*.value' => 'required|numeric|min:0'
        ]);

        $structure = SalaryStructure::create($request->only(['name', 'description']));

        if ($request->has('components')) {
            foreach ($request->components as $compId => $data) {
                if (isset($data['enabled'])) {
                    $structure->components()->attach($compId, [
                        'calculation_type' => $data['calculation_type'],
                        'value' => $data['value'],
                        'is_editable' => isset($data['is_editable']) ? 1 : 0
                    ]);
                }
            }
        }

        return redirect()->route('payroll.structures.index')
            ->with('success', 'Salary Structure created successfully.');
    }

    public function edit(SalaryStructure $structure)
    {
        $structure->load('components');
        $allComponents = \App\Models\SalaryComponent::where('status', true)->get();
        return view('payroll.structures.edit', compact('structure', 'allComponents'));
    }

    public function update(Request $request, SalaryStructure $structure)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'components' => 'nullable|array',
            'components.*.calculation_type' => 'required|in:fixed,percentage,earned_percentage',
            'components.*.value' => 'required|numeric|min:0'
        ]);

        $structure->update($request->only(['name', 'description']));

        $syncData = [];
        if ($request->has('components')) {
            foreach ($request->components as $compId => $data) {
                if (isset($data['enabled'])) {
                    $syncData[$compId] = [
                        'calculation_type' => $data['calculation_type'],
                        'value' => $data['value'],
                        'is_editable' => isset($data['is_editable']) ? 1 : 0
                    ];
                }
            }
        }
        $structure->components()->sync($syncData);

        return redirect()->route('payroll.structures.index')
            ->with('success', 'Salary Structure updated successfully.');
    }

    public function destroy(SalaryStructure $structure)
    {
        $structure->delete();
        return redirect()->route('payroll.structures.index')
            ->with('success', 'Salary Structure deleted successfully.');
    }
}
