@extends('layouts.app')

@section('title', 'Documents - Promoter Share Management')
@section('page-title', 'Document Management')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Documents</h5>
                    <div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-2"></i>Upload Document
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Document Type</th>
                                    <th>File Name</th>
                                    <th>Related To</th>
                                    <th>Upload Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ ucwords(str_replace('_', ' ', $document->document_type)) }}</span>
                                        </td>
                                        <td>{{ $document->file_name }}</td>
                                        <td>
                                            @if ($document->documentable_type === 'App\Models\SellApplication')
                                                <span class="text-primary">Sell Application
                                                    #{{ $document->documentable_id }}</span>
                                            @elseif($document->documentable_type === 'App\Models\BuyApplication')
                                                <span class="text-success">Buy Application
                                                    #{{ $document->documentable_id }}</span>
                                            @else
                                                {{ class_basename($document->documentable_type) }}
                                                #{{ $document->documentable_id }}
                                            @endif
                                        </td>
                                        <td>{{ $document->upload_date->format('M d, Y') }}</td>
                                        <td>
                                            @if ($document->is_verified)
                                                <span class="badge bg-success">Verified</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('documents.view', $document->id) }}"
                                                    class="btn btn-outline-primary" target="_blank">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('documents.download', $document->id) }}"
                                                    class="btn btn-outline-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if (!$document->is_verified)
                                                    <button class="btn btn-outline-info"
                                                        onclick="verifyDocument({{ $document->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-danger"
                                                    onclick="deleteDocument({{ $document->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No documents found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $documents->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="documentable_type" class="form-label">Related To</label>
                            <select class="form-select" name="documentable_type" required>
                                <option value="">Select Type</option>
                                <option value="App\Models\SellApplication">Sell Application</option>
                                <option value="App\Models\BuyApplication">Buy Application</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="documentable_id" class="form-label">Application ID</label>
                            <input type="number" class="form-control" name="documentable_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="document_type" class="form-label">Document Type</label>
                            <select class="form-select" name="document_type" required>
                                <option value="">Select Document Type</option>
                                <option value="sell_application">Sell Application</option>
                                <option value="seller_citizenship">Seller Citizenship</option>
                                <option value="seller_tax_clearance">Seller Tax Clearance</option>
                                <option value="seller_cia_report">Seller CIA Report</option>
                                <option value="buy_application">Buy Application</option>
                                <option value="buyer_citizenship">Buyer Citizenship</option>
                                <option value="combine_application">Combine Application</option>
                                <option value="police_report">Police Report</option>
                                <option value="self_declaration">Self Declaration</option>
                                <option value="sebbon_notification">SEBBON Notification</option>
                                <option value="nepse_notification">NEPSE Notification</option>
                                <option value="nia_notification">NIA Notification</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label">File</label>
                            <input type="file" class="form-control" name="file" accept=".pdf,.jpg,.jpeg,.png"
                                required>
                            <small class="form-text text-muted">PDF, JPG, PNG (Max: 5MB)</small>
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

@section('scripts')
    <script>
        function verifyDocument(id) {
            if (confirm('Are you sure you want to verify this document?')) {
                fetch(`/documents/${id}/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            is_verified: true
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    });
            }
        }

        function deleteDocument(id) {
            if (confirm('Are you sure you want to delete this document?')) {
                fetch(`/documents/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload();
                        }
                    });
            }
        }
    </script>
@endsection
