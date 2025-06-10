@extends('layouts.app')

@section('title', 'Edit Board Decision - Promoter Share Management')
@section('page-title', 'Edit Board Decision')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Edit Board Decision</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('board-decisions.update', $decision->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Sell Application</label>
                        <div class="border p-3 bg-light rounded">
                            <strong>{{ $decision->sellApplication->seller->name }}</strong> - 
                            {{ number_format($decision->sellApplication->share_quantity_to_sell) }} shares
                            <br><small class="text-muted">Applied: {{ $decision->sellApplication->application_date->format('M d, Y') }}</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="decision_date" class="form-label">Decision Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('decision_date') is-invalid @enderror" 
                                   id="decision_date" name="decision_date" value="{{ old('decision_date', $decision->decision_date->format('Y-m-d')) }}" required>
                            @error('decision_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="decision" class="form-label">Decision <span class="text-danger">*</span></label>
                            <select class="form-select @error('decision') is-invalid @enderror" id="decision" name="decision" required>
                                <option value="">Select Decision</option>
                                <option value="approved" {{ old('decision', $decision->decision) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('decision', $decision->decision) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            @error('decision')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="decision_remarks" class="form-label">Decision Remarks</label>
                        <textarea class="form-control @error('decision_remarks') is-invalid @enderror" 
                                  id="decision_remarks" name="decision_remarks" rows="3">{{ old('decision_remarks', $decision->decision_remarks) }}</textarea>
                        @error('decision_remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="board_members_present" class="form-label">Board Members Present</label>
                        <input type="text" class="form-control @error('board_members_present') is-invalid @enderror" 
                               id="board_members_present" name="board_members_present" 
                               value="{{ old('board_members_present', is_array($decision->board_members_present) ? implode(', ', $decision->board_members_present) : '') }}" 
                               placeholder="Enter names separated by commas">
                        <small class="form-text text-muted">Enter board member names separated by commas</small>
                        @error('board_members_present')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meeting_minute_reference" class="form-label">Meeting Minute Reference</label>
                        <input type="text" class="form-control @error('meeting_minute_reference') is-invalid @enderror" 
                               id="meeting_minute_reference" name="meeting_minute_reference" 
                               value="{{ old('meeting_minute_reference', $decision->meeting_minute_reference) }}" 
                               placeholder="e.g., BD-2024-001">
                        @error('meeting_minute_reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('board-decisions.show', $decision->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Decision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
