@extends('layouts.app')

@section('title', 'Board Decisions - Promoter Share Management')
@section('page-title', 'Board Decisions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>All Board Decisions</h4>
    <a href="{{ route('board-decisions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Board Decision
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Decision Date</th>
                        <th>Sell Application</th>
                        <th>Seller</th>
                        <th>Share Quantity</th>
                        <th>Decision</th>
                        <th>Meeting Reference</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($decisions as $decision)
                    <tr>
                        <td>{{ $decision->decision_date->format('M d, Y') }}</td>
                        <td>
                            <small class="text-muted">Applied: {{ $decision->sellApplication->application_date->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <strong>{{ $decision->sellApplication->seller->name }}</strong>
                        </td>
                        <td>{{ number_format($decision->sellApplication->share_quantity_to_sell) }}</td>
                        <td>
                            <span class="badge bg-{{ $decision->decision == 'approved' ? 'success' : 'danger' }}">
                                {{ ucfirst($decision->decision) }}
                            </span>
                        </td>
                        <td>{{ $decision->meeting_minute_reference ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('board-decisions.show', $decision->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('board-decisions.edit', $decision->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No board decisions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $decisions->links() }}
        </div>
    </div>
</div>
@endsection
