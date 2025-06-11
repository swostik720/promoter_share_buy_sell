@extends('layouts.app')

@section('title', 'Transactions - Promoter Share Management')
@section('page-title', 'Transactions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>All Transactions</h4>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>New Transaction
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Reference</th>
                        <th>Transaction Date</th>
                        <th>Seller</th>
                        <th>Buyer</th>
                        <th>Share Quantity</th>
                        <th>Price per Share</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>
                            <strong>{{ $transaction->transaction_reference }}</strong>
                        </td>
                        <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                        <td>
                            <strong>{{ $transaction->sellApplication->seller->name }}</strong>
                        </td>
                        <td>
                            <strong>{{ $transaction->buyApplication->buyer_name }}</strong>
                            <br><small class="text-muted">{{ ucfirst($transaction->buyApplication->buyer_type) }}</small>
                        </td>
                        <td>{{ number_format($transaction->share_quantity) }}</td>
                        <td>Rs. {{ number_format($transaction->price_per_share, 2) }}</td>
                        <td>Rs. {{ number_format($transaction->total_amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{
                                $transaction->status == 'pending' ? 'warning' :
                                ($transaction->status == 'completed' ? 'success' : 'danger')
                            }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($transaction->status == 'pending')
                                <form action="{{ route('transactions.complete', $transaction->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Complete this transaction?')">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
