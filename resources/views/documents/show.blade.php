@extends('layouts.app')

@section('title', 'Document Details - Promoter Share Management')
@section('page-title', 'Document Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Document Details</h5>
                <div>
                    <a href="{{ route('documents.download', $document->id) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                    <a href="{{ route('documents.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Document Type:</th>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>File Name:</th>
                                <td><strong>{{ $document->file_name }}</strong></td>
                            </tr>
                            <tr>
                                <th>File Type:</th>
                                <td>{{ $document->file_type }}</td>
                            </tr>
                            <tr>
                                <th>File Size:</th>
                                <td>{{ number_format($document->file_size / 1024, 2) }} KB</td>
                            </tr>
                            <tr>
                                <th>Upload Date:</th>
                                <td>{{ $document->upload_date->format('M d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Related To:</th>
                                <td>
                                    @if($document->documentable_type == 'App\Models\Shareholder')
                                        <span class="badge bg-info">Shareholder</span><br>
                                        <strong>{{ $document->documentable->name ?? 'N/A' }}</strong>
                                    @elseif($document->documentable_type == 'App\Models\SellApplication')
                                        <span class="badge bg-warning">Sell Application</span><br>
                                        <strong>{{ $document->documentable->seller->name ?? 'N/A' }}</strong>
                                    @elseif($document->documentable_type == 'App\Models\BuyApplication')
                                        <span class="badge bg-success">Buy Application</span><br>
                                        <strong>{{ $document->documentable->buyer_name ?? 'N/A' }}</strong>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Verification Status:</th>
                                <td>
                                    @if($document->is_verified)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Verified
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending Verification
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $document->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($document->remarks)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Remarks</h6>
                        <div class="border p-3 bg-light rounded">
                            {{ $document->remarks }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Verification Form -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Document Verification</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('documents.verify', $document->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="is_verified" class="form-label">Verification Status</label>
                                            <select class="form-select" id="is_verified" name="is_verified" required>
                                                <option value="0" {{ !$document->is_verified ? 'selected' : '' }}>Pending Verification</option>
                                                <option value="1" {{ $document->is_verified ? 'selected' : '' }}>Verified</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="verification_remarks" class="form-label">Verification Remarks</label>
                                            <textarea class="form-control" id="verification_remarks" name="remarks" rows="2">{{ $document->remarks }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Update Verification Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
