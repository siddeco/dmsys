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
                                        <i class="fas fa-edit me-2"></i>
                                        Edit Spare Part
                                    </h4>
                                    <p class="mb-0 text-white-50">
                                        Update spare part information and stock levels
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('spare_parts.index') }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Inventory
                                    </a>
                                    <span class="badge bg-white text-dark">
                                        ID: {{ $part->id }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('edit spare parts')
                <!-- Part Info Card -->
                <div class="row mb-4 mx-0">
                    <div class="col-12 px-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-4">
                                            <i class="fas fa-cube text-primary fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $part->name }}</h5>
                                            @if($part->part_number)
                                                <p class="text-muted mb-1">Part #: {{ $part->part_number }}</p>
                                            @endif
                                            @if($part->manufacturer)
                                                <p class="text-muted mb-0">Manufacturer: {{ $part->manufacturer }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="h4 mb-0 {{ $part->isLow() ? 'text-warning' : ($part->quantity <= 0 ? 'text-danger' : 'text-success') }}">
                                            {{ $part->quantity }} in stock
                                        </div>
                                        <small class="text-muted">Alert at: {{ $part->alert_threshold }}</small>
                                    </div>
                                </div>
                                @if($part->device)
                                    <div class="mt-3 pt-3 border-top">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-server text-primary me-2"></i>
                                            <div>
                                                <small class="text-muted">Linked Device</small>
                                                <div class="fw-medium">{{ $part->device->name['en'] ?? 'Device' }}</div>
                                                <small class="text-muted">SN: {{ $part->device->serial_number }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="row justify-content-center mx-0">
                    <div class="col-lg-8 col-md-10 px-3">
                        <!-- Main Form Card -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-3 border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-edit text-primary me-2"></i>
                                    Update Information
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <form action="{{ route('spare_parts.update', $part->id) }}" method="POST" id="editPartForm">
                                    @csrf
                                    @method('PUT')

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
                                                   value="{{ old('name', $part->name) }}"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
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
                                                       value="{{ old('part_number', $part->part_number) }}">
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
                                                       value="{{ old('manufacturer', $part->manufacturer) }}">
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
                                                            {{ old('device_id', $part->device_id) == $device->id ? 'selected' : '' }}
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
                                        <div class="mt-3 {{ $part->device ? '' : 'd-none' }}" id="devicePreview">
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
                                                       id="quantityInput"
                                                       class="form-control @error('quantity') is-invalid @enderror" 
                                                       value="{{ old('quantity', $part->quantity) }}" 
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
                                                       id="thresholdInput"
                                                       class="form-control @error('alert_threshold') is-invalid @enderror" 
                                                       value="{{ old('alert_threshold', $part->alert_threshold) }}" 
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
                                                    <small class="text-muted">Current: <span id="currentQuantity">{{ $part->quantity }}</span></small>
                                                    <small class="text-muted">Alert at: <span id="alertThreshold">{{ $part->alert_threshold }}</span></small>
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
                                                      id="descriptionTextarea"
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="4">{{ old('description', trim($part->description)) }}</textarea>
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
                                        <div>
                                            <a href="{{ route('spare_parts.index') }}" class="btn btn-outline-secondary px-4">
                                                <i class="fas fa-times me-1"></i> Cancel
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger px-4 ms-2"
                                                    onclick="confirmReset()">
                                                <i class="fas fa-redo me-1"></i> Reset
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                            <i class="fas fa-save me-1"></i> Update Part
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Stock History & Tips -->
                        <div class="row mt-4">
                            <!-- Tips Card -->
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <h6 class="mb-3">
                                            <i class="fas fa-lightbulb text-warning me-2"></i>
                                            Update Tips
                                        </h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small>Update stock after maintenance or usage</small>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small>Adjust alert thresholds based on usage patterns</small>
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small>Keep descriptions updated for new technicians</small>
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <small>Link parts to devices for maintenance tracking</small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <h6 class="mb-3">
                                            <i class="fas fa-bolt text-primary me-2"></i>
                                            Quick Actions
                                        </h6>
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-success" onclick="incrementStock()">
                                                <i class="fas fa-plus me-1"></i> Add 1 to Stock
                                            </button>
                                            <button type="button" class="btn btn-outline-warning" onclick="decrementStock()">
                                                <i class="fas fa-minus me-1"></i> Use 1 from Stock
                                            </button>
                                            @can('delete spare parts')
                                                <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                                    <i class="fas fa-trash me-1"></i> Delete This Part
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
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
                            <p class="text-muted mb-0">You are not authorized to edit spare parts.</p>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 py-3">
                    <div class="avatar-lg bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4">
                        <i class="fas fa-redo fa-2x text-warning"></i>
                    </div>
                    <h5 class="modal-title mb-2">Reset Changes</h5>
                    <p class="text-muted mb-4">Are you sure you want to reset all changes? This will restore original values.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-warning px-4" id="confirmResetBtn">
                            Reset Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-4 py-3">
                    <div class="avatar-lg bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4">
                        <i class="fas fa-trash fa-2x text-danger"></i>
                    </div>
                    <h5 class="modal-title mb-2">Delete Spare Part</h5>
                    <p class="text-muted mb-4">Are you sure you want to delete "{{ $part->name }}"? This action cannot be undone.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form action="{{ route('spare_parts.delete', $part->id) }}" method="POST" class="d-inline" id="deleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container .card {
            border-radius: 12px;
        }

        .avatar-lg {
            width: 80px;
            height: 80px;
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

        .progress {
            background-color: #dee2e6;
            border-radius: 4px;
        }

        #stockProgress {
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .btn-grid .btn {
            border-radius: 8px;
            padding: 10px;
        }

        @media (max-width: 768px) {
            .dashboard-container .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .row.mt-4 {
                flex-direction: column;
            }

            .row.mt-4 > .col-md-6 {
                margin-bottom: 1rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.justify-content-between > div {
                width: 100%;
            }

            .d-flex.justify-content-between .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Store original form values
            const originalValues = {
                name: document.querySelector('input[name="name"]').value,
                part_number: document.querySelector('input[name="part_number"]').value,
                manufacturer: document.querySelector('input[name="manufacturer"]').value,
                device_id: document.querySelector('select[name="device_id"]').value,
                quantity: document.querySelector('input[name="quantity"]').value,
                alert_threshold: document.querySelector('input[name="alert_threshold"]').value,
                description: document.querySelector('textarea[name="description"]').value
            };

            // Elements
            const quantityInput = document.getElementById('quantityInput');
            const thresholdInput = document.getElementById('thresholdInput');
            const stockProgress = document.getElementById('stockProgress');
            const stockStatusBadge = document.getElementById('stockStatusBadge');
            const currentQuantitySpan = document.getElementById('currentQuantity');
            const alertThresholdSpan = document.getElementById('alertThreshold');
            const deviceSelect = document.getElementById('deviceSelect');
            const devicePreview = document.getElementById('devicePreview');
            const deviceInfo = document.getElementById('deviceInfo');
            const descriptionTextarea = document.getElementById('descriptionTextarea');

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

            // Quick stock actions
            window.incrementStock = function() {
                const current = parseInt(quantityInput.value) || 0;
                quantityInput.value = current + 1;
                quantityInput.dispatchEvent(new Event('input'));
                showToast('Stock increased by 1', 'success');
            };

            window.decrementStock = function() {
                const current = parseInt(quantityInput.value) || 0;
                if (current > 0) {
                    quantityInput.value = current - 1;
                    quantityInput.dispatchEvent(new Event('input'));
                    showToast('Stock decreased by 1', 'info');
                } else {
                    showToast('Stock is already at 0', 'warning');
                }
            };

            // Reset form function
            window.confirmReset = function() {
                const resetModal = new bootstrap.Modal(document.getElementById('resetModal'));
                resetModal.show();

                document.getElementById('confirmResetBtn').onclick = function() {
                    // Reset form values
                    document.querySelector('input[name="name"]').value = originalValues.name;
                    document.querySelector('input[name="part_number"]').value = originalValues.part_number;
                    document.querySelector('input[name="manufacturer"]').value = originalValues.manufacturer;
                    document.querySelector('select[name="device_id"]').value = originalValues.device_id;
                    document.querySelector('input[name="quantity"]').value = originalValues.quantity;
                    document.querySelector('input[name="alert_threshold"]').value = originalValues.alert_threshold;
                    document.querySelector('textarea[name="description"]').value = originalValues.description;

                    // Trigger change events
                    deviceSelect.dispatchEvent(new Event('change'));
                    quantityInput.dispatchEvent(new Event('input'));
                    updateStockPreview();

                    resetModal.hide();
                    showToast('Form reset to original values', 'info');
                };
            };

            // Delete confirmation
            window.confirmDelete = function() {
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();
            };

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

            // Character counter for description
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

            // Form validation
            const form = document.getElementById('editPartForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Check if form has changes
                    const currentName = document.querySelector('input[name="name"]').value;
                    const currentPartNumber = document.querySelector('input[name="part_number"]').value;
                    const currentManufacturer = document.querySelector('input[name="manufacturer"]').value;
                    const currentDeviceId = document.querySelector('select[name="device_id"]').value;
                    const currentQuantity = document.querySelector('input[name="quantity"]').value;
                    const currentThreshold = document.querySelector('input[name="alert_threshold"]').value;
                    const currentDescription = document.querySelector('textarea[name="description"]').value;

                    const hasChanges = currentName !== originalValues.name ||
                                      currentPartNumber !== originalValues.part_number ||
                                      currentManufacturer !== originalValues.manufacturer ||
                                      currentDeviceId !== originalValues.device_id ||
                                      currentQuantity !== originalValues.quantity ||
                                      currentThreshold !== originalValues.alert_threshold ||
                                      currentDescription !== originalValues.description;

                    if (!hasChanges) {
                        e.preventDefault();
                        showToast('No changes were made to update.', 'warning');
                        return false;
                    }

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
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
                    submitBtn.classList.add('disabled');

                    return true;
                });
            }

            // Auto-focus first input if there's an error
            @if($errors->any())
                setTimeout(() => {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }, 500);
            @endif

            // Show toast notification
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-bg-${type} border-0 position-fixed`;
                toast.style.top = '20px';
                toast.style.right = '20px';
                toast.style.zIndex = '1060';
                toast.setAttribute('role', 'alert');
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation' : 'info'}-circle me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;

                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
                bsToast.show();

                toast.addEventListener('hidden.bs.toast', function() {
                    document.body.removeChild(toast);
                });
            }
        });
    </script>
@endsection