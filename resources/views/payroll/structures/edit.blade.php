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
                            <a href="{{ route('payroll.structures.index') }}" class="text-muted fw-light">Salary Structures
                                /</a> Edit Structure
                        </h4>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <form action="{{ route('payroll.structures.update', $structure->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Structure Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" placeholder="e.g. Standard Monthly Salary"
                                                    value="{{ old('name', $structure->name) }}" required />
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="description">Description</label>
                                                <textarea id="description" name="description"
                                                    class="form-control @error('description') is-invalid @enderror"
                                                    placeholder="Briefly describe this salary structure">{{ old('description', $structure->description) }}</textarea>
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
                                                                @php
                                                                    $pivot = $structure->components->find($component->id)?->pivot;
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                            name="components[{{ $component->id }}][enabled]"
                                                                            value="1" {{ $pivot ? 'checked' : '' }}>
                                                                    </td>
                                                                    <td>{{ $component->name }}</td>
                                                                    <td>
                                                                        <span
                                                                            class="badge bg-label-{{ $component->type == 'earning' ? 'success' : 'danger' }}">{{ ucfirst($component->type) }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <select
                                                                            name="components[{{ $component->id }}][calculation_type]"
                                                                            class="form-select form-select-sm">
                                                                            <option value="fixed" {{ ($pivot && $pivot->calculation_type == 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
                                                                            <option value="percentage" {{ ($pivot && $pivot->calculation_type == 'percentage') ? 'selected' : '' }}>% of Base Salary</option>
                                                                            <option value="earned_percentage" {{ ($pivot && $pivot->calculation_type == 'earned_percentage') ? 'selected' : '' }}>% of earned salary</option>
                                                                            <option value="percentage_ctc" {{ ($pivot && $pivot->calculation_type == 'percentage_ctc') ? 'selected' : '' }}>% of Monthly CTC</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number"
                                                                            name="components[{{ $component->id }}][value]"
                                                                            value="{{ $pivot ? $pivot->value : 0 }}"
                                                                            class="form-control form-control-sm" step="0.01">
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                            name="components[{{ $component->id }}][is_editable]"
                                                                            value="1" {{ ($pivot && $pivot->is_editable) ? 'checked' : '' }}>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-primary me-2">Update Structure</button>
                                                <a href="{{ route('payroll.structures.index') }}"
                                                    class="btn btn-label-secondary">Cancel</a>
                                            </div>
                                        </form>
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