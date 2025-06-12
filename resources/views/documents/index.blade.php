@section('title', 'Documents - Promoter Share Management')
@section('page-title', 'Document Management')

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Documents</h5>
                </div>
                <div class="card-body">
                    @php
                        $groupedDocuments = $documents->groupBy(function ($doc) {
                            return $doc->documentable_type . '-' . $doc->documentable_id;
                        });
                    @endphp

                    <div class="accordion" id="documentsAccordion">
                        @forelse ($groupedDocuments as $groupKey => $docs)
                            @php
                                [$type, $id] = explode('-', $groupKey);
                                $label = match(class_basename($type)) {
                                    'SellApplication' => 'Sell Application',
                                    'BuyApplication' => 'Buy Application',
                                    default => class_basename($type),
                                };
                                $groupId = Str::slug($label . '-' . $id);
                            @endphp

                            <div class="accordion-item mb-2">
                                <h2 class="accordion-header" id="heading-{{ $groupId }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $groupId }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $groupId }}">
                                        {{ $label }} #{{ $id }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $groupId }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading-{{ $groupId }}" data-bs-parent="#documentsAccordion">
                                    <div class="accordion-body">
                                        <table class="table table-hover align-middle table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Document Type</th>
                                                    <th>File Name</th>
                                                    <th>Upload Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($docs as $document)
                                                    <tr data-doc-id="{{ $document->id }}">
                                                        <td>
                                                            <span class="badge bg-info">
                                                                {{ ucwords(str_replace('_', ' ', $document->document_type)) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $document->file_name }}</td>
                                                        <td>{{ $document->upload_date->format('M d, Y') }}</td>
                                                        <td class="status-badge">
                                                            @if ($document->is_verified)
                                                                <span class="badge bg-success">Verified</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @endif
                                                            <div>
                                                                <small class="text-muted">Status: {{ ucfirst($document->status) }}</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('documents.view', $document->id) }}"
                                                                    class="btn btn-outline-primary" target="_blank"
                                                                    title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ route('documents.download', $document->id) }}"
                                                                    class="btn btn-outline-success" title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>

                                                                @if (!$document->is_verified)
                                                                    <button onclick="verifyDocument({{ $document->id }})"
                                                                        class="btn btn-outline-warning btn-verify"
                                                                        data-id="{{ $document->id }}">
                                                                        <i class="fas fa-check"></i>
                                                                    </button>
                                                                @endif

                                                                <button class="btn btn-outline-danger"
                                                                    onclick="deleteDocument({{ $document->id }})"
                                                                    title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted">No documents found</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSRF token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        function verifyDocument(id) {
            if (!confirm("Are you sure you want to verify this document?")) return;

            fetch(`/documents/${id}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({})
            })
            .then(res => {
                if (!res.ok) throw new Error('Verification failed');
                return res.json();
            })
            .then(data => {
                const row = document.querySelector(`[data-doc-id="${id}"]`);
                if (row) {
                    row.querySelector('.status-badge').innerHTML = `
                        <span class="badge bg-success">Verified</span>
                        <div><small class="text-muted">Status: Approved</small></div>
                    `;
                    const btn = row.querySelector('.btn-verify');
                    if (btn) btn.remove();
                }
            })
            .catch(err => alert(err.message));
        }
    </script>
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
                        body: JSON.stringify({})
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Error response:', text);
                                throw new Error('Server error: ' + response.status);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        const row = document.querySelector(`[data-doc-id="${id}"]`);
                        if (row) {
                            const statusCell = row.querySelector('.status-badge');
                            if (statusCell) {
                                statusCell.innerHTML = `
                            <span class="badge bg-success">Verified</span>
                            <div><small class="text-muted">Status: Approved</small></div>
                        `;
                            }

                            const verifyBtn = row.querySelector('.btn-verify');
                            if (verifyBtn) {
                                verifyBtn.remove(); // Remove the verify button after successful verification
                            }
                        }
                    })
                    .catch(error => {
                        alert('Verification failed: ' + error.message);
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
