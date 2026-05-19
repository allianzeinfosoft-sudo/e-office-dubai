@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/handsontable/14.0.0/handsontable.full.min.css" />
<style>
    .content-wrapper { 
        overflow: visible !important; 
        position: relative !important;
    }
    
    .hot-card-main {
        border: 1px solid #dcdfe6;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
        position: relative !important;
        overflow: visible !important; 
    }
    
    .hot-container {
        width: 100% !important;
        position: relative !important;
        height: auto !important;
        min-height: 400px;
    }

    .sticky-payroll-header {
        position: sticky;
        top: 0;
        background: #7367f0;
        z-index: 1100;
        padding: 12px 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 8px 8px 0 0;
    }

    .nav-tabs-custom {
        background: #f8f9fa;
        position: sticky;
        top: 50px; 
        z-index: 1050;
        border-bottom: 1px solid #ddd;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 10px 15px;
        font-size: 13px;
    }
    .nav-tabs-custom .nav-link.active {
        background: #fff;
        color: #7367f0;
        border-bottom: 2px solid #7367f0;
    }

    .handsontable .ht_clone_top, 
    .handsontable .ht_clone_left, 
    .handsontable .ht_clone_top_left_corner {
        z-index: 100 !important;
        pointer-events: all !important;
    }

    .handsontable th {
        background-color: #f1f5f9 !important;
        font-weight: 700 !important;
        font-size: 11px !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
    }
    .handsontable td {
        font-size: 12px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 4px 8px !important;
    }

    .group-header-1 { background-color: #7367f0 !important; color: #fff !important; }
    .group-header-2 { background-color: #00cfe8 !important; color: #fff !important; }
    .group-header-3 { background-color: #28c76f !important; color: #fff !important; }
    .group-header-4 { background-color: #82868b !important; color: #fff !important; }
    .group-header-5 { background-color: #ff9f43 !important; color: #fff !important; }
    .group-header-6 { background-color: #ea5455 !important; color: #fff !important; }
    .group-header-7 { background-color: #1e1e1e !important; color: #fff !important; }
    .group-header-8 { background-color: #7367f0 !important; color: #fff !important; }
    .header-group-row { text-transform: uppercase; font-size: 10px !important; font-weight: 800 !important; }
    
    .readonly-cell { background-color: #f8fafc !important; color: #64748b !important; }
    .numeric-cell { text-align: right !important; }
</style>
@endpush

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <x-menu />
        <div class="layout-page">
            <x-header />
            <div class="content-wrapper">
                <div class="container-fluid flex-grow-1 container-p-y">
                    
                    <form id="payrollForm" action="{{ route('payroll.batches.store-manual') }}" method="POST">
                        @csrf
                        <input type="hidden" name="month" value="{{ $month }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="department_id" value="{{ $department_id }}">

                        <input type="hidden" name="is_part_wise" value="{{ $is_part_wise ? '1' : '0' }}">
                        <input type="hidden" name="entries_json" id="entries_json">

                        <div class="hot-card-main shadow">
                            <div class="sticky-payroll-header">
                                <h5 class="mb-0 text-white">Manual Entry: {{ date("F", mktime(0, 0, 0, $month, 10)) }} {{ $year }}</h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('payroll.batches.create') }}" class="btn btn-light btn-sm">
                                        <i class="ti ti-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>

                            <ul class="nav nav-tabs nav-tabs-custom" id="payrollTabs" role="tablist">
                                @foreach($structures as $index => $structure)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                            id="tab-btn-{{ $structure->id }}" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#tab-panel-{{ $structure->id }}" 
                                            type="button" role="tab">
                                        {{ $structure->name }} 
                                        <span class="badge bg-label-secondary ms-1">{{ count($hotData[$structure->id] ?? []) }} Emp</span>
                                    </button>
                                </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="payrollTabContent">
                                @foreach($structures as $index => $structure)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                     id="tab-panel-{{ $structure->id }}" 
                                     role="tabpanel">
                                    <div id="hot-{{ $structure->id }}" class="hot-container"></div>
                                </div>
                                @endforeach
                            </div>

                            <div class="card-footer bg-light border-top p-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 fw-bold shadow">
                                    <i class="ti ti-device-floppy me-1"></i> SAVE PAYROLL DATA
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <x-footer />
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/handsontable/14.0.0/handsontable.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const payrollSettings = @json($payrollSettings);
    const hotData = @json($hotData);
    const hots = {};

    const calculateRow = (data, prop) => {
        const workDays = parseFloat(data.payable_days) || 0;
        const deductionDays = parseFloat(data.deduction_days) || 0;
        data.real_payable_days = Math.max(0, workDays - deductionDays);
        
        const proRataFactor = data.real_payable_days / 30;

        const ctcBasicDa = parseFloat(data.ctc_basic_da) || 0;
        const ctcHra = parseFloat(data.ctc_hra) || 0;
        data.ctc_total = Math.round((ctcBasicDa + ctcHra) * 100) / 100;

        data.earned_basic_da = Math.round(ctcBasicDa * proRataFactor);
        data.earned_hra = Math.round(ctcHra * proRataFactor);
        data.earned_total = Math.round((data.earned_basic_da + data.earned_hra) * 100) / 100;

        // Incentive and OT
        if (data.incentive_is_attendance_based) {
            data.incentive = Math.round((parseFloat(data.ctc_incentive) || 0) * proRataFactor);
        }
        if (data.ot_add_is_attendance_based) {
            data.ot_add = Math.round((parseFloat(data.ctc_ot_add) || 0) * proRataFactor);
        }

        const incentive = parseFloat(data.incentive) || 0;
        const otAdd = parseFloat(data.ot_add) || 0;

        // Dynamic components first to calculate dynamic earnings/deductions
        let dynamicEarnings = 0;
        let dynamicDeductions = 0;
        Object.keys(data).forEach(key => {
            if (key.startsWith('comp_') && !key.endsWith('_editable') && !key.endsWith('_name') && !key.endsWith('_type') && !key.endsWith('_standard') && !key.endsWith('_is_attendance_based')) {
                if (data[key + '_is_attendance_based']) {
                    data[key] = Math.round((parseFloat(data[key + '_standard']) || 0) * proRataFactor);
                }
                const val = parseFloat(data[key]) || 0;
                const type = data[key + '_type'] || 'earning';
                if (type === 'earning') dynamicEarnings += val;
                else dynamicDeductions += val;
            }
        });

        // PF Calculation Base
        let pfBase = 0;
        const pfEmpBaseConfig = (payrollSettings.pf_employee_base || '').toLowerCase();
        if (pfEmpBaseConfig === 'gross') {
            pfBase = data.earned_total + incentive + otAdd + dynamicEarnings;
        } else {
            const pfComponents = pfEmpBaseConfig.split(',').map(s => s.trim().toLowerCase());
            if (pfComponents.some(c => c.includes('basic'))) pfBase += data.earned_basic_da;
            if (pfComponents.some(c => c.includes('hra'))) pfBase += data.earned_hra;
            if (pfComponents.some(c => c.includes('incentive'))) pfBase += incentive;
            if (pfComponents.some(c => c.includes('ot')) || pfComponents.some(c => c.includes('overtime'))) pfBase += otAdd;
            
            Object.keys(data).forEach(key => {
                if (key.startsWith('comp_') && !key.endsWith('_editable') && !key.endsWith('_name') && !key.endsWith('_type') && !key.endsWith('_standard') && !key.endsWith('_is_attendance_based')) {
                    const cName = (data[key + '_name'] || '').toLowerCase();
                    if (pfComponents.includes(cName)) pfBase += (parseFloat(data[key]) || 0);
                }
            });
        }
        data.pf_salary = data.pf_eligible ? Math.round(Math.min(pfBase, payrollSettings.pf_wage_limit)) : 0;
        if (data.pf_eligible) {
            data.pf = Math.round(data.pf_salary * (payrollSettings.pf_employee_percent / 100));
        } else data.pf = 0;

        // ESI Calculation Base
        let esiBase = 0;
        const esiEmpBaseConfig = (payrollSettings.esi_employee_base || '').toLowerCase();
        if (esiEmpBaseConfig === 'gross') {
            esiBase = data.earned_total + incentive + otAdd + dynamicEarnings;
        } else {
            const esiComponents = esiEmpBaseConfig.split(',').map(s => s.trim().toLowerCase());
            if (esiComponents.includes('basic salary') || esiComponents.includes('basic') || esiComponents.includes('basic+da')) {
                esiBase += (parseFloat(data.earned_basic_da) || 0);
            }
            if (esiComponents.includes('hra')) {
                esiBase += (parseFloat(data.earned_hra) || 0);
            }
            
            Object.keys(data).forEach(key => {
                if (key.startsWith('comp_') && !key.endsWith('_editable') && !key.endsWith('_name') && !key.endsWith('_type') && !key.endsWith('_standard') && !key.endsWith('_is_attendance_based')) {
                    const cName = (data[key + '_name'] || '').toLowerCase();
                    if (esiComponents.includes(cName)) {
                        esiBase += (parseFloat(data[key]) || 0);
                    }
                }
            });
        }
        data.esi_salary = data.esi_eligible ? Math.round(Math.min(esiBase, payrollSettings.esi_wage_limit)) : 0;
        
        const esiRate = payrollSettings.esi_employee_percent / 100;
        const totalEsi = data.esi_eligible ? Math.round(data.esi_salary * esiRate) : 0;
        data.esi = totalEsi;

        // Split ESI into Part 1 and Part 2 portions for display splitting
        const part1EsiSalary = data.esi_eligible ? Math.min(data.earned_total, payrollSettings.esi_wage_limit) : 0;
        const part1Esi = Math.min(totalEsi, data.esi_eligible ? Math.round(part1EsiSalary * esiRate) : 0);
        const part2Esi = Math.max(0, totalEsi - part1Esi);

        const wwf = parseFloat(data.wwf) || 0;

        // Final Salary Logic
        data.total_deductions = Math.round((data.pf + data.esi + wwf + dynamicDeductions) * 100) / 100;
        
        // Statutory (Part 1) - Subtraction of only Part 1 Deductions
        data.part_a = Math.round((data.earned_total - (data.pf + part1Esi + wwf + dynamicDeductions)) * 100) / 100;
        
        // Non-Statutory (Part 2) - Part 2 earnings minus its incremental ESI
        data.part_b = Math.round((incentive + otAdd + dynamicEarnings - part2Esi) * 100) / 100;
        
        const rawNet = data.part_a + data.part_b;
        data.net_pay = Math.round(rawNet);
        data.round_off = (data.net_pay - rawNet).toFixed(2);
    };

    @foreach($structures as $structure)
    (function(sid) {
        const container = document.getElementById('hot-' + sid);
        if (!container) return;
        
        const sourceData = JSON.parse(JSON.stringify(hotData[sid] || []));
        sourceData.forEach(row => calculateRow(row, 'loadData'));

        // Identify and categorize dynamic columns for this structure
        const firstRow = sourceData[0] || {};
        const dynamicEarnings = [];
        const dynamicDeductions = [];
        
        Object.keys(firstRow).forEach(key => {
            if (key.startsWith('comp_') && !key.endsWith('_editable') && !key.endsWith('_name') && !key.endsWith('_type') && !key.endsWith('_standard') && !key.endsWith('_is_attendance_based')) {
                const entry = {
                    key: key,
                    name: firstRow[key + '_name'] || key.replace('comp_', '').replace(/_/g, ' ').toUpperCase(),
                    type: firstRow[key + '_type'] || 'earning'
                };
                if (entry.type === 'earning') dynamicEarnings.push(entry);
                else dynamicDeductions.push(entry);
            }
        });

        const nestedHeaders = [
            [
                { label: 'Employee Details', colspan: 2, className: 'group-header-1 header-group-row' },
                { label: 'Envolvement', colspan: 3, className: 'group-header-2 header-group-row' },
                { label: 'Actual Salary (Earned)', colspan: 3, className: 'group-header-3 header-group-row' },
                { label: 'Working Details', colspan: 3, className: 'group-header-4 header-group-row' },
                { label: 'Additions', colspan: 2 + dynamicEarnings.length, className: 'group-header-7 header-group-row' },
                { label: 'Salary Calculation', colspan: 2, className: 'group-header-5 header-group-row' },
                { label: 'Deduction', colspan: 4 + dynamicDeductions.length, className: 'group-header-6 header-group-row' },
                { label: 'Final Salary Calculation', colspan: 3, className: 'group-header-8 header-group-row' },
                { label: 'Extra', colspan: 1, className: 'header-group-row' }
            ],
            [
                'Code', 'Name', 
                'Basic+DA', 'HRA', 'Total',
                'Basic+DA', 'HRA', 'Total',
                'Work Days', 'Deduc', 'Payable',
                'Incentive', 'OT', ...dynamicEarnings.map(e => e.name),
                'PF Sal', 'ESI Sal',
                'PF', 'ESI', 'WWF', ...dynamicDeductions.map(e => e.name), 'Tot Ded',
                'Part 1', 'Part 2', 'Net Pay',
                'Remarks'
            ]
        ];

        const baseColumns = [
            { data: 'code', readOnly: true, className: 'readonly-cell' },
            { data: 'name', readOnly: true, className: 'readonly-cell', width: 170 },
            { data: 'ctc_basic_da', type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' },
            { data: 'ctc_hra', type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' },
            { data: 'ctc_total', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'earned_basic_da', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'earned_hra', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'earned_total', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell fw-bold' },
            { data: 'payable_days', type: 'numeric', className: 'htCenter' },
            { data: 'deduction_days', type: 'numeric', className: 'htCenter' },
            { data: 'real_payable_days', type: 'numeric', readOnly: true, className: 'readonly-cell htCenter fw-bold' }
        ];

        // Additions Second
        baseColumns.push(
            { data: 'incentive', type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' },
            { data: 'ot_add', type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' }
        );

        dynamicEarnings.forEach(e => {
            baseColumns.push({ data: e.key, type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' });
        });

        // Salary Calculation Third
        baseColumns.push(
            { data: 'pf_salary', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'esi_salary', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' }
        );

        // Deductions First
        baseColumns.push(
            { data: 'pf', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'esi', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'wwf', type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' }
        );

        dynamicDeductions.forEach(e => {
            baseColumns.push({ data: e.key, type: 'numeric', numericFormat: { pattern: '0,0.00' }, className: 'numeric-cell' });
        });

        baseColumns.push({ data: 'total_deductions', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell text-danger' });

        baseColumns.push(
            { data: 'part_a', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'part_b', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell' },
            { data: 'net_pay', type: 'numeric', numericFormat: { pattern: '0,0.00' }, readOnly: true, className: 'readonly-cell numeric-cell fw-bold text-success' },
            { data: 'remarks', type: 'text' }
        );

        hots[sid] = new Handsontable(container, {
            data: sourceData,
            licenseKey: 'non-commercial-and-evaluation',
            height: 'auto',
            width: '100%',
            rowHeaders: true,
            fixedColumnsStart: 2,
            stretchH: 'none',
            autoWrapRow: false,
            contextMenu: true,
            viewportColumnRenderingOffset: 24,
            manualColumnResize: true,
            manualColumnMove: true,
            nestedHeaders: nestedHeaders,
            columns: baseColumns,
            cells: function(row, col, prop) {
                const cellProperties = {};
                const rowData = this.instance.getSourceDataAtRow(row);
                if (!rowData) return cellProperties;

                // Check if this property should be read-only based on the editable flag
                const isEditable = rowData[prop + '_editable'];
                // Some specific field names for editability tracking
                const specialMaps = {
                    'ctc_basic_da': 'earned_basic_da_editable',
                    'earned_basic_da': 'earned_basic_da_editable',
                    'ctc_hra': 'earned_hra_editable',
                    'earned_hra': 'earned_hra_editable',
                    'incentive': 'incentive_editable',
                    'ot_add': 'ot_add_editable',
                    'wwf': 'wwf_editable'
                };

                const editableKey = specialMaps[prop] || (prop + '_editable');
                
                // If it's a field that could be editable but we passed it as not editable
                if (rowData.hasOwnProperty(editableKey) && (rowData[editableKey] === 0 || rowData[editableKey] === false)) {
                    cellProperties.readOnly = true;
                    cellProperties.className = (cellProperties.className || '') + ' readonly-cell';
                }
                
                return cellProperties;
            },
            afterChange: function(changes, source) {
                if (source === 'loadData' || source === 'calculation' || !changes) return;
                
                const updates = [];
                changes.forEach(([row, prop, oldValue, newValue]) => {
                    if (oldValue === newValue) return;
                    
                    const rowData = this.getSourceDataAtRow(row);
                    calculateRow(rowData, prop);
                    
                    // Collect all values that might have been modified by calculateRow
                    const affectedProps = [
                        'payable_days', 'deduction_days', 'real_payable_days',
                        'earned_basic_da', 'earned_hra', 'earned_total', 
                        'incentive', 'ot_add',
                        'pf_salary', 'esi_salary', 'pf', 'esi', 
                        'total_deductions', 'part_a', 'part_b', 'net_pay'
                    ];
                    
                    // Add dynamic component keys to affectedProps
                    Object.keys(rowData).forEach(k => {
                        if (k.startsWith('comp_') && !k.endsWith('_editable') && !k.endsWith('_name') && !k.endsWith('_type') && !k.endsWith('_standard') && !k.endsWith('_is_attendance_based')) {
                            affectedProps.push(k);
                        }
                    });
                    
                    affectedProps.forEach(p => {
                        updates.push([row, p, rowData[p]]);
                    });
                });
                
                if (updates.length > 0) {
                    this.setDataAtRowProp(updates, 'calculation');
                }
            }
        });

        function visualRowIndexToSourceRowIndex(visualRowIndex) {
            return visualRowIndex; // For now mapping is direct since no sorting/filtering is active
        }
    })('{{ $structure->id }}');
    @endforeach

    const payrollTabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
    payrollTabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', (event) => {
            const pid = event.target.getAttribute('data-bs-target').replace('#tab-panel-', '');
            if (hots[pid]) {
                hots[pid].render();
                setTimeout(() => hots[pid].refreshDimensions(), 100);
            }
        });
    });

    document.getElementById('payrollForm').addEventListener('submit', function(e) {
        let allEntries = [];
        Object.keys(hots).forEach(structId => {
            allEntries = allEntries.concat(hots[structId].getSourceData());
        });
        document.getElementById('entries_json').value = JSON.stringify(allEntries);
    });
});
</script>
@endpush
