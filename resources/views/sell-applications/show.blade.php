@extends('layouts.app')

@section('title', 'Sell Application Details - Promoter Share Management')
@section('page-title', 'Sell Application Details')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sell Application #{{ $application->id }}</h5>
                <div>
                    @if($application->status == 'pending')
                    <a href="{{ route('sell-applications.edit', $application->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    @endif
                    <a href="{{ route('sell-applications.index') }}" class="btn btn-sm btn-secondary">
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
                                <th>Seller:</th>
                                <td>
                                    <strong>{{ $application->seller->name }}</strong>
                                    <br><small class="text-muted">{{ ucfirst($application->seller->type) }} {{ ucfirst($application->seller->category) }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Share Quantity to Sell:</th>
                                <td><strong>{{ number_format($application->share_quantity_to_sell) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Proposed Price per Share:</th>
                                <td>
                                    @if($application->proposed_price_per_share)
                                        Rs. {{ number_format($application->proposed_price_per_share, 2) }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Status:</th>
                                <td>
                                    <span class="badge bg-{{
                                        $application->status == 'pending' ? 'warning' :
                                        ($application->status == 'board_approved' ? 'info' :
                                        ($application->status == 'board_rejected' ? 'danger' :
                                        ($application->status == 'notice_published' ? 'primary' :
                                        ($application->status == 'completed' ? 'success' : 'secondary'))))
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Reason:</th>
                                <td>{{ $application->reason ?? 'N/A' }}</td>
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
            </div>
        </div>

        <!-- Board Decision -->
        @if($application->boardDecision)
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Board Decision</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Decision:</strong>
                        <span class="badge bg-{{ $application->boardDecision->decision == 'approved' ? 'success' : 'danger' }}">
                            {{ ucfirst($application->boardDecision->decision) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Decision Date:</strong> {{ $application->boardDecision->decision_date->format('M d, Y') }}
                    </div>
                </div>
                @if($application->boardDecision->decision_remarks)
                <div class="mt-2">
                    <strong>Remarks:</strong> {{ $application->boardDecision->decision_remarks }}
                </div>
                @endif
                @if($application->boardDecision->meeting_minute_reference)
                <div class="mt-2">
                    <strong>Meeting Reference:</strong> {{ $application->boardDecision->meeting_minute_reference }}
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Notice Publication -->
        @if($application->noticePublication)
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Notice Publication</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Publication Date:</strong> {{ $application->noticePublication->publication_date->format('M d, Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Newspaper:</strong> {{ $application->noticePublication->newspaper_name }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Buy Applications -->
        @if($application->buyApplications->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Buy Applications ({{ $application->buyApplications->count() }})</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Quantity</th>
                                <th>Offered Price</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($application->buyApplications as $buyApp)
                            <tr>
                                <td>
                                    <strong>{{ $buyApp->buyer_name }}</strong>
                                    <br><small class="text-muted">{{ ucfirst($buyApp->buyer_type) }}</small>
                                </td>
                                <td>{{ number_format($buyApp->share_quantity_to_buy) }}</td>
                                <td>Rs. {{ number_format($buyApp->offered_price_per_share, 2) }}</td>
                                <td>Rs. {{ number_format($buyApp->share_quantity_to_buy * $buyApp->offered_price_per_share, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{
                                        $buyApp->status == 'pending' ? 'warning' :
                                        ($buyApp->status == 'approved' ? 'success' :
                                        ($buyApp->status == 'rejected' ? 'danger' : 'info'))
                                    }}">
                                        {{ ucfirst($buyApp->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('buy-applications.show', $buyApp->id) }}" class="btn btn-sm btn-outline-info">
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

    <div class="col-md-4">
        <!-- Documents -->
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Documents</h6>
                {{-- <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                    <i class="fas fa-upload me-1"></i>Upload
                </button> --}}
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
        <div class="card shadow mt-4">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                @if($application->status == 'pending')
                    <a href="{{ route('board-decisions.create') }}?sell_application_id={{ $application->id }}" class="btn btn-sm btn-success w-100 mb-2">
                        <i class="fas fa-gavel me-1"></i>Record Board Decision
                    </a>
                @endif

                @if($application->status == 'board_approved')
                    <a href="{{ route('notice-publications.create') }}?sell_application_id={{ $application->id }}" class="btn btn-sm btn-info w-100 mb-2">
                        <i class="fas fa-newspaper me-1"></i>Publish Notice
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
{{-- <div class="modal fade" id="uploadDocumentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="documentable_type" value="App\Models\SellApplication">
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
