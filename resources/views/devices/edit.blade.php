@extends('layouts.app')

@section('content')

    @can('manage devices')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">
                        <i class="fas fa-edit me-2"></i>
                        {{ __('تعديل جهاز: ') }} <span class="text-dark">{{ $device->display_name }}</span>
                    </h4>
                    <a href="{{ route('devices.show', $device->id) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        {{ __('رجوع إلى التفاصيل') }}
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('devices.update', $device->id) }}" method="POST" id="editDeviceForm">
                    @csrf
                    @method('PUT')

                    <!-- توجيه سريع -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info border-0 bg-light-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-3 fs-4 text-info"></i>
                                    <div>
                                        <h6 class="mb-1">تعديل بيانات الجهاز</h6>
                                        <p class="mb-0">قم بتحديث بيانات الجهاز، الحقول المطلوبة <span class="text-danger">*</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ١: المعلومات الأساسية -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-info-circle me-2"></i>
                                المعلومات الأساسية
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- اسم الجهاز -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">
                                            {{ __('اسم الجهاز') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $device->english_name) }}" 
                                               placeholder="Device Name in English" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الرقم التسلسلي -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">
                                            {{ __('الرقم التسلسلي') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="serial_number" 
                                               class="form-control @error('serial_number') is-invalid @enderror" 
                                               value="{{ old('serial_number', $device->serial_number) }}" 
                                               placeholder="أدخل الرقم التسلسلي الفريد" 
                                               required>
                                        <small class="text-muted">يجب أن يكون الرقم التسلسلي فريداً لكل جهاز</small>
                                        @error('serial_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الموديل -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الموديل') }}</label>
                                        <input type="text" name="model" 
                                               class="form-control @error('model') is-invalid @enderror" 
                                               value="{{ old('model', $device->model) }}" 
                                               placeholder="أدخل موديل الجهاز">
                                        @error('model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الشركة المصنعة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الشركة المصنعة') }}</label>
                                        <input type="text" name="manufacturer" 
                                               class="form-control @error('manufacturer') is-invalid @enderror" 
                                               value="{{ old('manufacturer', $device->manufacturer) }}" 
                                               placeholder="أدخل اسم الشركة المصنعة">
                                        @error('manufacturer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ٢: التصنيف -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-tags me-2"></i>
                                التصنيف
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- نوع الجهاز -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('نوع الجهاز') }}</label>
                                        <select name="device_type" class="form-select @error('device_type') is-invalid @enderror">
                                            <option value="">-- اختر نوع الجهاز --</option>
                                            @foreach(['xray', 'ultrasound', 'mri', 'ct_scanner', 'ventilator', 'monitor', 'defibrillator', 'analyzer', 'centrifuge', 'microscope', 'autoclave', 'incubator', 'other'] as $type)
                                                <option value="{{ $type }}" 
                                                    {{ old('device_type', $device->device_type) == $type ? 'selected' : '' }}>
                                                    @php
                                                        $typeNames = [
                                                            'xray' => 'X-Ray',
                                                            'ultrasound' => 'Ultrasound',
                                                            'mri' => 'MRI',
                                                            'ct_scanner' => 'CT Scanner',
                                                            'ventilator' => 'Ventilator',
                                                            'monitor' => 'Monitor',
                                                            'defibrillator' => 'Defibrillator',
                                                            'analyzer' => 'Analyzer',
                                                            'centrifuge' => 'Centrifuge',
                                                            'microscope' => 'Microscope',
                                                            'autoclave' => 'Autoclave',
                                                            'incubator' => 'Incubator',
                                                            'other' => 'Other'
                                                        ];
                                                    @endphp
                                                    {{ $typeNames[$type] ?? $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('device_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- التصنيف -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('التصنيف') }}</label>
                                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                                            <option value="">-- اختر التصنيف --</option>
                                            @foreach(['imaging', 'monitoring', 'laboratory', 'therapeutic', 'surgical', 'diagnostic', 'dental', 'ophthalmic', 'other'] as $cat)
                                                <option value="{{ $cat }}" 
                                                    {{ old('category', $device->category) == $cat ? 'selected' : '' }}>
                                                    @php
                                                        $categoryNames = [
                                                            'imaging' => 'Imaging',
                                                            'monitoring' => 'Monitoring',
                                                            'laboratory' => 'Laboratory',
                                                            'therapeutic' => 'Therapeutic',
                                                            'surgical' => 'Surgical',
                                                            'diagnostic' => 'Diagnostic',
                                                            'dental' => 'Dental',
                                                            'ophthalmic' => 'Ophthalmic',
                                                            'other' => 'Other'
                                                        ];
                                                    @endphp
                                                    {{ $categoryNames[$cat] ?? $cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ٣: الموقع -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                الموقع
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- المشروع -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('المشروع') }}</label>
                                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror">
                                            <option value="">-- اختر المشروع --</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}" 
                                                    {{ old('project_id', $device->project_id) == $project->id ? 'selected' : '' }}>
                                                    {{ $project->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('project_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الموقع -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الموقع') }}</label>
                                        <input type="text" name="location" 
                                               class="form-control @error('location') is-invalid @enderror" 
                                               value="{{ old('location', $device->location) }}" 
                                               placeholder="أدخل الموقع العام">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- رقم الغرفة -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('رقم الغرفة') }}</label>
                                        <input type="text" name="room_number" 
                                               class="form-control @error('room_number') is-invalid @enderror" 
                                               value="{{ old('room_number', $device->room_number) }}" 
                                               placeholder="رقم الغرفة">
                                        @error('room_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الطابق -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الطابق') }}</label>
                                        <input type="text" name="floor" 
                                               class="form-control @error('floor') is-invalid @enderror" 
                                               value="{{ old('floor', $device->floor) }}" 
                                               placeholder="الطابق">
                                        @error('floor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- المبنى -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('المبنى') }}</label>
                                        <input type="text" name="building" 
                                               class="form-control @error('building') is-invalid @enderror" 
                                               value="{{ old('building', $device->building) }}" 
                                               placeholder="المبنى">
                                        @error('building')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- المدينة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('المدينة') }}</label>
                                        <input type="text" name="city" 
                                               class="form-control @error('city') is-invalid @enderror" 
                                               value="{{ old('city', $device->city) }}" 
                                               placeholder="المدينة">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- المنطقة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('المنطقة') }}</label>
                                        <select name="region" class="form-select @error('region') is-invalid @enderror">
                                            <option value="">-- اختر المنطقة --</option>
                                            @foreach (['الرياض', 'مكة المكرمة', 'المدينة المنورة', 'القصيم', 'الشرقية', 'عسير', 'تبوك', 'حائل', 'الحدود الشمالية', 'جازان', 'نجران', 'الباحة', 'الجوف'] as $region)
                                                <option value="{{ $region }}" 
                                                    {{ old('region', $device->region) == $region ? 'selected' : '' }}>
                                                    {{ $region }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ٤: التواريخ -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="far fa-calendar-alt me-2"></i>
                                التواريخ
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- تاريخ الشراء -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('تاريخ الشراء') }}</label>
                                        <input type="date" name="purchase_date" 
                                               class="form-control @error('purchase_date') is-invalid @enderror" 
                                               value="{{ old('purchase_date', $device->purchase_date ? $device->purchase_date->format('Y-m-d') : '') }}">
                                        @error('purchase_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تاريخ التثبيت -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('تاريخ التثبيت') }}</label>
                                        <input type="date" name="installation_date" 
                                               class="form-control @error('installation_date') is-invalid @enderror" 
                                               value="{{ old('installation_date', $device->installation_date ? $device->installation_date->format('Y-m-d') : '') }}">
                                        @error('installation_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تاريخ انتهاء الضمان -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('تاريخ انتهاء الضمان') }}</label>
                                        <input type="date" name="warranty_expiry" 
                                               class="form-control @error('warranty_expiry') is-invalid @enderror" 
                                               value="{{ old('warranty_expiry', $device->warranty_expiry ? $device->warranty_expiry->format('Y-m-d') : '') }}">
                                        @error('warranty_expiry')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تاريخ المعايرة الأخير -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('تاريخ المعايرة الأخير') }}</label>
                                        <input type="date" name="last_calibration_date" 
                                               class="form-control @error('last_calibration_date') is-invalid @enderror" 
                                               value="{{ old('last_calibration_date', $device->last_calibration_date ? $device->last_calibration_date->format('Y-m-d') : '') }}">
                                        @error('last_calibration_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تاريخ المعايرة القادم -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('تاريخ المعايرة القادم') }}</label>
                                        <input type="date" name="next_calibration_date" 
                                               class="form-control @error('next_calibration_date') is-invalid @enderror" 
                                               value="{{ old('next_calibration_date', $device->next_calibration_date ? $device->next_calibration_date->format('Y-m-d') : '') }}">
                                        @error('next_calibration_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ٥: الحالة والإدارة -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-clipboard-check me-2"></i>
                                الحالة والإدارة
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- الحالة -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الحالة') }} <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            @foreach(['active', 'inactive', 'under_maintenance', 'out_of_service'] as $stat)
                                                <option value="{{ $stat }}" 
                                                    {{ old('status', $device->status) == $stat ? 'selected' : '' }}>
                                                    @php
                                                        $statusNames = [
                                                            'active' => 'نشط',
                                                            'inactive' => 'غير نشط',
                                                            'under_maintenance' => 'قيد الصيانة',
                                                            'out_of_service' => 'خارج الخدمة'
                                                        ];
                                                    @endphp
                                                    {{ $statusNames[$stat] ?? $stat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الحالة الفنية -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الحالة الفنية') }} <span class="text-danger">*</span></label>
                                        <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                            @foreach(['excellent', 'good', 'fair', 'poor'] as $cond)
                                                <option value="{{ $cond }}" 
                                                    {{ old('condition', $device->condition) == $cond ? 'selected' : '' }}>
                                                    @php
                                                        $conditionNames = [
                                                            'excellent' => 'ممتاز',
                                                            'good' => 'جيد',
                                                            'fair' => 'مقبول',
                                                            'poor' => 'سيئ'
                                                        ];
                                                    @endphp
                                                    {{ $conditionNames[$cond] ?? $cond }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الفني المسؤول -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الفني المسؤول') }}</label>
                                        <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                                            <option value="">-- اختر فني --</option>
                                            @foreach ($technicians as $technician)
                                                <option value="{{ $technician->id }}" 
                                                    {{ old('assigned_to', $device->assigned_to) == $technician->id ? 'selected' : '' }}>
                                                    {{ $technician->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- التكلفة -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('سعر الشراء') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="purchase_price" 
                                                   class="form-control @error('purchase_price') is-invalid @enderror" 
                                                   value="{{ old('purchase_price', $device->purchase_price) }}" 
                                                   placeholder="0.00">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                        @error('purchase_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- القيمة الحالية -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('القيمة الحالية') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="current_value" 
                                                   class="form-control @error('current_value') is-invalid @enderror" 
                                                   value="{{ old('current_value', $device->current_value) }}" 
                                                   placeholder="0.00">
                                            <span class="input-group-text">ر.س</span>
                                        </div>
                                        @error('current_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- نسبة الإهلاك -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('نسبة الإهلاك السنوية') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="depreciation_rate" 
                                                   class="form-control @error('depreciation_rate') is-invalid @enderror" 
                                                   value="{{ old('depreciation_rate', $device->depreciation_rate) }}" 
                                                   placeholder="0.00">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('depreciation_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ٦: الضمان والصيانة -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-shield-alt me-2"></i>
                                الضمان والصيانة
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- مزود الخدمة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('مزود الخدمة') }}</label>
                                        <input type="text" name="service_provider" 
                                               class="form-control @error('service_provider') is-invalid @enderror" 
                                               value="{{ old('service_provider', $device->service_provider) }}" 
                                               placeholder="اسم مزود خدمة الصيانة">
                                        @error('service_provider')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- رقم عقد الخدمة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('رقم عقد الخدمة') }}</label>
                                        <input type="text" name="service_contract_number" 
                                               class="form-control @error('service_contract_number') is-invalid @enderror" 
                                               value="{{ old('service_contract_number', $device->service_contract_number) }}" 
                                               placeholder="رقم العقد">
                                        @error('service_contract_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تردد الصيانة الوقائية -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('تردد الصيانة الوقائية') }}</label>
                                        <div class="input-group">
                                            <input type="number" name="preventive_maintenance_frequency" 
                                                   class="form-control @error('preventive_maintenance_frequency') is-invalid @enderror" 
                                                   value="{{ old('preventive_maintenance_frequency', $device->preventive_maintenance_frequency) }}" 
                                                   placeholder="90">
                                            <span class="input-group-text">يوم</span>
                                        </div>
                                        <small class="text-muted">عدد الأيام بين كل صيانة وقائية</small>
                                        @error('preventive_maintenance_frequency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الجزء ٧: المواصفات الفنية -->
                    <div class="section-card mb-4">
                        <div class="section-header bg-light-primary rounded-top p-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-cogs me-2"></i>
                                المواصفات الفنية
                            </h5>
                        </div>
                        <div class="section-body p-4">
                            <div class="row g-3">
                                <!-- متطلبات الطاقة -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('متطلبات الطاقة') }}</label>
                                        <input type="text" name="power_requirements" 
                                               class="form-control @error('power_requirements') is-invalid @enderror" 
                                               value="{{ old('power_requirements', $device->power_requirements) }}" 
                                               placeholder="مثال: 220V AC, 50Hz">
                                        @error('power_requirements')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الأبعاد -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الأبعاد') }}</label>
                                        <input type="text" name="dimensions" 
                                               class="form-control @error('dimensions') is-invalid @enderror" 
                                               value="{{ old('dimensions', $device->dimensions) }}" 
                                               placeholder="مثال: 120x80x200 cm">
                                        @error('dimensions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الوزن -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('الوزن') }}</label>
                                        <div class="input-group">
                                            <input type="text" name="weight" 
                                                   class="form-control @error('weight') is-invalid @enderror" 
                                                   value="{{ old('weight', $device->weight) }}" 
                                                   placeholder="0">
                                            <span class="input-group-text">كجم</span>
                                        </div>
                                        @error('weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ملاحظات -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label fw-semibold mb-2">{{ __('ملاحظات') }}</label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                                  rows="4" 
                                                  placeholder="أي ملاحظات إضافية حول الجهاز...">{{ old('notes', $device->notes) }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="d-flex justify-content-between border-top pt-4">
                                <button type="button" onclick="resetForm()" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-2"></i>
                                    {{ __('إعادة تعيين') }}
                                </button>
                                <div>
                                    <a href="{{ route('devices.show', $device->id) }}" class="btn btn-outline-danger me-2">
                                        <i class="fas fa-times me-2"></i>
                                        {{ __('إلغاء') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas fa-save me-2"></i>
                                        {{ __('حفظ التعديلات') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    @else
        <div class="alert alert-danger border-0 shadow-sm mt-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-danger fs-4 me-3"></i>
                <div>
                    <h5 class="mb-1">{{ __('غير مصرح لك') }}</h5>
                    <p class="mb-0">{{ __('ليس لديك الصلاحيات اللازمة لتعديل الأجهزة.') }}</p>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('styles')
    <style>
        /* نفس الأنماط من صفحة الإضافة */
        .section-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .section-header {
            background: linear-gradient(45deg, #f8f9fa, #fff);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            color: #2a4c7d;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
            background-color: #fff;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #2a4c7d;
            box-shadow: 0 0 0 0.25rem rgba(42, 76, 125, 0.15);
            transform: translateY(-2px);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #6c757d;
            font-weight: 500;
        }

        .btn {
            padding: 10px 25px;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(45deg, #2a4c7d, #3a86ff);
            border: none;
            box-shadow: 0 4px 10px rgba(42, 76, 125, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(42, 76, 125, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // حساب القيمة الحالية بناء على سعر الشراء ومعدل الإهلاك
            const purchasePrice = document.querySelector('input[name="purchase_price"]');
            const depreciationRate = document.querySelector('input[name="depreciation_rate"]');
            const currentValue = document.querySelector('input[name="current_value"]');

            function calculateCurrentValue() {
                if (purchasePrice && purchasePrice.value && depreciationRate && depreciationRate.value) {
                    const price = parseFloat(purchasePrice.value);
                    const rate = parseFloat(depreciationRate.value) / 100;

                    // افتراض أن الجهاز عمره سنة واحدة
                    const years = 1;
                    const depreciation = price * rate * years;
                    const calculatedValue = price - depreciation;

                    if (currentValue && (!currentValue.value || currentValue.value == 0)) {
                        currentValue.value = calculatedValue.toFixed(2);
                    }
                }
            }

            if (purchasePrice) purchasePrice.addEventListener('change', calculateCurrentValue);
            if (depreciationRate) depreciationRate.addEventListener('change', calculateCurrentValue);

            // التحقق من الحقول المطلوبة
            const form = document.getElementById('editDeviceForm');
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    showToast('error', 'يرجى ملء جميع الحقول المطلوبة');
                }
            });

            // وظيفة إعادة التعيين
            window.resetForm = function() {
                if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع التعديلات.')) {
                    form.reset();
                    showToast('success', 'تم إعادة تعيين النموذج بنجاح');
                }
            };

            // وظيفة عرض الرسائل
            function showToast(type, message) {
                const toast = document.createElement('div');
                toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                toast.style.cssText = `
                    top: 100px;
                    right: 20px;
                    z-index: 9999;
                    min-width: 300px;
                    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
                `;
                toast.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 5000);
            }
        });
    </script>
@endpush