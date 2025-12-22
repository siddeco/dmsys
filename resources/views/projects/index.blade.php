@extends('layouts.app')

@section('title', 'إدارة المشاريع')

@section('content')
    <div class="container-fluid px-4">
        <!-- ترويسة الصفحة -->
        <div class="page-header py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 text-dark">
                        <i class="fas fa-project-diagram me-2 text-primary"></i>
                        {{ __('إدارة المشاريع') }}
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
                                <i class="fas fa-project-diagram me-1"></i>
                                {{ __('المشاريع') }}
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex gap-2">
                    @can('manage projects')
                        <a href="{{ route('projects.create') }}" class="btn btn-primary d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i> {{ __('إضافة مشروع') }}
                        </a>
                        <a href="{{ route('projects.completed') }}" class="btn btn-outline-success d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i> {{ __('المكتملة') }}
                        </a>
                        <a href="{{ route('projects.overdue') }}" class="btn btn-outline-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ __('المتأخرة') }}
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
                                <i class="fas fa-project-diagram fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('إجمالي المشاريع') }}</h6>
                                <h3 class="mb-0">{{ $stats['total'] ?? $projects->total() }}</h3>
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
                                <i class="fas fa-play-circle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('نشطة') }}</h6>
                                <h3 class="mb-0">{{ $stats['active'] ?? $projects->where('status', 'active')->count() }}
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
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('قريب الانتهاء') }}</h6>
                                <h3 class="mb-0">{{ $stats['ending_soon'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-stats border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-3 p-3 me-3">
                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">{{ __('متأخرة') }}</h6>
                                <h3 class="mb-0">{{ $stats['overdue'] ?? 0 }}</h3>
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
                    <i class="fas fa-filter me-2"></i>{{ __('فلترة المشاريع') }}
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('projects.index') }}" class="row g-3">
                    <!-- البحث -->
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('بحث') }}</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="q" class="form-control form-control-sm border-start-0"
                                placeholder="ابحث بالاسم، الرمز، العميل..." value="{{ request('q') }}">
                        </div>
                    </div>

                    <!-- الحالة -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('الحالة') }}</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="">{{ __('جميع الحالات') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('نشط') }}
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                {{ __('مكتمل') }}</option>
                            <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>{{ __('متوقف') }}
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                {{ __('ملغي') }}</option>
                        </select>
                    </div>

                    <!-- الأولوية -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('الأولوية') }}</label>
                        <select name="priority" class="form-select form-select-sm">
                            <option value="">{{ __('جميع الأولويات') }}</option>
                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ __('منخفضة') }}
                            </option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>{{ __('متوسطة') }}
                            </option>
                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ __('عالية') }}
                            </option>
                        </select>
                    </div>

                    <!-- المنطقة -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('المنطقة') }}</label>
                        <select name="region" class="form-select form-select-sm">
                            <option value="">{{ __('جميع المناطق') }}</option>
                            @foreach(['الرياض', 'مكة المكرمة', 'المدينة المنورة', 'القصيم', 'الشرقية', 'عسير', 'تبوك', 'حائل', 'الحدود الشمالية', 'جازان', 'نجران', 'الباحة', 'الجوف'] as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ $region }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- نوع العميل -->
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label small fw-medium text-muted mb-1">{{ __('نوع العميل') }}</label>
                        <select name="client_type" class="form-select form-select-sm">
                            <option value="">{{ __('جميع الأنواع') }}</option>
                            <option value="hospital" {{ request('client_type') == 'hospital' ? 'selected' : '' }}>
                                {{ __('مستشفى') }}</option>
                            <option value="clinic" {{ request('client_type') == 'clinic' ? 'selected' : '' }}>
                                {{ __('عيادة') }}</option>
                            <option value="laboratory" {{ request('client_type') == 'laboratory' ? 'selected' : '' }}>
                                {{ __('مختبر') }}</option>
                            <option value="pharmacy" {{ request('client_type') == 'pharmacy' ? 'selected' : '' }}>
                                {{ __('صيدلية') }}</option>
                            <option value="government" {{ request('client_type') == 'government' ? 'selected' : '' }}>
                                {{ __('حكومي') }}</option>
                            <option value="company" {{ request('client_type') == 'company' ? 'selected' : '' }}>
                                {{ __('شركة') }}</option>
                            <option value="other" {{ request('client_type') == 'other' ? 'selected' : '' }}>{{ __('أخرى') }}
                            </option>
                        </select>
                    </div>

                    <!-- أزرار الإجراء -->
                    <div class="col-lg-1 col-md-6 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-1"></i> {{ __('تطبيق') }}
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary btn-sm"
                                title="{{ __('إعادة تعيين') }}">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- رسائل النجاح -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- جدول المشاريع -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;">{{ __('الرمز') }}</th>
                                <th style="min-width: 250px;">{{ __('معلومات المشروع') }}</th>
                                <th style="min-width: 120px;">{{ __('العميل') }}</th>
                                <th style="min-width: 100px;">{{ __('المسؤول') }}</th>
                                <th style="min-width: 120px;">{{ __('التواريخ') }}</th>
                                <th style="min-width: 100px;">{{ __('الحالة') }}</th>
                                <th class="text-center" style="width: 120px;">{{ __('الإجراءات') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr class="border-bottom">
                                    <!-- Code Column -->
                                    <td class="ps-4">
                                        <div class="fw-medium text-muted">
                                            <code>{{ $project->code ?? '---' }}</code>
                                        </div>
                                    </td>

                                    <!-- Project Information Column -->
                                    <td>
                                        <div class="d-flex align-items-start">
                                            <!-- Project Icon -->
                                            <div class="flex-shrink-0 me-3">
                                                <div class="project-icon bg-primary bg-opacity-10 text-primary rounded-2 p-2">
                                                    <i class="fas fa-project-diagram fa-lg"></i>
                                                </div>
                                            </div>

                                            <!-- Project Details -->
                                            <div class="flex-grow-1">
                                                <!-- Project Name -->
                                                <h6 class="mb-1 fw-semibold text-dark">
                                                    <a href="{{ route('projects.show', $project->id) }}"
                                                        class="text-decoration-none text-dark">
                                                        {{ $project->name }}
                                                    </a>
                                                </h6>

                                                <!-- Progress Bar -->
                                                <div class="progress mb-2" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $project->progress_percentage }}%"
                                                        aria-valuenow="{{ $project->progress_percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>

                                                <!-- Info -->
                                                <div class="d-flex flex-wrap gap-2 mt-1">
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-map-marker-alt me-1 fa-xs"></i>
                                                        {{ $project->city ?? 'غير محدد' }}
                                                    </span>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-calculator me-1 fa-xs"></i>
                                                        {{ number_format($project->budget ?? 0) }} ر.س
                                                    </span>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-microscope me-1 fa-xs"></i>
                                                        {{ $project->devices_count ?? $project->devices->count() }} أجهزة
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Client Column -->
                                    <td>
                                        <div class="small">
                                            <div class="mb-1">
                                                <strong>{{ $project->client_name ?? $project->client?->name ?? 'غير محدد' }}</strong>
                                            </div>
                                            <div class="text-muted">
                                                <i class="fas fa-tag me-1 fa-xs"></i>
                                                {{ $project->display_client_type }}
                                            </div>
                                            @if($project->contract_number)
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="fas fa-file-contract me-1 fa-xs"></i>
                                                        {{ $project->contract_number }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Manager Column -->
                                    <td>
                                        @if($project->manager)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <div class="avatar-initials bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                        {{ substr($project->manager->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-medium" style="font-size: 0.85rem;">
                                                        {{ $project->manager->name }}
                                                    </div>
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        مدير المشروع
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">غير معين</span>
                                        @endif
                                    </td>

                                    <!-- Dates Column -->
                                    <td>
                                        <div class="small">
                                            <div class="mb-1">
                                                <span class="text-muted">{{ __('البدء:') }}</span>
                                                <span
                                                    class="fw-medium">{{ $project->start_date ? $project->start_date->format('Y/m/d') : '---' }}</span>
                                            </div>
                                            <div class="mb-1">
                                                <span class="text-muted">{{ __('الانتهاء:') }}</span>
                                                <span
                                                    class="fw-medium">{{ $project->end_date ? $project->end_date->format('Y/m/d') : '---' }}</span>
                                            </div>
                                            @if($project->days_remaining !== null)
                                                <div>
                                                    @if($project->is_overdue)
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            تأخر {{ abs($project->days_remaining) }} يوم
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-clock me-1"></i>
                                                            متبقي {{ $project->days_remaining }} يوم
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Status Column -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="status-indicator me-2">
                                                <i class="fas fa-circle text-{{ $project->display_status['class'] }}"></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="badge bg-{{ $project->display_status['class'] }} bg-opacity-10 text-{{ $project->display_status['class'] }} border border-{{ $project->display_status['class'] }} border-opacity-25">
                                                    {{ $project->display_status['text'] }}
                                                </span>
                                                <div class="mt-1">
                                                    <span
                                                        class="badge bg-{{ $project->display_priority['class'] }} bg-opacity-10 text-{{ $project->display_priority['class'] }}">
                                                        <i
                                                            class="fas fa-{{ $project->display_priority['icon'] }} me-1 fa-xs"></i>
                                                        {{ $project->display_priority['text'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('projects.show', $project->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-start-2" data-bs-toggle="tooltip"
                                                title="{{ __('عرض التفاصيل') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @can('manage projects')
                                                <!-- Edit Button -->
                                                <a href="{{ route('projects.edit', $project->id) }}"
                                                    class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip"
                                                    title="{{ __('تعديل') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <button type="button" class="btn btn-sm btn-outline-danger rounded-end-2"
                                                    data-bs-toggle="tooltip" title="{{ __('حذف') }}"
                                                    onclick="confirmDelete({{ $project->id }}, '{{ addslashes($project->name) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-project-diagram fa-3x text-muted"></i>
                                            </div>
                                            <h5 class="mt-3 text-muted">{{ __('لا توجد مشاريع') }}</h5>
                                            <p class="text-muted mb-4">{{ __('ابدأ بإضافة أول مشروع في النظام') }}</p>
                                            @can('manage projects')
                                                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i> {{ __('إضافة أول مشروع') }}
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
            @if($projects->hasPages())
                <div class="card-footer bg-white border-top-0 py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('عرض') }} <strong>{{ $projects->firstItem() ?: 0 }}</strong> {{ __('إلى') }}
                            <strong>{{ $projects->lastItem() ?: 0 }}</strong> {{ __('من أصل') }}
                            <strong>{{ $projects->total() }}</strong> {{ __('مشروع') }}
                        </div>
                        <div>
                            {{ $projects->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- نموذج حذف مخفي -->
        @can('manage projects')
            <form id="deleteForm" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endcan
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

        /* آيكون المشروع */
        .project-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* شريط التقدم */
        .progress {
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.6s ease;
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

        /* حالة المشروع */
        .status-indicator {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* صورة المستخدم */
        .avatar-initials {
            font-weight: 600;
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

            .project-icon {
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

            .d-flex.gap-2 {
                gap: 0.5rem !important;
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
            const filterSelects = document.querySelectorAll('select[name="status"], select[name="priority"], select[name="region"], select[name="client_type"]');
            filterSelects.forEach(select => {
                select.addEventListener('change', function () {
                    this.form.submit();
                });
            });

            // تأثير ظهور الصفوف
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

            // إغلاق التنبيهات تلقائياً
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // تأكيد الحذف
        function confirmDelete(projectId, projectName) {
            Swal.fire({
                title: 'تأكيد الحذف',
                html: `هل أنت متأكد من رغبتك في حذف المشروع<br><strong>${projectName}</strong>؟`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.action = `/projects/${projectId}`;
                    deleteForm.submit();
                }
            });
        }

        // إذا كان SweetAlert غير محمل، استخدم confirm عادي
        if (typeof Swal === 'undefined') {
            function confirmDelete(projectId, projectName) {
                if (confirm(`هل أنت متأكد من رغبتك في حذف المشروع "${projectName}"؟`)) {
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.action = `/projects/${projectId}`;
                    deleteForm.submit();
                }
            }
        }
    </script>
@endpush