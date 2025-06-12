@extends('layouts.app')

@section('title', 'New Buy Application - Promoter Share Management')
@section('page-title', 'New Buy Application')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Buy Application Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('buy-applications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="sell_application_id" class="form-label">Available Sell Application <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('sell_application_id') is-invalid @enderror"
                                id="sell_application_id" name="sell_application_id" required>
                                <option value="">Select Sell Application</option>
                                @foreach ($sellApplications as $sellApp)
                                    <option value="{{ $sellApp->id }}"
                                        {{ old('sell_application_id') == $sellApp->id ? 'selected' : '' }}>
                                        {{ $sellApp->seller->name }} - {{ number_format($sellApp->share_quantity_to_sell) }}
                                        shares
                                        @if ($sellApp->proposed_price_per_share)
                                            (Rs. {{ number_format($sellApp->proposed_price_per_share, 2) }}/share)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('sell_application_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="buyer_category" class="form-label">Buyer Category <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('buyer_category') is-invalid @enderror" id="buyer_category"
                                name="buyer_category" required>
                                <option value="">Select Category</option>
                                <option value="existing_promoter"
                                    {{ old('buyer_category') == 'existing_promoter' ? 'selected' : '' }}>Existing Promoter
                                </option>
                                <option value="public" {{ old('buyer_category') == 'public' ? 'selected' : '' }}>Public
                                </option>
                            </select>
                            @error('buyer_category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buyer Name Field - Changes based on category -->
                        <div class="mb-3">
                            <label for="buyer_name" class="form-label">Buyer Name <span class="text-danger">*</span></label>

                            <!-- Dropdown for existing promoter -->
                            <select class="form-select @error('buyer_name') is-invalid @enderror" id="buyer_name_dropdown"
                                style="display: none;">
                                <option value="">Select Shareholder</option>
                                @foreach ($shareholders as $shareholder)
                                    <option value="{{ $shareholder->id }}" data-name="{{ $shareholder->name }}"
                                        data-type="{{ $shareholder->type }}"
                                        {{ old('buyer_name_dropdown') == $shareholder->id ? 'selected' : '' }}>
                                        {{ $shareholder->name }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Text input for public -->
                            <input type="text" class="form-control @error('buyer_name') is-invalid @enderror"
                                id="buyer_name_input" value="{{ old('buyer_name') }}" style="display: none;">

                            <!-- This is the actual field that gets submitted -->
                            <input type="hidden" id="buyer_name_hidden" name="buyer_name" value="{{ old('buyer_name') }}">

                            @error('buyer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="buyer_type" class="form-label">Buyer Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('buyer_type') is-invalid @enderror" id="buyer_type"
                                name="buyer_type" required>
                                <option value="">Select buyer name first</option>
                                <option value="individual" {{ old('buyer_type') == 'individual' ? 'selected' : '' }}>
                                    Individual</option>
                                <option value="institutional" {{ old('buyer_type') == 'institutional' ? 'selected' : '' }}>
                                    Institutional</option>
                            </select>
                            @error('buyer_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="share_quantity_to_buy" class="form-label">Share Quantity to Buy <span
                                        class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('share_quantity_to_buy') is-invalid @enderror"
                                    id="share_quantity_to_buy" name="share_quantity_to_buy"
                                    value="{{ old('share_quantity_to_buy') }}" min="1" required>
                                @error('share_quantity_to_buy')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="offered_price_per_share" class="form-label">Offered Price per Share <span
                                        class="text-danger">*</span></label>
                                <input type="number"
                                    class="form-control @error('offered_price_per_share') is-invalid @enderror"
                                    id="offered_price_per_share" name="offered_price_per_share"
                                    value="{{ old('offered_price_per_share') }}" step="0.01" min="0" required>
                                @error('offered_price_per_share')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="application_date" class="form-label">Application Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('application_date') is-invalid @enderror"
                                    id="application_date" name="application_date"
                                    value="{{ old('application_date', date('Y-m-d')) }}" required>
                                @error('application_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="citizenship_number" class="form-label">Citizenship Number <span
                                        class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control @error('citizenship_number') is-invalid @enderror"
                                    id="citizenship_number" name="citizenship_number"
                                    value="{{ old('citizenship_number') }}" required>
                                @error('citizenship_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pan_number" class="form-label">PAN Number</label>
                                <input type="text" class="form-control @error('pan_number') is-invalid @enderror"
                                    id="pan_number" name="pan_number" value="{{ old('pan_number') }}">
                                @error('pan_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="demat_account" class="form-label">Demat Account <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('demat_account') is-invalid @enderror"
                                    id="demat_account" name="demat_account" value="{{ old('demat_account') }}" required>
                                @error('demat_account')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">Required Documents</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="buy_application_doc" class="form-label">Buy Application Document <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('buy_application_doc') is-invalid @enderror"
                                            id="buy_application_doc" name="buy_application_doc"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        @error('buy_application_doc')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="buyer_citizenship" class="form-label">Buyer Citizenship <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('buyer_citizenship') is-invalid @enderror"
                                            id="buyer_citizenship" name="buyer_citizenship" accept=".pdf,.jpg,.jpeg,.png"
                                            required>
                                        @error('buyer_citizenship')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="buyer_cia_report" class="form-label">CIA Report <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('buyer_cia_report') is-invalid @enderror"
                                            id="buyer_cia_report" name="buyer_cia_report" accept=".pdf,.jpg,.jpeg,.png"
                                            required>
                                        @error('buyer_cia_report')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="buyer_tax_clearance" class="form-label">Tax Clearance <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('buyer_tax_clearance') is-invalid @enderror"
                                            id="buyer_tax_clearance" name="buyer_tax_clearance"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        @error('buyer_tax_clearance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="buyer_income_source" class="form-label">Income Source Document <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('buyer_income_source') is-invalid @enderror"
                                            id="buyer_income_source" name="buyer_income_source"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        @error('buyer_income_source')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="combine_application" class="form-label">Combined Application <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('combine_application') is-invalid @enderror"
                                            id="combine_application" name="combine_application"
                                            accept=".pdf,.jpg,.jpeg,.png" required>
                                        @error('combine_application')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="police_report" class="form-label">Police Report</label>
                                        <input type="file"
                                            class="form-control @error('police_report') is-invalid @enderror"
                                            id="police_report" name="police_report" accept=".pdf,.jpg,.jpeg,.png"
                                            required>
                                        @error('police_report')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="self_declaration" class="form-label">Self Declaration <span
                                                class="text-danger">*</span></label>
                                        <input type="file"
                                            class="form-control @error('self_declaration') is-invalid @enderror"
                                            id="self_declaration" name="self_declaration" accept=".pdf,.jpg,.jpeg,.png"
                                            required>
                                        @error('self_declaration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Institutional Documents (shown conditionally) -->
                                <div id="institutional-docs" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_moa_aoa" class="form-label">MOA & AOA <span
                                                    class="text-danger">*</span></label>
                                            <input type="file"
                                                class="form-control @error('buyer_moa_aoa') is-invalid @enderror"
                                                id="buyer_moa_aoa" name="buyer_moa_aoa" accept=".pdf,.jpg,.jpeg,.png">
                                            @error('buyer_moa_aoa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="buyer_decision_minute" class="form-label">Decision Minute <span
                                                    class="text-danger">*</span></label>
                                            <input type="file"
                                                class="form-control @error('buyer_decision_minute') is-invalid @enderror"
                                                id="buyer_decision_minute" name="buyer_decision_minute"
                                                accept=".pdf,.jpg,.jpeg,.png">
                                            @error('buyer_decision_minute')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="buyer_others" class="form-label">Other Documents</label>
                                    <input type="file"
                                        class="form-control @error('buyer_others') is-invalid @enderror"
                                        id="buyer_others" name="buyer_others" accept=".pdf,.jpg,.jpeg,.png">
                                    @error('buyer_others')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('buy-applications.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Submit Application
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
        document.addEventListener('DOMContentLoaded', function() {
            const buyerCategorySelect = document.getElementById('buyer_category');
            const buyerNameDropdown = document.getElementById('buyer_name_dropdown');
            const buyerNameInput = document.getElementById('buyer_name_input');
            const buyerNameHidden = document.getElementById('buyer_name_hidden');
            const buyerTypeSelect = document.getElementById('buyer_type');
            const institutionalDocs = document.getElementById('institutional-docs');

            // Handle buyer category change
            buyerCategorySelect.addEventListener('change', function() {
                const category = this.value;

                // Reset buyer type
                buyerTypeSelect.innerHTML = '<option value="">Select buyer name first</option>';
                buyerTypeSelect.value = '';

                if (category === 'existing_promoter') {
                    // Show dropdown for existing promoter
                    buyerNameDropdown.style.display = 'block';
                    buyerNameInput.style.display = 'none';
                    buyerNameDropdown.required = true;
                    buyerNameInput.required = false;

                    // Clear the text input name attribute when not needed
                    buyerNameInput.removeAttribute('name');
                    buyerNameDropdown.setAttribute('name', 'buyer_name_dropdown');
                } else if (category === 'public') {
                    // Show text input for public
                    buyerNameDropdown.style.display = 'none';
                    buyerNameInput.style.display = 'block';
                    buyerNameDropdown.required = false;
                    buyerNameInput.required = true;

                    // Set proper name attributes
                    buyerNameDropdown.removeAttribute('name');
                    buyerNameInput.setAttribute('name', 'buyer_name');
                } else {
                    // Hide both
                    buyerNameDropdown.style.display = 'none';
                    buyerNameInput.style.display = 'none';
                    buyerNameDropdown.required = false;
                    buyerNameInput.required = false;
                }

                // Reset values
                buyerNameDropdown.value = '';
                buyerNameInput.value = '';
                buyerNameHidden.value = '';

                // Reset form fields that get auto-filled
                resetAutoFilledFields();

                toggleInstitutionalDocs();
            });

            // Handle shareholder selection for existing promoter
            buyerNameDropdown.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const shareholderName = selectedOption.getAttribute('data-name');
                const shareholderType = selectedOption.getAttribute('data-type');

                if (shareholderName && this.value) {
                    buyerNameHidden.value = shareholderName;

                    // Clear and rebuild buyer type options
                    buyerTypeSelect.innerHTML = '';

                    // Add default option
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Select Type';
                    buyerTypeSelect.appendChild(defaultOption);

                    // Add individual option
                    const individualOption = document.createElement('option');
                    individualOption.value = 'individual';
                    individualOption.textContent = 'Individual';
                    buyerTypeSelect.appendChild(individualOption);

                    // Add institutional option
                    const institutionalOption = document.createElement('option');
                    institutionalOption.value = 'institutional';
                    institutionalOption.textContent = 'Institutional';
                    buyerTypeSelect.appendChild(institutionalOption);

                    // Auto-select the buyer type based on shareholder type
                    if (shareholderType === 'individual' || shareholderType === 'institutional') {
                        buyerTypeSelect.value = shareholderType;
                    }

                    // Trigger change event for buyer type to show/hide institutional docs
                    buyerTypeSelect.dispatchEvent(new Event('change'));

                    // Fetch additional shareholder data
                    fetchShareholderData(this.value);
                } else {
                    buyerNameHidden.value = '';
                    buyerTypeSelect.innerHTML = '<option value="">Select buyer name first</option>';
                    buyerTypeSelect.value = '';
                    resetAutoFilledFields();
                }
            });

            // Handle buyer name input for public category
            buyerNameInput.addEventListener('input', function() {
                const name = this.value.trim();
                buyerNameHidden.value = name;

                if (name) {
                    // Enable buyer type selection for public
                    buyerTypeSelect.innerHTML = `
                <option value="">Select Type</option>
                <option value="individual">Individual</option>
                <option value="institutional">Institutional</option>
            `;
                } else {
                    buyerTypeSelect.innerHTML = '<option value="">Select buyer name first</option>';
                    buyerTypeSelect.value = '';
                }

                toggleInstitutionalDocs();
            });

            // Handle buyer type change
            buyerTypeSelect.addEventListener('change', function() {
                toggleInstitutionalDocs();
            });

            function toggleInstitutionalDocs() {
                const buyerType = buyerTypeSelect.value;
                const moaAoaField = document.getElementById('buyer_moa_aoa');
                const decisionMinuteField = document.getElementById('buyer_decision_minute');

                if (buyerType === 'institutional') {
                    institutionalDocs.style.display = 'block';
                    if (moaAoaField) moaAoaField.required = true;
                    if (decisionMinuteField) decisionMinuteField.required = true;
                } else {
                    institutionalDocs.style.display = 'none';
                    if (moaAoaField) moaAoaField.required = false;
                    if (decisionMinuteField) decisionMinuteField.required = false;
                }
            }

            function fetchShareholderData(shareholderId) {
                if (!shareholderId) return;

                // Fixed the fetch URL syntax
                fetch(`/shareholders/${shareholderId}/data`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Auto-fill form fields with shareholder data
                        const citizenshipField = document.getElementById('citizenship_number');
                        const phoneField = document.getElementById('phone');
                        const emailField = document.getElementById('email');
                        const dematField = document.getElementById('demat_account');
                        const panField = document.getElementById('pan_number');

                        if (citizenshipField) citizenshipField.value = data.citizenship_number || '';
                        if (phoneField) phoneField.value = data.contact_number || '';
                        if (emailField) emailField.value = data.email || '';
                        if (dematField) dematField.value = data.demat_account || '';
                        if (panField) panField.value = data.pan_number || '';
                    })
                    .catch(error => {
                        console.error('Error fetching shareholder data:', error);
                        // Optionally show user-friendly error message
                        // alert('Could not load shareholder data. Please fill the form manually.');
                    });
            }

            function resetAutoFilledFields() {
                const fields = ['citizenship_number', 'phone', 'email', 'demat_account', 'pan_number'];
                fields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) field.value = '';
                });
            }

            // Handle form submission to ensure proper data is sent
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const category = buyerCategorySelect.value;

                    if (category === 'existing_promoter') {
                        // For existing promoter, use the hidden field value
                        buyerNameInput.value = buyerNameHidden.value;
                    } else if (category === 'public') {
                        // For public, ensure hidden field has the input value
                        buyerNameHidden.value = buyerNameInput.value;
                    }
                });
            }

            // Initialize form state on page load
            if (buyerCategorySelect.value) {
                buyerCategorySelect.dispatchEvent(new Event('change'));
            }

            // If there's an old value for buyer_name_dropdown, trigger its change event
            if (buyerNameDropdown.value) {
                buyerNameDropdown.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
