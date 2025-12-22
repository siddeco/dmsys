@extends('layouts.app')

@section('title', 'إدارة الأجهزة الطبية')

@section('content')
    <div class="container-fluid px-4">
        <!-- ترويسة الصفحة -->
        <div class="page-header py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 text-dark">
                        <i class="fas fa-microscope me-2 text-primary"></i>
                        {{ __('الأجهزة الطبية') }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                    <i class="fas fa-home me-1"></i>
                                    {{ __('لوحة التحكم') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-microscope me-1"></i>
                                {{ __('الأجهزة') }}
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex gap-2">
                    @can('manage devices')
                        <a href="{{ route('devices.create') }}" class="btn btn-primary d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i> {{ __('إضافة جهاز') }}
                        </a>
                        <a href="{{ route('devices.archived') }}" class="btn btn-outline-secondary d-flex align-items-center">
                            <i class="fas fa-archive me-2"></i> {{ __('الأرشيف') }}
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- الإحصائيات -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                                <i class="fas fa-microscope fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('إجمالي الأجهزة') }}</h6>
                                <h3 class="mb-0">{{ $stats['total'] ?? $devices->total() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('نشطة') }}</h6>
                                <h3 class="mb-0">{{ $stats['active'] ?? $devices->where('status', 'active')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                                <i class="fas fa-tools fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('قيد الصيانة') }}</h6>
                                <h3 class="mb-0">
                                    {{ $stats['maintenance'] ?? $devices->where('status', 'under_maintenance')->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-3 me-3">
                                <i class="fas fa-hospital fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('المشاريع') }}</h6>
                                <h3 class="mb-0">{{ $stats['projects'] ?? $projects->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقة الفلترة -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 text-primary">
                    <i class="fas fa-filter me-2"></i>{{ __('فلترة الأجهزة') }}
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('devices.index') }}" class="row g-3">
                    <!-- البحث -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('بحث') }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="q" class="form-control form-control-sm border-start-0"
                                placeholder="ابحث بالاسم، الرقم التسلسلي، الموديل..." value="{{ request('q') }}">
                        </div>
                    </div>

                    <!-- الحالة -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('الحالة') }}</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('جميع الحالات') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('نشط') }}
                            </option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                {{ __('غير نشط') }}
                            </option>
                            <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>{{ __('قيد الصيانة') }}</option>
                            <option value="out_of_service" {{ request('status') == 'out_of_service' ? 'selected' : '' }}>
                                {{ __('خارج الخدمة') }}
                            </option>
                        </select>
                    </div>

                    <!-- المشروع -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('المشروع') }}</label>
                        <select name="project_id" class="form-select form-select-sm">
                            <option value="">{{ __('جميع المشاريع') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- نوع الجهاز -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('نوع الجهاز') }}</label>
                        <select name="device_type" class="form-select form-select-sm">
                            <option value="">{{ __('جميع الأنواع') }}</option>
                            @foreach(['xray', 'ultrasound', 'mri', 'ct_scanner', 'ventilator', 'monitor', 'defibrillator', 'analyzer', 'centrifuge', 'microscope', 'autoclave', 'incubator', 'other'] as $type)
                                <option value="{{ $type }}" {{ request('device_type') == $type ? 'selected' : '' }}>
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
                    </div>

                    <!-- الترتيب -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('الترتيب') }}</label>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>{{ __('الأحدث') }}</option>
                            <option value="name->en" {{ request('sort') == 'name->en' ? 'selected' : '' }}>{{ __('الاسم') }}
                            </option>
                            <option value="serial_number" {{ request('sort') == 'serial_number' ? 'selected' : '' }}>
                                {{ __('رقم تسلسلي') }}
                            </option>
                            <option value="installation_date" {{ request('sort') == 'installation_date' ? 'selected' : '' }}>
                                {{ __('تاريخ التركيب') }}
                            </option>
                        </select>
                    </div>

                    <!-- أزرار الإجراء -->
                    <div class="col-lg-1 col-md-6 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-1"></i> {{ __('تطبيق') }}
                            </button>
                            <a href="{{ route('devices.index') }}" class="btn btn-outline-secondary btn-sm"
                                title="{{ __('إعادة تعيين') }}">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- جدول الأجهزة -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 50px;">#</th>
                                <th style="min-width: 220px;">{{ __('معلومات الجهاز') }}</th>
                                <th style="min-width: 140px;">{{ __('المواصفات') }}</th>
                                <th style="min-width: 120px;">{{ __('الموقع') }}</th>
                                <th style="min-width: 100px;">{{ __('الحالة') }}</th>
                                <th class="text-center" style="width: 120px;">{{ __('الإجراءات') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($devices as $device)
                                <tr class="border-bottom">
                                    <!-- ID Column -->
                                    <td class="ps-4">
                                        <div class="fw-medium text-muted">#{{ $device->id }}</div>
                                    </td>

                                    <!-- Device Information Column -->
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <!-- Device Icon -->
                                            <div class="flex-shrink-0 me-3">
                                                <div class="device-icon bg-primary bg-opacity-10 text-primary rounded-2 p-2">
                                                    @php
                                                        $deviceIcons = [
                                                            'xray' => 'fas fa-radiation',
                                                            'ultrasound' => 'fas fa-wave-square',
                                                            'mri' => 'fas fa-magnet',
                                                            'ct_scanner' => 'fas fa-scanner',
                                                            'ventilator' => 'fas fa-lungs',
                                                            'monitor' => 'fas fa-heartbeat',
                                                            'defibrillator' => 'fas fa-heartbeat',
                                                            'analyzer' => 'fas fa-vial',
                                                            'centrifuge' => 'fas fa-spinner',
                                                            'microscope' => 'fas fa-microscope',
                                                            'autoclave' => 'fas fa-temperature-high',
                                                            'incubator' => 'fas fa-baby',
                                                            'other' => 'fas fa-microscope'
                                                        ];
                                                    @endphp
                                                    <i
                                                        class="{{ $deviceIcons[$device->device_type] ?? 'fas fa-microscope' }} fa-lg"></i>
                                                </div>
                                            </div>

                                            <!-- Device Details -->
                                            <div class="flex-grow-1">
                                                <!-- Device Name -->
                                                <h6 class="mb-1 fw-semibold text-dark">
                                                    <a href="{{ route('devices.show', $device->id) }}"
                                                        class="text-decoration-none text-dark">
                                                        {{ $device->display_name }}
                                                    </a>
                                                </h6>

                                                <!-- Serial Number -->
                                                <div class="mb-1">
                                                    <small class="text-muted me-2">
                                                        <i class="fas fa-hashtag fa-xs me-1"></i>
                                                        <strong>{{ __('الرقم التسلسلي:') }}</strong>
                                                        <span class="fw-medium">{{ $device->serial_number }}</span>
                                                    </small>
                                                </div>

                                                <!-- Model & Manufacturer -->
                                                <div class="d-flex flex-wrap gap-2 mt-1">
                                                    @if($device->model)
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fas fa-cube me-1 fa-xs"></i>
                                                            {{ $device->model }}
                                                        </span>
                                                    @endif
                                                    @if($device->manufacturer)
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fas fa-industry me-1 fa-xs"></i>
                                                            {{ $device->manufacturer }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Project -->
                                                @if($device->project)
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-hospital me-1 fa-xs"></i>
                                                            <strong>{{ __('المشروع:') }}</strong> {{ $device->project->name }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Specifications Column -->
                                    <td>
                                        <div class="small">
                                            <div class="mb-1">
                                                <span class="text-muted">{{ __('النوع:') }}</span>
                                                <span class="fw-medium">
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
                                                    {{ $typeNames[$device->device_type] ?? 'غير محدد' }}
                                                </span>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-muted">{{ __('التصنيف:') }}</span>
                                                <span class="fw-medium">
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
                                                    {{ $categoryNames[$device->category] ?? 'غير محدد' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-muted">{{ __('تاريخ التركيب:') }}</span>
                                                <span
                                                    class="fw-medium">{{ $device->installation_date ? $device->installation_date->format('Y/m/d') : 'غير محدد' }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Location Column -->
                                    <td>
                                        <div class="small">
                                            <div class="mb-1">
                                                <i class="fas fa-map-marker-alt text-primary me-1 fa-xs"></i>
                                                <span class="fw-medium">{{ $device->location ?? 'غير محدد' }}</span>
                                            </div>
                                            <div class="text-muted">
                                                {{ $device->city ?? 'غير محدد' }}
                                                @if($device->room_number)
                                                    <span class="ms-1">• {{ __('غرفة') }} {{ $device->room_number }}</span>
                                                @endif
                                                @if($device->floor)
                                                    <span class="ms-1">• {{ __('طابق') }} {{ $device->floor }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Status Column -->
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'active' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'نشط'],
                                                'inactive' => ['class' => 'secondary', 'icon' => 'minus-circle', 'text' => 'غير نشط'],
                                                'under_maintenance' => ['class' => 'warning', 'icon' => 'tools', 'text' => 'قيد الصيانة'],
                                                'out_of_service' => ['class' => 'danger', 'icon' => 'exclamation-triangle', 'text' => 'خارج الخدمة']
                                            ];
                                            $config = $statusConfig[$device->status] ?? $statusConfig['inactive'];
                                        @endphp

                                        <div class="d-flex align-items-center">
                                            <div class="status-indicator me-2">
                                                <i class="fas fa-{{ $config['icon'] }} text-{{ $config['class'] }}"></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="badge bg-{{ $config['class'] }} bg-opacity-10 text-{{ $config['class'] }} border border-{{ $config['class'] }} border-opacity-25">
                                                    {{ $config['text'] }}
                                                </span>
                                                @if($device->condition)
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            {{ __('الحالة الفنية:') }} <span
                                                                class="fw-medium">{{ __($device->condition) }}</span>
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('devices.show', $device->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-start-2" data-bs-toggle="tooltip"
                                                title="{{ __('عرض التفاصيل') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @can('manage devices')
                                                <!-- Edit Button -->
                                                <a href="{{ route('devices.edit', $device->id) }}"
                                                    class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                    title="{{ __('تعديل') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Archive Button -->
                                                <form action="{{ route('devices.archive', $device->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-end-2"
                                                        data-bs-toggle="tooltip" title="{{ __('أرشفة') }}"
                                                        onclick="return confirm('هل أنت متأكد من رغبتك في أرشفة هذا الجهاز؟')">
                                                        <i class="fas fa-archive"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-microscope fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mt-3 text-muted">{{ __('لا توجد أجهزة') }}</h5>
                                            <p class="text-muted mb-4">{{ __('ابدأ بإضافة أول جهاز طبي في النظام') }}</p>
                                            @can('manage devices')
                                                <a href="{{ route('devices.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i> {{ __('إضافة أول جهاز') }}
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- التصفح -->
            @if($devices->hasPages())
                <div class="card-footer bg-white border-top-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('عرض') }} <strong>{{ $devices->firstItem() ?: 0 }}</strong> {{ __('إلى') }}
                            <strong>{{ $devices->lastItem() ?: 0 }}</strong> {{ __('من أصل') }}
                            <strong>{{ $devices->total() }}</strong> {{ __('جهاز') }}
                        </div>
                        <div>
                            {{ $devices->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* تحسينات الجدول */
        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(42, 76, 125, 0.03) !important;
        }

        /* رأس الجدول */
        .table-light {
            background-color: #f8f9fa !important;
            border-bottom: 2px solid #e9ecef;
        }

        .table-light th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            padding: 1rem 1.5rem;
            border: none;
        }

        /* خلايا الجدول */
        .table td {
            padding: 1.25rem 1.5rem;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }

        /* آيكون الجهاز */
        .device-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* الأزرار */
        .btn-group .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 6px !important;
            border-bottom-left-radius: 6px !important;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 6px !important;
            border-bottom-right-radius: 6px !important;
        }

        /* حالة الجهاز */
        .status-indicator {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(var(--bs-primary-rgb), 0.1);
        }

        /* البادجات */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }

        /* حالة فارغة */
        .empty-state {
            padding: 3rem 0;
        }

        .empty-state-icon {
            opacity: 0.3;
        }

        /* الكروت الإحصائية */
        .card-stats {
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform: translateY(-3px);
        }

        .icon-shape {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* التجاوب */
        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 8px;
                border: 1px solid #e9ecef;
            }

            .table td {
                padding: 1rem;
            }

            .device-icon {
                width: 40px;
                height: 40px;
            }

            .btn-group {
                flex-wrap: wrap;
                gap: 2px;
            }

            .btn-group .btn {
                border-radius: 4px !important;
                margin: 1px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // تهيئة الأدوات المساعدة
            var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltips.map(function (el) {
                return new bootstrap.Tooltip(el);
            });

            // فلتر تلقائي للمحددات
            const filterSelects = document.querySelectorAll('select[name="status"], select[name="project_id"], select[name="device_type"], select[name="sort"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', function () {
                    this.form.submit();
                });
            });

            // تحسين تجربة البحث على الهواتف
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput && window.innerWidth < 768) {
                searchInput.placeholder = "بحث...";
            }

            // إضافة تأثير عند التمرير
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                row.style.transition = 'opacity 0.3s, transform 0.3s';

                setTimeout(() => {
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
@endpush