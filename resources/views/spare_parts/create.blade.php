@extends('layouts.app')

@section('content')
    <div class="dashboard-container">
        <div class="container-fluid px-0">
            <!-- Header Section -->
            <div class="row mb-4 mx-0">
                <div class="col-12 px-0">
                    <div class="card border-0 shadow-sm bg-gradient-primary">
                        <div class="card-body py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 text-white">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Add New Spare Part
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Add a new spare part to the inventory
                                    </p>
                                </div>
                                <a href="{{ route('spare_parts.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Inventory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('manage spare parts')
                <!-- Add Form -->
                <div class="row justify-content-center mx-0">
                    <div class="col-lg-8 col-md-10 px-3">
                        <!-- Main Form Card -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-cog text-primary me-2"></i>
                                    Part Information
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('spare_parts.store') }}" method="POST" id="addPartForm">
                                    @csrf

                                    <!-- Part Name -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium mb-2">
                                            <i class="fas fa-tag me-1 text-primary"></i>
                                            Part Name *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-cube text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                   name="name" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   placeholder="Enter part name"
                                                   value="{{ old('name') }}"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Enter a descriptive name for the spare part</div>
                                    </div>

                                    <!-- Part Details Row -->
                                    <div class="row g-3 mb-4">
                                        <!-- Part Number -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium mb-2">
                                                <i class="fas fa-barcode me-1 text-primary"></i>
                                                Part Number
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-hashtag text-muted"></i>
                                                </span>
                                                <input type="text" 
                                                       name="part_number" 
                                                       class="form-control @error('part_number') is-invalid @enderror" 
                                                       placeholder="e.g., ABC-12345"
                                                       value="{{ old('part_number') }}">
                                                @error('part_number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Manufacturer part number</div>
                                        </div>

                                        <!-- Manufacturer -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium mb-2">
                                                <i class="fas fa-industry me-1 text-primary"></i>
                                                Manufacturer
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-building text-muted"></i>
                                                </span>
                                                <input type="text" 
                                                       name="manufacturer" 
                                                       class="form-control @error('manufacturer') is-invalid @enderror" 
                                                       placeholder="Manufacturer name"
                                                       value="{{ old('manufacturer') }}">
                                                @error('manufacturer')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Part manufacturer or brand</div>
                                        </div>
                                    </div>

                                    <!-- Device Selection -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium mb-2">
                                            <i class="fas fa-server me-1 text-primary"></i>
                                            Linked Device
                                            <span class="badge bg-info bg-opacity-10 text-info ms-2">
                                                Optional
                                            </span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-link text-muted"></i>
                                            </span>
                                            <select name="device_id" 
                                                    class="form-select @error('device_id') is-invalid @enderror"
                                                    id="deviceSelect">
                                                <option value="">Not linked to a specific device</option>
                                                @foreach ($devices as $device)
                                                    <option value="{{ $device->id }}" 
                                                            {{ old('device_id') == $device->id ? 'selected' : '' }}
                                                            data-serial="{{ $device->serial_number }}"
                                                            data-model="{{ $device->name['en'] ?? 'Device' }}">
                                                        {{ $device->name['en'] ?? $device->serial_number }}
                                                        (SN: {{ $device->serial_number }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('device_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Link this part to a specific device for easier tracking</div>

                                        <!-- Device Preview -->
                                        <div class="mt-3 d-none" id="devicePreview">
                                            <div class="alert alert-info border-0 py-2 px-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <div>
                                                        <small class="fw-medium">Selected Device:</small>
                                                        <div class="small" id="deviceInfo"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stock Information Row -->
                                    <div class="row g-3 mb-4">
                                        <!-- Quantity -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium mb-2">
                                                <i class="fas fa-boxes me-1 text-primary"></i>
                                                Quantity in Stock *
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-layer-group text-muted"></i>
                                                </span>
                                                <input type="number" 
                                                       name="quantity" 
                                                       class="form-control @error('quantity') is-invalid @enderror" 
                                                       value="{{ old('quantity', 0) }}" 
                                                       min="0"
                                                       step="1"
                                                       required>
                                                @error('quantity')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Current stock quantity</div>
                                        </div>

                                        <!-- Alert Threshold -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium mb-2">
                                                <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                                Low Stock Alert *
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light">
                                                    <i class="fas fa-bell text-muted"></i>
                                                </span>
                                                <input type="number" 
                                                       name="alert_threshold" 
                                                       class="form-control @error('alert_threshold') is-invalid @enderror" 
                                                       value="{{ old('alert_threshold', 5) }}" 
                                                       min="0"
                                                       step="1"
                                                       required>
                                                @error('alert_threshold')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Alert when stock falls below this level</div>
                                        </div>
                                    </div>

                                    <!-- Stock Status Preview -->
                                    <div class="mb-4">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body py-3">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="fw-medium">Stock Status Preview</small>
                                                    <span class="badge" id="stockStatusBadge">N/A</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar" id="stockProgress" style="width: 0%;"></div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2">
                                                    <small class="text-muted">Current: <span id="currentQuantity">0</span></small>
                                                    <small class="text-muted">Alert at: <span id="alertThreshold">5</span></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label class="form-label fw-medium mb-2">
                                            <i class="fas fa-align-left me-1 text-primary"></i>
                                            Description
                                            <span class="badge bg-info bg-opacity-10 text-info ms-2">
                                                Optional
                                            </span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light align-items-start pt-3">
                                                <i class="fas fa-file-alt text-muted"></i>
                                            </span>
                                            <textarea name="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="4"
                                                      placeholder="Add additional details about this part...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-text">Add notes, specifications, or usage instructions</div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <a href="{{ route('spare_parts.index') }}" class="btn btn-outline-secondary px-4">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                            <i class="fas fa-save me-1"></i> Add Spare Part
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tips Card -->
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-body p-4">
                                <h6 class="mb-3">
                                    <i class="fas fa-lightbulb text-warning me-2"></i>
                                    Best Practices
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <small>Use descriptive names for easy identification</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <small>Include part number for accurate reordering</small>
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <small>Set realistic alert thresholds based on usage</small>
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <small>Link parts to devices for maintenance tracking</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- No Permission -->
                <div class="card border-0 shadow-sm mx-3">
                    <div class="card-body text-center py-5">
                        <div class="py-4">
                            <i class="fas fa-ban fa-3x text-danger mb-3"></i>
                            <h5 class="text-danger">Access Denied</h5>
                            <p class="text-muted mb-0">You are not authorized to add spare parts.</p>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <style>
        .dashboard-container .card {
            border-radius: 12px;
        }

        .input-group-text {
            border-right: none;
            min-width: 45px;
            justify-content: center;
        }

        .form-control, .form-select, textarea.form-control {
            border-left: none;
            padding-left: 0;
        }

        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .progress {
            background-color: #dee2e6;
            border-radius: 4px;
        }

        #stockProgress {
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        @media (max-width: 768px) {
            .dashboard-container .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .row.g-3 > [class*="col-"] {
                margin-bottom: 1rem;
            }

            .row.g-3 > [class*="col-"]:last-child {
                margin-bottom: 0;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const quantityInput = document.querySelector('input[name="quantity"]');
            const thresholdInput = document.querySelector('input[name="alert_threshold"]');
            const stockProgress = document.getElementById('stockProgress');
            const stockStatusBadge = document.getElementById('stockStatusBadge');
            const currentQuantitySpan = document.getElementById('currentQuantity');
            const alertThresholdSpan = document.getElementById('alertThreshold');
            const deviceSelect = document.getElementById('deviceSelect');
            const devicePreview = document.getElementById('devicePreview');
            const deviceInfo = document.getElementById('deviceInfo');

            // Update stock preview
            function updateStockPreview() {
                const quantity = parseInt(quantityInput.value) || 0;
                const threshold = parseInt(thresholdInput.value) || 5;

                // Update display values
                currentQuantitySpan.textContent = quantity;
                alertThresholdSpan.textContent = threshold;

                // Calculate percentage (cap at threshold * 2 for visual)
                const maxVisual = Math.max(threshold * 2, quantity);
                const percentage = Math.min(100, (quantity / maxVisual) * 100);

                // Update progress bar
                stockProgress.style.width = percentage + '%';

                // Update colors and status
                if (quantity === 0) {
                    stockProgress.className = 'progress-bar bg-danger';
                    stockStatusBadge.className = 'badge bg-danger';
                    stockStatusBadge.textContent = 'Out of Stock';
                } else if (quantity <= threshold) {
                    stockProgress.className = 'progress-bar bg-warning';
                    stockStatusBadge.className = 'badge bg-warning';
                    stockStatusBadge.textContent = 'Low Stock';
                } else {
                    stockProgress.className = 'progress-bar bg-success';
                    stockStatusBadge.className = 'badge bg-success';
                    stockStatusBadge.textContent = 'In Stock';
                }
            }

            // Update device preview
            function updateDevicePreview() {
                const selectedOption = deviceSelect.options[deviceSelect.selectedIndex];

                if (selectedOption.value) {
                    const model = selectedOption.getAttribute('data-model');
                    const serial = selectedOption.getAttribute('data-serial');

                    deviceInfo.innerHTML = `
                        <div>${model}</div>
                        <small class="text-muted">Serial: ${serial}</small>
                    `;
                    devicePreview.classList.remove('d-none');
                } else {
                    devicePreview.classList.add('d-none');
                }
            }

            // Event listeners
            if (quantityInput && thresholdInput) {
                quantityInput.addEventListener('input', updateStockPreview);
                thresholdInput.addEventListener('input', updateStockPreview);

                // Initial update
                updateStockPreview();
            }

            if (deviceSelect) {
                deviceSelect.addEventListener('change', updateDevicePreview);

                // Initial update
                updateDevicePreview();
            }

            // Form validation
            const form = document.getElementById('addPartForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Validate quantity
                    const quantity = parseInt(quantityInput.value);
                    if (isNaN(quantity) || quantity < 0) {
                        e.preventDefault();
                        alert('Please enter a valid quantity (0 or higher).');
                        quantityInput.focus();
                        return false;
                    }

                    // Validate threshold
                    const threshold = parseInt(thresholdInput.value);
                    if (isNaN(threshold) || threshold < 0) {
                        e.preventDefault();
                        alert('Please enter a valid alert threshold (0 or higher).');
                        thresholdInput.focus();
                        return false;
                    }

                    // Disable submit button to prevent double submission
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                    submitBtn.classList.add('disabled');

                    return true;
                });
            }

            // Auto-focus first input
            const firstInput = document.querySelector('input[name="name"]');
            if (firstInput) {
                setTimeout(() => {
                    firstInput.focus();
                }, 100);
            }

            // Show error messages if any
            @if($errors->any())
                setTimeout(() => {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }, 500);
            @endif

            // Character counter for description
            const descriptionTextarea = document.querySelector('textarea[name="description"]');
            if (descriptionTextarea) {
                const charCounter = document.createElement('div');
                charCounter.className = 'form-text text-end mt-1';
                charCounter.innerHTML = '<span id="charCount">0</span>/500 characters';
                descriptionTextarea.parentNode.appendChild(charCounter);

                descriptionTextarea.addEventListener('input', function() {
                    const charCount = this.value.length;
                    document.getElementById('charCount').textContent = charCount;

                    if (charCount > 500) {
                        charCounter.classList.add('text-danger');
                    } else {
                        charCounter.classList.remove('text-danger');
                    }
                });

                // Initial count
                descriptionTextarea.dispatchEvent(new Event('input'));
            }
        });
    </script>
@endsection