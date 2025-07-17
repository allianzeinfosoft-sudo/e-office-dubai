@extends('layouts.app')

@section('css')
<style>
    .w-35 { width: 35% !important; }
    .w-45 { width: 45% !important; }
    .offcanvas-close {
        position: absolute;
        top: 0px;
        left: -32px;
        z-index: 1055;
        padding: 28px 10px;
        border-radius: 0px;
    }
</style>
@stop

@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <x-menu />

        <div class="layout-page">
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Others /</span> {{ $meta_title }}</h4>

                    <div class="row">
                    @hasanyrole('Developer|HR')
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openCompanyPolicyOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New Policy</span>
                                </span>
                            </a>
                        </div>
                    @endhasanyrole
                        <div class="card">
                            <div class="card-datatable table-policy">
                                <table class="datatables-basic datatables-policy table border-top table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Policy Title</th>
                                            <th>Start Date</th>
                                            <th>Description</th>
                                            <th>Attachment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <x-footer />
                <div class="content-backdrop fade"></div>
                <div class="layout-overlay layout-menu-toggle"></div>
                <div class="drag-target"></div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="policy_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i>
            <span id="policy-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create Company Policy</h5>
                <span class="text-white slogan">Create New Company Policy</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-company-policy-form />
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewCompanyPolicy" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">View MOM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <input type="hidden" name="id" id="target_id">
                <button type="button" onclick="markAsRead()" class="btn btn-primary waves-effect waves-light">Mark as Read</button>
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    const quillPolicy = new Quill('#policy-description-editor', {
        theme: 'snow',
        placeholder: 'Type policy description here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });

    $(function(){
        $('.flatpickr-input').flatpickr({
            monthSelectorType: 'static',
            altInput: true,
            altFormat: 'd-m-Y',
            dateFormat: 'd-m-Y'
        });

        let policyTable = $('.datatables-policy');

        if(policyTable.length){
            const assetBaseUrl = "{{ asset('storage/company_policies') }}";

            policyTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    type: "GET",
                    url: "{{ route('view.company-policies') }}",
                    dataType: "json",
                    dataSrc: "data"
                },
                columns: [
                    { data: 'row', name: 'No' },
                    { data: 'policyTitle', name: 'Policy Title' },
                    { data: 'policyStartDate', name: 'Start Date' },
                    { data: 'policyDescription', name: 'Description' },
                    {
                        data: 'attachments',
                        render: function (data) {
                            if (data) {
                                return `<a href="${assetBaseUrl}/${data}" target="_blank" class="btn btn-sm btn-outline-success"><i class="ti ti-download"></i></a>`;
                            }
                            return '-';
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <a href="javascript:void(0)" onclick="viewCompanyPolicyModal('${row.id}')" class="btn btn-sm btn-icon btn-success"><i class="ti ti-eye"></i></a>
                                <a href="javascript:void(0)" onclick="openCompanyPolicyOffcanvas(${row.id})" class="btn btn-sm btn-icon btn-primary"><i class="ti ti-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deletePolicy(${row.id})"><i class="ti ti-trash"></i></button>`;
                        }
                    }
                ]
            });
        }

        $('#company-policy-form').on('submit', function (e) {
            e.preventDefault();
            $('#description').val(quillPolicy.root.innerHTML);
            let form = $(this);
            let formData = new FormData(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
                },
                success: function (response) {
                    toastr["success"](response.message);
                    form.trigger('reset');
                    quillPolicy.root.innerHTML = '';
                    const offcanvasElement = document.getElementById('policy_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) offcanvas.hide();
                    policyTable.DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    let message = 'Something went wrong.';
                    if (xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).join('\n');
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr["error"](message);
                },
                complete: function () {
                    form.find('button[type="submit"]').prop('disabled', false).text('Save');
                }
            });
        });
    });

    function openCompanyPolicyOffcanvas(id = null) {
        const $form = $('#company-policy-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#policy-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create Policy</h5><span class="text-white slogan">Create New Policy</span>`);
        const offcanvasElement = $('#policy_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            const url = "{{ route('view.company-policies.edit', ':CompanyPolicy') }}".replace(':CompanyPolicy', id);
            $('#target_id').val(id);
            $('#policy-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit Policy</h5><span class="text-white slogan">Edit Policy</span>`);
            $('#current-attachment').remove();

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#policyTitle').val(data.policy.policyTitle);
                    $('#policyStartDate').flatpickr().setDate(data.policy.policyStartDate, true);
                    const desc = data.policy.policyDescription || '';
                    quillPolicy.root.innerHTML = desc;
                    $('#description').val(desc);

                    if (data.policy.attachments) {
                        const fileUrl = `/storage/company_policies/${data.policy.attachments}`;
                        $('#attachments').after(`
                            <div id="current-attachment" class="mt-2">
                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary"> <i class="ti ti-pin me-1"></i> ${data.policy.attachments} </a>
                            </div>
                        `);
                    } else {
                        $('#current-attachment').remove();
                    }

                    $('#status').val(data.policy.status);
                },
                error: function () {
                    alert('Failed to load policy data.');
                }
            });
        }
    }

    function deletePolicy(id) {
        if (confirm('Are you sure you want to delete this company policy?')) {
            $.ajax({
                url: "{{ route('view.company-policies.destroy', ':CompanyPolicy') }}".replace(':CompanyPolicy', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatables-policy').DataTable().ajax.reload();
                },
                error: function() {
                    alert("Error deleting policy. Please try again.");
                }
            });
        }
    }

    function viewCompanyPolicyModal(id) {
        const url = "{{ route('view.company-policies.show', ':companyPolicy') }}".replace(':companyPolicy', id);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#viewCompanyPolicy .modal-body').html(data.html);
                $('.modal-title').text(data.meta_title);
                $('#viewCompanyPolicy').modal('show');
                $('#target_id').val(id);
            },
            error: function () {
                alert('Failed to load Company Policy data.');
            }
        });
    }

    function markAsRead() {
        const id = $('#target_id').val();
        $.ajax({
            url: "{{ route('view.company-policies.mark-as-read', ':companyPolicy') }}".replace(':companyPolicy', id),
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                $('#viewCompanyPolicy').modal('hide');
                $('.datatables-policy').DataTable().ajax.reload();
            },
            error: function() {
                alert("Error marking as read. Please try again.");
            }
        });
    }
</script>
@endpush
