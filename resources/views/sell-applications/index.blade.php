@extends('layouts.app')

@section('title', 'Sell Applications - Promoter Share Management')
@section('page-title', 'Sell Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>All Sell Applications</h4>
    <a href="{{ route('sell-applications.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Sell Application
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Application Date</th>
                        <th>Seller</th>
                        <th>Share Quantity</th>
                        <th>Proposed Price</th>
                        <th>Status</th>
                        <th>Board Decision</th>
                        <th>Notice Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td>{{ $application->application_date->format('M d, Y') }}</td>
                        <td>
                            <strong>{{ $application->seller->name }}</strong>
                            <br><small class="text-muted">{{ ucfirst($application->seller->type) }} {{ ucfirst($application->seller->category) }}</small>
                        </td>
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
                                ($application->status == 'board_approved' ? 'info' : 
                                ($application->status == 'board_rejected' ? 'danger' : 
                                ($application->status == 'notice_published' ? 'primary' : 
                                ($application->status == 'completed' ? 'success' : 'secondary')))) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </td>
                        <td>
                            @if($application->boardDecision)
                                <span class="badge bg-{{ $application->boardDecision->decision == 'approved' ? 'success' : 'danger' }}">
                                    {{ ucfirst($application->boardDecision->decision) }}
                                </span>
                                <br><small class="text-muted">{{ $application->boardDecision->decision_date->format('M d, Y') }}</small>
                            @else
                                <span class="text-muted">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($application->noticePublication)
                                <i class="fas fa-check text-success"></i> {{ $application->noticePublication->newspaper_name }}
                                <br><small class="text-muted">{{ $application->noticePublication->publication_date->format('M d, Y') }}</small>
                            @else
                                <span class="text-muted">Not published</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('sell-applications.show', $application->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($application->status == 'pending')
                                <a href="{{ route('sell-applications.edit', $application->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No sell applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
