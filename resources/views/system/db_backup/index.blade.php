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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> {{ $meta_title }}</h4>

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" onclick="startBackup()">Generate Backup</button>
                        </div>

                        <div class="card">
                            <h5 class="card-header">{{ $meta_title }}</h5>
                            <div class="card-datatable">
                               <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>File</th>
                                        <th>Size</th>
                                        <th width="25%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($backups as $file)
                                        <tr>
                                            <td>{{ basename($file) }}</td>
                                            <td>{{ Storage::size($file) / 1024 | number_format(2) }} KB</td>
                                            <td>
                                                <a href="{{ route('db.backup.download', basename($file)) }}" class="btn btn-sm btn-success"><i class="ti ti-download"></i> &nbsp;&nbsp;Download</a>
                                                <form action="{{ route('db.backup.delete', basename($file)) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"> <i class="ti ti-trash"></i> &nbsp;&nbsp; Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3">No backups found.</td></tr>
                                    @endforelse
                                </tbody>
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


<!-- Modal -->
<div class="modal fade" data-backdrop="static" id="backupProgressModal" tabindex="-1" aria-labelledby="backupProgressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h5 class="mb-3">Generating Backup...</h5>
      <div class="progress" style="height: 20px;">
        <div id="backupProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
             role="progressbar" style="width: 0%">0%</div>
      </div>
    </div>
  </div>
</div>

@stop

@push('js')
<script>
function startBackup() {
    // Reset bar
    $('#backupProgressBar').css('width', '0%').text('0%');
    $('#backupProgressModal').modal('show');

    // Fake progress bar (not real progress, just UX)
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        if (progress <= 90) {
            $('#backupProgressBar').css('width', progress + '%').text(progress + '%');
        }
    }, 500);

    // Send request
    fetch("{{ route('db-backup.generate') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    })
    .then(response => response.json())
    .then(data => {
        clearInterval(interval);
        $('#backupProgressBar').css('width', '100%').text('100%');

        setTimeout(() => {
            $('#backupProgressModal').modal('hide');
            toastr.success("Backup completed successfully.");
            location.reload();
        }, 800);
    })
    .catch(error => {
        clearInterval(interval);
        $('#backupProgressModal').modal('hide');
        toastr.error("Backup failed.");
    });
}
</script>
@endpush
