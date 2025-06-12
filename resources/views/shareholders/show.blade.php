@extends('layouts.app')

@section('title', 'Shareholder Details - Promoter Share Management')
@section('page-title', 'Shareholder Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $shareholder->name }}</h5>
                <div>
                    <a href="{{ route('shareholders.edit', $shareholder->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('shareholders.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Type:</th>
                                <td>
                                    <span class="badge bg-{{ $shareholder->type == 'individual' ? 'info' : 'warning' }}">
                                        {{ ucfirst($shareholder->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>
                                    <span class="badge bg-{{ $shareholder->category == 'promoter' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($shareholder->category) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Share Quantity:</th>
                                <td><strong>{{ number_format($shareholder->share_quantity) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Citizenship Number:</th>
                                <td>{{ $shareholder->citizenship_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td>{{ ucfirst($shareholder->gender ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Father's Name:</th>
                                <td>{{ $shareholder->father_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Grandfather's Name:</th>
                                <td>{{ $shareholder->grandfather_name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">BOID:</th>
                                <td>{{ $shareholder->boid ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Demat Account:</th>
                                <td>{{ $shareholder->demat_account ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>PAN Number:</th>
                                <td>{{ $shareholder->pan_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $shareholder->contact_details['phone'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $shareholder->contact_details['email'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $shareholder->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Contact Person:</th>
                                <td>{{ $shareholder->contact_person ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ $shareholder->is_active ? 'success' : 'danger' }}">
                                        {{ $shareholder->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sell Applications -->
        @if($shareholder->sellApplications->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Sell Applications</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Application Date</th>
                                <th>Share Quantity</th>
                                <th>Proposed Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shareholder->sellApplications as $application)
                            <tr>
                                <td>{{ $application->application_date->format('M d, Y') }}</td>
                                <td>{{ number_format($application->share_quantity_to_sell) }}</td>
                                <td>
                                    @if($application->proposed_price_per_share)
                                        Rs. {{ number_format($application->proposed_price_per_share, 2) }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{
                                        $application->status == 'pending' ? 'warning' :
                                        ($application->status == 'completed' ? 'success' : 'info')
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('sell-applications.show', $application->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- <div class="col-md-4">
        <!-- Documents -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Documents</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i class="fas fa-upload me-1"></i>Upload
                </button>
            </div>
            <div class="card-body">
                @if($shareholder->documents->count() > 0)
                    @foreach($shareholder->documents as $document)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <small class="fw-bold">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</small>
                            <br><small class="text-muted">{{ $document->file_name }}</small>
                        </div>
                        <div>
                            @if($document->status == 'verified')
                                <i class="fas fa-check-circle text-success" title="Verified"></i>
                            @else
                                <i class="fas fa-clock text-warning" title="Pending Verification"></i>
                                <form action="{{ route('documents.verify', $document->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Verify">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('documents.download', $document->id) }}" class="btn btn-sm btn-outline-primary" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No documents uploaded.</p>
                @endif
            </div>
        </div>
    </div> --}}
</div>

<!-- Upload Document Modal -->
{{-- <div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="documentable_type" value="shareholder">
                <input type="hidden" name="documentable_id" value="{{ $shareholder->id }}">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select class="form-select" name="document_type" required>
                            <option value="">Select Document Type</option>
                            <option value="citizenship">Citizenship</option>
                            <option value="tax_clearance">Tax Clearance</option>
                            <option value="cia_report">CIA Report</option>
                            <option value="moa_aoa">MOA & AOA</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" name="file" required>
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection
