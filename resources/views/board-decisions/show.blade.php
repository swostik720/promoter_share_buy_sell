@extends('layouts.app')

@section('title', 'Board Decision Details - Promoter Share Management')
@section('page-title', 'Board Decision Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Board Decision #{{ $decision->id }}</h5>
                <div>
                    <a href="{{ route('board-decisions.edit', $decision->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('board-decisions.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Decision Date:</th>
                                <td>{{ $decision->decision_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Decision:</th>
                                <td>
                                    <span class="badge bg-{{ $decision->decision == 'approved' ? 'success' : 'danger' }} fs-6">
                                        {{ ucfirst($decision->decision) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Meeting Reference:</th>
                                <td>{{ $decision->meeting_minute_reference ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $decision->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Related Sell Application</h6>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Seller:</th>
                                <td><strong>{{ $decision->sellApplication->seller->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Application Date:</th>
                                <td>{{ $decision->sellApplication->application_date->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Share Quantity:</th>
                                <td>{{ number_format($decision->sellApplication->share_quantity_to_sell) }}</td>
                            </tr>
                            <tr>
                                <th>Current Status:</th>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $decision->sellApplication->status)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <a href="{{ route('sell-applications.show', $decision->sellApplication->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye me-1"></i>View Sell Application
                        </a>
                    </div>
                </div>

                @if($decision->decision_remarks)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Decision Remarks</h6>
                        <div class="border p-3 bg-light rounded">
                            {{ $decision->decision_remarks }}
                        </div>
                    </div>
                </div>
                @endif

                @if($decision->board_members_present)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Board Members Present</h6>
                        <div class="border p-3 bg-light rounded">
                            @foreach($decision->board_members_present as $member)
                                <span class="badge bg-secondary me-2 mb-1">{{ $member }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
