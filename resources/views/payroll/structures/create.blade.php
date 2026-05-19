@extends('layouts.app')

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <x-menu />
        <div class="layout-page">
            <x-header />
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">
                        <a href="{{ route('payroll.structures.index') }}" class="text-muted fw-light">Salary Structures /</a> Create New
                    </h4>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Structure Details</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('payroll.structures.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Structure Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g. Standard Monthly Salary" value="{{ old('name') }}" required />
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Briefly describe this salary structure">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mt-4">
                                            <h5 class="mb-3">Salary Components</h5>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 50px;">Enable</th>
                                                            <th>Component</th>
                                                            <th>Type</th>
                                                            <th>Calculation</th>
                                                            <th>Value</th>
                                                            <th class="text-center">Editable in Manual?</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($allComponents as $component)
                                                            <tr>
                                                                <td class="text-center">
                                                                    <input type="checkbox" name="components[{{ $component->id }}][enabled]" value="1">
                                                                </td>
                                                                <td>{{ $component->name }}</td>
                                                                <td>
                                                                    <span class="badge bg-label-{{ $component->type == 'earning' ? 'success' : 'danger' }}">{{ ucfirst($component->type) }}</span>
                                                                </td>
                                                                <td>
                                                                    <select name="components[{{ $component->id }}][calculation_type]" class="form-select form-select-sm">
                                                                        <option value="fixed">Fixed Amount</option>
                                                                        <option value="percentage">% of Base Salary</option>
                                                                        <option value="earned_percentage">% of earned salary</option>
                                                                        <option value="percentage_ctc">% of Monthly CTC</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="components[{{ $component->id }}][value]" value="0" class="form-control form-control-sm" step="0.01">
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="checkbox" name="components[{{ $component->id }}][is_editable]" value="1" checked>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary me-2">Create Structure</button>
                                            <a href="{{ route('payroll.structures.index') }}" class="btn btn-label-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-label-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Defining Structures</h5>
                                    <p class="card-text">
                                        A salary structure defines the default setup for employee earnings and deductions. 
                                        Once created, you can assign it to employees and specify their individual basic salary.
                                    </p>
                                    <ul class="ps-3 mb-0">
                                        <li>Define common components like HRA, PF, etc.</li>
                                        <li>Standardize calculations across departments.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <x-footer />
            </div>
        </div>
    </div>
</div>
@endsection
