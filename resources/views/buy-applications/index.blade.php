@extends('layouts.app')

@section('title', 'Buy Applications - Promoter Share Management')
@section('page-title', 'Buy Applications')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>All Buy Applications</h4>
        <a href="{{ route('buy-applications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Buy Application
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Application Date</th>
                            <th>Buyer</th>
                            <th>Seller</th>
                            <th>Share Quantity</th>
                            <th>Offered Price</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ $application->application_date->format('M d, Y') }}</td>
                                <td>
                                    <strong>{{ $application->buyer_name }}</strong>
                                    <br><small class="text-muted">{{ ucfirst($application->buyer_type) }}
                                        {{ ucfirst(str_replace('_', ' ', $application->buyer_category)) }}</small>
                                </td>
                                <td>
                                    <strong>{{ $application->sellApplication->seller->name }}</strong>
                                    <br><small class="text-muted">Available:
                                        {{ number_format($application->sellApplication->share_quantity_to_sell) }}
                                        shares</small>
                                </td>
                                <td>{{ number_format($application->share_quantity_to_buy) }}</td>
                                <td>Rs. {{ number_format($application->offered_price_per_share, 2) }}</td>
                                <td>Rs.
                                    {{ number_format($application->share_quantity_to_buy * $application->offered_price_per_share, 2) }}
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $application->status == 'pending'
                                            ? 'warning'
                                            : ($application->status == 'approved'
                                                ? 'success'
                                                : ($application->status == 'rejected'
                                                    ? 'danger'
                                                    : 'info')) }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('buy-applications.show', $application->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($application->status == 'pending')
                                            <form action="{{ route('buy-applications.update-status', $application->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    onclick="return confirm('Approve this application?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('buy-applications.update-status', $application->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Reject this application?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No buy applications found.</td>
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
