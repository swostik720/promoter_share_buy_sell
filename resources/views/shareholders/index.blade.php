@extends('layouts.app')

@section('title', 'Promoter Share Holders - Promoter Share Management')
@section('page-title', 'List of Promoter Share Holders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Promoter Share Holders Database</h4>
    <a href="{{ route('shareholders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Share Holder
    </a>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Individual Promoters</h5>
                <h3>{{ $shareholders->where('type', 'individual')->where('category', 'promoter')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Institutional Promoters</h5>
                <h3>{{ $shareholders->where('type', 'institutional')->where('category', 'promoter')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Total Shares</h5>
                <h3>{{ number_format($shareholders->sum('share_quantity')) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Active Holders</h5>
                <h3>{{ $shareholders->where('is_active', true)->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h6 class="mb-0">Promoter Share Holder Details</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name of Share Holder</th>
                        <th>Share Quantity</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Demat Account</th>
                        <th>Contact Details</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shareholders as $shareholder)
                    <tr>
                        <td>
                            <strong>{{ $shareholder->name }}</strong>
                            @if($shareholder->citizenship_number)
                                <br><small class="text-muted">Citizenship: {{ $shareholder->citizenship_number }}</small>
                            @endif
                            @if($shareholder->pan_number)
                                <br><small class="text-muted">PAN: {{ $shareholder->pan_number }}</small>
                            @endif
                        </td>
                        <td>
                            <strong class="text-primary">{{ number_format($shareholder->share_quantity) }}</strong>
                        </td>
                        <td>
                            @if($shareholder->type == 'individual')
                                <span class="badge bg-info">Individual</span>
                            @else
                                <span class="badge bg-warning">Institutional</span>
                            @endif
                        </td>
                        <td>
                            @if($shareholder->category == 'promoter')
                                <span class="badge bg-success">Promoter</span>
                            @else
                                <span class="badge bg-secondary">Public</span>
                            @endif
                        </td>
                        <td>{{ $shareholder->demat_account ?? 'N/A' }}</td>
                        <td>
                            @if($shareholder->contact_details)
                                @if(isset($shareholder->contact_details['phone']))
                                    <small>Phone: {{ $shareholder->contact_details['phone'] }}</small><br>
                                @endif
                                @if(isset($shareholder->contact_details['email']))
                                    <small>Email: {{ $shareholder->contact_details['email'] }}</small>
                                @endif
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('shareholders.show', $shareholder->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('shareholders.edit', $shareholder->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($shareholder->category == 'promoter')
                                <a href="{{ route('sell-applications.create') }}?seller_id={{ $shareholder->id }}" class="btn btn-sm btn-outline-success" title="Initiate Sell Process">
                                    <i class="fas fa-file-export"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No share holders found in database.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $shareholders->links() }}
        </div>
    </div>
</div>
@endsection
