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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tools /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn add-new btn-primary" href="javascript:void(0);" onclick="openMomOffcanvas()">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block"> New KSP</span>
                                </span>
                            </a>
                        </div>
                        @if($ksps->count() > 0)
                            @foreach($ksps as $ksp)
                            <div class="col-sm-3 mb-3">
                                <div class="card mb-3">
                                    <img class="card-img-top" src="{{ asset('storage/ksps/'.$ksp->ksp_featured_image) }}" alt="Card image cap">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center lh-1 me-3 mb-2 mb-sm-0">
                                            <span class="badge badge-dot bg-success me-1"></span>
                                            <span class="text-muted" style="font-size: 12px;">{{ $ksp->category->category_name ?? 'N/A' }}</span>
                                        </div>
                                        <h5 class="card-title">{{ Str::limit($ksp->ksp_title, 25) }}</h5>
                                        <p class="card-text"> {!!  Str::limit($ksp->ksp_description, 100) !!} </p>
                                        <p class="card-text"> <small class="text-muted">{{ '@'. $ksp->createdBy?->full_name ?? 'N/A' }} |  {{ \Carbon\Carbon::parse($ksp->created_at)->format('F j Y, h:i A') }}</small> </p>
                                        <a href="javascript:void(0);" class="card-link" onclick="viewKspModal({{ $ksp->id }})"><i class="ti ti-eye"></i></a> 
                                        <a href="javascript:void(0);" class="card-link" onclick="openMomOffcanvas({{ $ksp->id }})"><i class="ti ti-edit"></i></a> 
                                        <a href="javascript:void(0);" class="card-link text-danger" onclick="deleteKsp({{ $ksp->id }})"><i class="ti ti-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

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

<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="ksp_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-description fs-2 text-white"></i> 
            <span id="ksp-offcanvas-title">
                <h5 class="offcanvas-title text-white">Create KSP</h5>
                <span class="text-white slogan">Create New Knowledge Sharing Program</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-ksp-form />
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="viewKsp" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-0">
                <button type="button" class="btn-close zindex-5" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body m-0 p-0">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('tools.ksp.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="category_id">Category <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="category_name" id="category_name" required />
                            </div>
                        </div>
                        <div class="col-sm-12 mb-3">
                            <button type="button" onclick="addCategory()" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;  Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop

@push('js')
<script>
    const quillKsp  = new Quill('#ksp-details-editor', {
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
        
        $('#ksp-form').on('submit', function (e) {
            e.preventDefault();
            
            const kspDetailsInput = document.getElementById('ksp_description');
            kspDetailsInput.value = quillKsp.root.innerHTML;

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
                    quillKsp.root.innerHTML = ''; 
                    const offcanvasElement = document.getElementById('ksp_offcanvas');
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
                    if (offcanvas) offcanvas.hide();
                    window.location.reload();
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
        const $form = $('#ksp-form');
        $form[0].reset();
        $('#target_id').val('');
        $('#ksp-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Create KSP</h5><span class="text-white slogan">Create New Knowledge Sharing Program</span>`);

        const offcanvasElement = $('#ksp_offcanvas');

        if (offcanvasElement.length) {
            const offcanvas = new bootstrap.Offcanvas(offcanvasElement[0]);
            offcanvas.show();
        }

        if(id) {
            const url = "{{ route('tools.ksp.edit', ':ksp') }}".replace(':ksp', id);
            $('#target_id').val(id);
            $('#ksp-offcanvas-title').html(`<h5 class="offcanvas-title text-white">Edit KSP</h5><span class="text-white slogan">Edit Knowledge Sharing Program</span>`);
            $('#current-attachment').remove();
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#ksp_title').val(data.ksp.ksp_title);
                    $('#ksp_category').val(data.ksp.ksp_category).trigger('change');
                    $('#created_by').val(data.ksp.created_by).trigger('change');
                    const desc = data.ksp.ksp_description || '';
                    quillKsp.root.innerHTML = desc;
                    $('#ksp_details').val(data.ksp.ksp_description);
                    if (data.ksp.ksp_featured_image) {
                        const fileUrl = `/storage/ksps/${data.ksp.ksp_featured_image}`;
                        $('#ksp_featured_image').after(`
                            <div id="current-attachment" class="mt-2">
                                <img src="${fileUrl}" class="w-100" alt="ksp_title" style="max-width: 150px; max-height: 150px;" />
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

    function addCategoryModal() {
        $('#addCategoryModal').modal('show');
    }

    function addCategory() {
        const categoryName = $('#category_name').val();
        $.ajax({
            url: "{{ route('tools.ksp.store-category') }}",
            type: "POST",
            data: { category_name: categoryName, _token: "{{ csrf_token() }}" },
            success: function(response) {
                $('#addCategoryModal').modal('hide');
                toastr["success"](response.message);
                $('#ksp_category').append(`<option value="${response.data.id}" selected>${response.data.category_name}</option>`).trigger('change');
            },
            error: function() {
                alert("Error adding category. Please try again.");
            }
        });
    }

    function deleteKsp(id) {
        if (confirm('Are you sure you want to delete this KSP?')) {
            $.ajax({
                url: "{{ route('tools.ksp.destroy', ':ksp') }}".replace(':ksp', id),
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    toastr["error"](response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                },
                error: function() {
                    alert("Error deleting MOM. Please try again.");
                }
            });
        }
    }

    function viewKspModal(id) {
        const url = "{{ route('tools.ksp.show', ':ksp') }}".replace(':ksp', id);
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                $('#viewKsp .modal-body').html(data.html);
                $('#viewKsp .modal-title').text(data.meta_title);
                $('#viewKsp').modal('show');
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
