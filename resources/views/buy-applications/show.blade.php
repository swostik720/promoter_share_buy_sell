@extends('layouts.app')

@section('title', 'Buy Application Details - Promoter Share Management')
@section('page-title', 'Buy Application Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Buy Application #{{ $application->id }}</h5>
                <div>
                    @if($application->status == 'pending')
                    <form action="{{ route('buy-applications.update-status', $application->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this application?')">
                            <i class="fas fa-check me-1"></i>Approve
                        </button>
                    </form>
                    <form action="{{ route('buy-applications.update-status', $application->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this application?')">
                            <i class="fas fa-times me-1"></i>Reject
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('buy-applications.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Application Date:</th>
                                <td>{{ $application->application_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Buyer Name:</th>
                                <td><strong>{{ $application->buyer_name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Buyer Type:</th>
                                <td>
                                    <span class="badge bg-{{ $application->buyer_type == 'individual' ? 'info' : 'warning' }}">
                                        {{ ucfirst($application->buyer_type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Buyer Category:</th>
                                <td>
                                    <span class="badge bg-{{ $application->buyer_category == 'existing_promoter' ? 'success' : 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->buyer_category)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Share Quantity:</th>
                                <td><strong>{{ number_format($application->share_quantity_to_buy) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Offered Price per Share:</th>
                                <td>Rs. {{ number_format($application->offered_price_per_share, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount:</th>
                                <td><strong>Rs. {{ number_format($application->share_quantity_to_buy * $application->offered_price_per_share, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-{{ 
                                        $application->status == 'pending' ? 'warning' : 
                                        ($application->status == 'approved' ? 'success' : 
                                        ($application->status == 'rejected' ? 'danger' : 'info')) 
                                    }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $application->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $application->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Contact Details</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Citizenship:</strong><br>
                                {{ $application->citizenship_number ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>PAN:</strong><br>
                                {{ $application->pan_number ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Demat Account:</strong><br>
                                {{ $application->demat_account ?? 'N/A' }}
                            </div>
                            <div class="col-md-3">
                                <strong>Contact:</strong><br>
                                @if($application->contact_details)
                                    @if(isset($application->contact_details['phone']))
                                        Phone: {{ $application->contact_details['phone'] }}<br>
                                    @endif
                                    @if(isset($application->contact_details['email']))
                                        Email: {{ $application->contact_details['email'] }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Sell Application -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Related Sell Application</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Seller:</strong> {{ $application->sellApplication->seller->name }}<br>
                        <strong>Application Date:</strong> {{ $application->sellApplication->application_date->format('M d, Y') }}<br>
                        <strong>Available Quantity:</strong> {{ number_format($application->sellApplication->share_quantity_to_sell) }}
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong> 
                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $application->sellApplication->status)) }}</span><br>
                        @if($application->sellApplication->proposed_price_per_share)
                            <strong>Proposed Price:</strong> Rs. {{ number_format($application->sellApplication->proposed_price_per_share, 2) }}
                        @endif
                    </div>
                </div>
                <div class="mt-2">
                    <a href="{{ route('sell-applications.show', $application->sellApplication->id) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-eye me-1"></i>View Sell Application
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Documents -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Documents</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i class="fas fa-upload me-1"></i>Upload
                </button>
            </div>
            <div class="card-body">
                @if($application->documents->count() > 0)
                    @foreach($application->documents as $document)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <small class="fw-bold">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</small>
                            <br><small class="text-muted">{{ $document->file_name }}</small>
                        </div>
                        <div>
                            @if($document->is_verified)
                                <i class="fas fa-check-circle text-success" title="Verified"></i>
                            @else
                                <i class="fas fa-clock text-warning" title="Pending Verification"></i>
                            @endif
                            <a href="{{ route('documents.download', $document->id) }}" class="btn btn-sm btn-outline-primary ms-1">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No documents uploaded.</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        @if($application->status == 'approved')
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('transactions.create') }}?buy_application_id={{ $application->id }}" class="btn btn-sm btn-success w-100">
                    <i class="fas fa-exchange-alt me-1"></i>Create Transaction
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="documentable_type" value="App\Models\BuyApplication">
                <input type="hidden" name="documentable_id" value="{{ $application->id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select class="form-select" name="document_type" required>
                            <option value="">Select Document Type</option>
                            <option value="application">Application</option>
                            <option value="citizenship">Citizenship</option>
                            <option value="tax_clearance">Tax Clearance</option>
                            <option value="cia_report">CIA Report</option>
                            <option value="income_source">Income Source</option>
                            <option value="police_report">Police Report</option>
                            <option value="self_declaration">Self Declaration</option>
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
</div>
@endsection
