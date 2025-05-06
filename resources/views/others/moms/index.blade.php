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
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openMomOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New MOM</span>
                                </span>
                            </a>
                        </div>

                        <div class="card">
                            <div class="card-datatable table-mom">
                                <table class="datatables-basic datatables-mom table border-top table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Created By</th>
                                            <th>Assigned To</th>
                                            <th>Attachment</th>
                                            <th>Status</th>
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

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="mom_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="mom-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create MOM</h5>
                <span class="text-white slogan">Create New Minutes of Meeting</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-mom-form />
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="viewMOM" tabindex="-1" style="display: none;" aria-hidden="true">
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
    const quillMom  = new Quill('#mom-details-editor', {
        theme: 'snow',
        placeholder: 'Type event description here...',
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

        var momTable = $('.datatables-mom');

        if (momTable.length) {
    const assetBaseUrl = "{{ asset('storage/moms') }}";

    momTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: "GET",
            url: "{{ route('others.moms.index') }}",
            dataType: "json",
            dataSrc: "data"
        },
        columns: [
            { data: 'row', name: 'No' },
            { data: 'mom_title', name: 'Title' },
            { data: 'mom_date', name: 'Date' },
            { data: 'created_by', name: 'Created By' },
            { 
                data: 'assigned_to', 
                name: 'Assigned To',
                render: function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        return data.map(user => `<span class="badge bg-info me-1">${user.full_name}</span>`).join(' ');
                    } else {
                        return '-';
                    }
                }
            },
            { 
                data: 'attachments',
                title: 'Attachments',
                render: function (data) {
                    return data ? 
                        `<a href="${assetBaseUrl}/${data}" target="_blank" class="btn btn-sm btn-icon btn-outline-success"><i class="ti ti-download"></i></a>` 
                        : '-';
                }
            },
            { data: 'status', name: 'Status' },
            { 
                data: null,
                title: 'Actions',
                render: function (data, type, row) {
                    return `
                        <a href="javascript:void(0)" onclick="viewMomModal('${row.id}')" class="btn btn-sm btn-icon btn-success">
                            <i class="ti ti-eye"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="openMomOffcanvas(${row.id})" class="btn btn-sm btn-icon btn-primary">
                            <i class="ti ti-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-danger" onclick="deleteMom(${row.id})">
                            <i class="ti ti-trash"></i>
                        </button>`;
                }
            }
        ]
    });
}

        $('#mom-form').on('submit', function (e) {
            e.preventDefault();
            
            const momDetailsInput = document.getElementById('mom_details');
            momDetailsInput.value = quillMom.root.innerHTML;

            const form = $(this);
            const formData = new FormData(this);
            const url = form.attr('action');

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
                    quillMom.root.innerHTML = ''; 
                    const offcanvasElement = document.getElementById('mom_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) offcanvas.hide();
                    momTable.DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    let message = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
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

    function openMomOffcanvas(id = null) {
        const $form = $('#mom-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#mom-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create MOM</h5><span class="text-white slogan">Create New Minutes of Meeting</span>`);

        const offcanvasElement = $('#mom_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if (id) {
            const url = "{{ route('others.moms.edit', ':mom') }}".replace(':mom', id);
            $('#target_id').val(id);
            $('#mom-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit MOM</h5><span class="text-white slogan">Edit Minutes of Meeting</span>`);
            $('#current-attachment').remove();
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#mom_title').val(data.mom.mom_title);
                    $('#mom_date').flatpickr().setDate(data.mom.mom_date, true);
                    $('#assigned_to').val(data.mom.assigned_to).trigger('change');
                    $('#created_by').val(data.mom.created_by).trigger('change');
                    $('#status').val(data.mom.status).trigger('change');
                    const desc = data.mom.mom_details || '';
                    quillMom.root.innerHTML = desc;
                    $('#mom_details').val(data.mom.mom_details);
                    if (data.mom.attachment) {
                        const fileUrl = `/storage/moms/${data.mom.attachment}`;
                        $('#attachments').after(`
                            <div id="current-attachment" class="mt-2">
                                <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary"> <i class="ti ti-pin me-1"></i> ${data.mom.attachment} </a>
                            </div>
                        `);
                    } else {
                        $('#current-attachment').remove();
                    }
                },
                error: function () {
                    alert('Failed to load MOM data.');
                }
            });
        }
    }

    function deleteMom(id) {
        if (confirm('Are you sure you want to delete this MOM?')) {
            $.ajax({
                url: "{{ route('others.moms.destroy', ':mom') }}".replace(':mom', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    $('.datatables-mom').DataTable().ajax.reload();
                },
                error: function() {
                    alert("Error deleting MOM. Please try again.");
                }
            });
        }
    }

    function viewMomModal(id) {
        const url = "{{ route('others.moms.show', ':mom') }}".replace(':mom', id);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#viewMOM .modal-body').html(data.html);
                $('.modal-title').text(data.meta_title);
                $('#viewMOM').modal('show');
                $('#target_id').val(id);
            },
            error: function () {
                alert('Failed to load MOM data.');
            }
        });
    }

    function markAsRead() {
        const id = $('#target_id').val();
        $.ajax({
            url: "{{ route('others.moms.mark-as-read', ':mom') }}".replace(':mom', id),
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                $('#viewMOM').modal('hide');
                $('.datatables-mom').DataTable().ajax.reload();
            },
            error: function() {
                alert("Error marking as read. Please try again.");
            }
        });
    }

</script>
@endpush
