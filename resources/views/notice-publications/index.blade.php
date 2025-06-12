@extends('layouts.app')

@section('title', 'Notice Publications - Promoter Share Management')
@section('page-title', 'Notice Publications')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Notices</h6>
                        <h2 class="mt-2 mb-0">{{ $totalNotices }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-newspaper fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">This Month</h6>
                        <h2 class="mt-2 mb-0">{{ $thisMonthNotices }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Pending Applications</h6>
                        <h2 class="mt-2 mb-0">{{ \App\Models\SellApplication::where('status', 'board_approved')->count() }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-hourglass-half fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Active Notices</h6>
                        <h2 class="mt-2 mb-0">{{ \App\Models\SellApplication::where('status', 'notice_published')->count() }}</h2>
                    </div>
                    <div>
                        <i class="fas fa-bullhorn fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Notice Publications</h5>
        <a href="{{ route('notice-publications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Notice
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Seller</th>
                        <th>Share Quantity</th>
                        <th>Publication Date</th>
                        <th>Newspaper</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notices as $notice)
                        <tr>
                            <td>{{ $notice->id }}</td>
                            <td>
                                <a href="{{ route('shareholders.show', $notice->sellApplication->seller_id) }}">
                                    {{ $notice->sellApplication->seller->name }}
                                </a>
                            </td>
                            <td>{{ number_format($notice->sellApplication->share_quantity_to_sell) }}</td>
                            <td>{{ $notice->publication_date->format('M d, Y') }}</td>
                            <td>{{ $notice->newspaper_name }}</td>
                            <td>
                                <span class="badge bg-{{ $notice->sellApplication->status == 'notice_published' ? 'success' : 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $notice->sellApplication->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('notice-publications.show', $notice->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('notice-publications.edit', $notice->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($notice->documents && $notice->documents->count() > 0)
                                        <a href="{{ route('documents.download', $notice->documents->first()->id) }}" class="btn btn-sm btn-outline-success" title="Download Notice">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No notice publications found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $notices->links() }}
    </div>
</div>
@endsection
