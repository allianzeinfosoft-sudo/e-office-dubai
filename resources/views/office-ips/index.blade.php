@extends('layouts.app')

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
                    <h4 class="fw-bold py-3 mb-3"><span class="text-muted fw-light">Settings /</span> Office IPs</h4>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end mb-3">
                            <a class="btn btn-primary" href="{{ route('office-ips.create') }}">
                                <i class="ti ti-plus me-1"></i> Add Office IP
                            </a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>IP Address</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($officeIps as $index => $ip)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ip->ip_address }}</td>
                                        <td>
                                            @if($ip->status == 1)
                                                <span class="badge bg-label-success">Active</span>
                                            @else
                                                <span class="badge bg-label-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $ip->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('office-ips.edit', $ip->id) }}" class="btn btn-sm btn-primary">
                                                <i class="ti ti-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('office-ips.destroy', $ip->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="ti ti-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <x-footer />
            </div>
        </div>
    </div>
</div>
@endsection