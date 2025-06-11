@extends('layouts.app')

@section('title', 'Notice Publications - Promoter Share Management')
@section('page-title', 'Notice Publications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted">Manage newspaper notice publications for approved sell applications</p>
    </div>
    @if(auth()->user()->canEdit())
        <a href="{{ route('notice-publications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Notice Publication
        </a>
    @endif
</div>

<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0">Notice Publications List</h5>
    </div>
    <div class="card-body">
        @if($notices->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Sell Application</th>
                            <th>Seller</th>
                            <th>Publication Date</th>
                            <th>Newspaper</th>
                            <th>Notice Reference</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notices as $notice)
                            <tr>
                                <td>{{ $notice->id }}</td>
                                <td>
                                    <a href="{{ route('sell-applications.show', $notice->sellApplication->id) }}" class="text-decoration-none">
                                        #{{ $notice->sellApplication->id }}
                                    </a>
                                </td>
                                <td>{{ $notice->sellApplication->seller->name }}</td>
                                <td>{{ $notice->publication_date->format('M d, Y') }}</td>
                                <td>{{ $notice->newspaper_name }}</td>
                                <td>
                                    @if($notice->notice_reference)
                                        <span class="badge bg-info">{{ $notice->notice_reference }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notice->sellApplication->status === 'notice_published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('notice-publications.show', $notice->id) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->canEdit())
                                            <a href="{{ route('notice-publications.edit', $notice->id) }}" class="btn btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $notices->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Notice Publications Found</h5>
                <p class="text-muted">No notice publications have been recorded yet.</p>
                @if(auth()->user()->canEdit())
                    <a href="{{ route('notice-publications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Notice Publication
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Notices</h6>
                        <h3 class="mb-0">{{ $notices->total() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-newspaper fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Published</h6>
                        <h3 class="mb-0">{{ $notices->where('sellApplication.status', 'notice_published')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">This Month</h6>
                        <h3 class="mb-0">{{ $notices->where('publication_date', '>=', now()->startOfMonth())->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Pending</h6>
                        <h3 class="mb-0">{{ $notices->where('sellApplication.status', '!=', 'notice_published')->count() }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
