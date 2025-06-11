@extends('layouts.app')

@section('title', 'Transaction Details - Promoter Share Management')
@section('page-title', 'Transaction Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Transaction #{{ $transaction->transaction_reference }}</h5>
                <div>
                    @if($transaction->status == 'pending')
                    <form action="{{ route('transactions.complete', $transaction->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Complete this transaction? This action cannot be undone.')">
                            <i class="fas fa-check-circle me-1"></i>Complete Transaction
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Transaction Reference:</th>
                                <td><strong>{{ $transaction->transaction_reference }}</strong></td>
                            </tr>
                            <tr>
                                <th>Transaction Date:</th>
                                <td>{{ $transaction->transaction_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Share Quantity:</th>
                                <td><strong>{{ number_format($transaction->share_quantity) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Price per Share:</th>
                                <td>Rs. {{ number_format($transaction->price_per_share, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount:</th>
                                <td><strong class="text-success">Rs. {{ number_format($transaction->total_amount, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Status:</th>
                                <td>
                                    <span class="badge bg-{{
                                        $transaction->status == 'pending' ? 'warning' :
                                        ($transaction->status == 'completed' ? 'success' : 'danger')
                                    }} fs-6">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>{{ $transaction->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if($transaction->status == 'completed' && $transaction->regulatory_notifications)
                            <tr>
                                <th>Regulatory Notifications:</th>
                                <td>
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> SEBBON, NEPSE, NIA Notified
                                    </small>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Seller Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="30%">Name:</th>
                                        <td><strong>{{ $transaction->sellApplication->seller->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Type:</th>
                                        <td>{{ ucfirst($transaction->sellApplication->seller->type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>{{ ucfirst($transaction->sellApplication->seller->category) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Demat Account:</th>
                                        <td>{{ $transaction->sellApplication->seller->demat_account ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                                <a href="{{ route('shareholders.show', $transaction->sellApplication->seller->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye me-1"></i>View Seller
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">Buyer Information</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="30%">Name:</th>
                                        <td><strong>{{ $transaction->buyApplication->buyer_name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Type:</th>
                                        <td>{{ ucfirst($transaction->buyApplication->buyer_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>{{ ucfirst(str_replace('_', ' ', $transaction->buyApplication->buyer_category)) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Demat Account:</th>
                                        <td>{{ $transaction->buyApplication->demat_account ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                                <a href="{{ route('buy-applications.show', $transaction->buyApplication->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye me-1"></i>View Buy Application
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Applications -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Related Applications</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border p-3 rounded">
                                    <strong>Sell Application:</strong><br>
                                    Applied: {{ $transaction->sellApplication->application_date->format('M d, Y') }}<br>
                                    Status: <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $transaction->sellApplication->status)) }}</span><br>
                                    <a href="{{ route('sell-applications.show', $transaction->sellApplication->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border p-3 rounded">
                                    <strong>Buy Application:</strong><br>
                                    Applied: {{ $transaction->buyApplication->application_date->format('M d, Y') }}<br>
                                    Status: <span class="badge bg-success">{{ ucfirst($transaction->buyApplication->status) }}</span><br>
                                    <a href="{{ route('buy-applications.show', $transaction->buyApplication->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
