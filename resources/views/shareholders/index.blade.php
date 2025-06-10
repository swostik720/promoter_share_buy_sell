@extends('layouts.app')

@section('title', 'Shareholders - Promoter Share Management')
@section('page-title', 'Shareholders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>All Shareholders</h4>
    <a href="{{ route('shareholders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add New Shareholder
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Share Quantity</th>
                        <th>Citizenship/PAN</th>
                        <th>Demat Account</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shareholders as $shareholder)
                    <tr>
                        <td>
                            <strong>{{ $shareholder->name }}</strong>
                            @if($shareholder->contact_details && isset($shareholder->contact_details['email']))
                                <br><small class="text-muted">{{ $shareholder->contact_details['email'] }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $shareholder->type == 'individual' ? 'info' : 'warning' }}">
                                {{ ucfirst($shareholder->type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $shareholder->category == 'promoter' ? 'success' : 'secondary' }}">
                                {{ ucfirst($shareholder->category) }}
                            </span>
                        </td>
                        <td>{{ number_format($shareholder->share_quantity) }}</td>
                        <td>
                            @if($shareholder->citizenship_number)
                                <small>Citizenship: {{ $shareholder->citizenship_number }}</small><br>
                            @endif
                            @if($shareholder->pan_number)
                                <small>PAN: {{ $shareholder->pan_number }}</small>
                            @endif
                        </td>
                        <td>{{ $shareholder->demat_account ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('shareholders.show', $shareholder->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('shareholders.edit', $shareholder->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('shareholders.destroy', $shareholder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No shareholders found.</td>
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
