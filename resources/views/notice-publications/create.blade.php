@extends('layouts.app')

@section('title', 'New Notice Publication - Promoter Share Management')
@section('page-title', 'New Notice Publication')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Notice Publication Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('notice-publications.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="sell_application_id" class="form-label">Approved Sell Application <span class="text-danger">*</span></label>
                        <select class="form-select @error('sell_application_id') is-invalid @enderror" id="sell_application_id" name="sell_application_id" required>
                            <option value="">Select Sell Application</option>
                            @foreach($sellApplications as $sellApp)
                                <option value="{{ $sellApp->id }}" {{ old('sell_application_id', request('sell_application_id')) == $sellApp->id ? 'selected' : '' }}>
                                    {{ $sellApp->seller->name }} - {{ number_format($sellApp->share_quantity_to_sell) }} shares
                                    (Approved: {{ $sellApp->boardDecision->decision_date->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('sell_application_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publication_date" class="form-label">Publication Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('publication_date') is-invalid @enderror"
                                   id="publication_date" name="publication_date" value="{{ old('publication_date', date('Y-m-d')) }}" required>
                            @error('publication_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="newspaper_name" class="form-label">Newspaper Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('newspaper_name') is-invalid @enderror"
                                   id="newspaper_name" name="newspaper_name" value="{{ old('newspaper_name') }}"
                                   placeholder="e.g., The Himalayan Times" required>
                            @error('newspaper_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notice_attachment" class="form-label">Notice Publication Document <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('notice_attachment') is-invalid @enderror"
                               id="notice_attachment" name="notice_attachment" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="form-text text-muted">Upload the published notice document (PDF, JPG, PNG - Max: 5MB)</small>
                        @error('notice_attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('notice-publications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Publish Notice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('sell_application_id').addEventListener('change', function() {
    const sellAppId = this.value;
    if (sellAppId) {
        // You can add AJAX call here to get sell application details and auto-populate notice content
        generateNoticeContent();
    }
});

function generateNoticeContent() {
    const sellAppSelect = document.getElementById('sell_application_id');
    const selectedOption = sellAppSelect.options[sellAppSelect.selectedIndex];

    if (selectedOption.value) {
        const text = selectedOption.text;
        const sellerName = text.split(' - ')[0];
        const shareQuantity = text.match(/(\d+(?:,\d+)*) shares/)[1];

        const noticeContent = `NOTICE OF SHARE SALE

Notice is hereby given that ${sellerName} intends to sell ${shareQuantity} shares of the company.

Interested buyers may submit their applications within 15 days from the date of this publication.

For more information, please contact the company office.

Date: ${new Date().toLocaleDateString()}`;

        document.getElementById('notice_content').value = noticeContent;
    }
}
</script>
@endsection
