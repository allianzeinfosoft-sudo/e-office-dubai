@extends('layouts.app')

@section('css')
<style>
 
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
      <!-- Menu -->
      <x-menu />

      <div class="layout-page">

        <!-- Navbar -->
        <x-header />

        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="fw-bold py-3"><span class="text-muted fw-light">Recruitments /</span> {{ $meta_title }}</h4>

                <div class="card">
                    <div class="card-datatable table-responsive">
                        <div class=" float-end mt-15 mr-20">
                        </div>

                        <table class="datatables-basic datatables-recruitments table border-top table-stripedc table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Job Title</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Project Name</th>
                                    <th>Interviewer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>  
                </div>
            </div>

            <!-- Footer -->
            <x-footer />
            <!-- / Footer -->
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="apporvalModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2">Enter Reason for Rejection</h3>
                </div>
                <form id="rejectedForm" class="row g-2 fv-plugins-bootstrap5 fv-plugins-framework" onsubmit="return false" novalidate="novalidate">
                    <div class="col-12">
                        <textarea class="form-control" id="rejectedReason" rows="3" placeholder="Reason for Rejection"></textarea>
                    </div>
                    <div class="col-12">
                        <input type="hidden" name="rrfId" id="rrfId" />
                        <button type="submit" class="btn btn-primary me-sm-3 me-1 waves-effect waves-light">Submit</button>
                        <button type="reset" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script>

    var rrtTable = $('.datatables-recruitments'),
    select2 = $('.select2');

    $(function() {
        const applicationStatus = @json(config('optionsData.applicationStatus'));

        if (rrtTable.length) {            
            rrtTable.DataTable({
                ajax: {
                    type: "GET",
                    url: "{{ route('recruitments.rrf-approvals') }}", // Fixed syntax
                    dataType: "json", 
                    dataSrc: "data"  
                },
                columns: [
                    { data: 'row', title: 'No.' },
                    { data: 'rrfDate', title: 'Date' },
                    { data: null, title: 'Job Title',
                        render: function (data, type, row) {
                            return `<a href="/recruitments/${row.id}/show" class="btn btn-link"> ${row.jobTitle} </a>`;
                        }
                     },
                    { data: 'designation', title: 'Designation' },
                    { data: 'status', title: 'Status', 
                        render: function (data, type, row) {
                            const colors = {
                                0: 'warning',
                                1: 'danger',
                                2: 'primary',
                                3: 'info',
                                4: 'success'
                            };
                                        
                            return `
                                <span class="badge bg-${colors[row.status] ?? 'warning'} bg-glow"> ${applicationStatus[row.status] ?? 'Pending'} </span>
                            `;
                        }
                    },
                    { data: 'priority', title: 'Priority' },
                    { data: 'projectName', title: 'Project Name' },
                    { data: 'interviewer', title: 'Interviewer' },
                    { 
                        data: null, 
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <a href="/recruitments/rrf-approve/${row.id}" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-info">Approve</a>
                                <a href="javascript:void(0)" onclick ="rejectModal(${row.id})" class="btn btn-sm btn-primary edit-project">Reject</a>
                            `;
                        }
                    }
                ]
            });
        }
    });

    function rejectModal(id) {
        const apporvalModal = new bootstrap.Modal(document.getElementById('apporvalModal'));
        apporvalModal.show();
        $('#rrfId').val(id);
    }

    document.getElementById('rejectedForm').addEventListener('submit', function (e) {
        e.preventDefault();

            const rrfId = $('#rrfId').val().trim();
            const reason = $('#rejectedReason').val().trim();

            if (!reason) {
                alert('Please enter a rejection reason.');
                return;
            }

            $.ajax({
                url: "{{ route('recruitments.reject') }}",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    rrf_id: rrfId,
                    reason: reason
                }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        alert("Rejection submitted successfully.");
                        window.location.reload();
                    } else {
                        alert(data.message || "Something went wrong.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("An error occurred while submitting the rejection.");
                }
            });
    });


    

    
</script>

@stop