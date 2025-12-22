@extends('layouts.app')

@section('title', 'تفاصيل المشروع: ' . $project->name)

@section('content')

    <div class="container-fluid px-4">
        <!-- ترويسة الصفحة -->
        <div class="page-header py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2 text-dark">
                        <i class="fas fa-project-diagram me-2 text-primary"></i>
                        {{ __('تفاصيل المشروع') }}
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                    <i class="fas fa-home me-1"></i>
                                    {{ __('لوحة التحكم') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('projects.index') }}" class="text-decoration-none">
                                    <i class="fas fa-project-diagram me-1"></i>
                                    {{ __('المشاريع') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ Str::limit($project->name, 30) }}
                            </li>
                        </ol>
                    </nav>
                </div>

                <div class="d-flex gap-2">
                    @can('manage projects')
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning d-flex align-items-center">
                            <i class="fas fa-edit me-2"></i> {{ __('تعديل') }}
                        </a>
                    @endcan
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="fas fa-arrow-right me-2"></i> {{ __('رجوع') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- المعلومات الأساسية -->
        <div class="row mb-4">
            <!-- بطاقة معلومات المشروع -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ __('معلومات المشروع') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- رمز المشروع والاسم -->
                            <div class="col-md-12 mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="project-icon bg-primary bg-opacity-10 text-primary rounded-2 p-3 me-3">
                                        <i class="fas fa-project-diagram fa-2x"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-1 text-dark">{{ $project->name }}</h3>
                                        <div class="d-flex align-items-center gap-3">
                                            <code class="bg-light p-2 rounded">{{ $project->code ?? '---' }}</code>
                                            <span
                                                class="badge bg-{{ $project->display_status['class'] }} bg-opacity-10 text-{{ $project->display_status['class'] }} border border-{{ $project->display_status['class'] }}">
                                                {{ $project->display_status['text'] }}
                                            </span>
                                            <span
                                                class="badge bg-{{ $project->display_priority['class'] }} bg-opacity-10 text-{{ $project->display_priority['class'] }}">
                                                <i class="fas fa-{{ $project->display_priority['icon'] }} me-1"></i>
                                                {{ $project->display_priority['text'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات العميل -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">{{ __('العميل') }}</h6>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="avatar-initials bg-info text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px; font-size: 1.1rem;">
                                            {{ $project->client ? substr($project->client->name, 0, 1) : '?' }}
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">
                                            {{ $project->client_name ?? $project->client?->name ?? 'غير محدد' }}
                                        </h5>
                                        <small class="text-muted">{{ $project->display_client_type }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- مدير المشروع -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">{{ __('مدير المشروع') }}</h6>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="avatar-initials bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px; font-size: 1.1rem;">
                                            {{ $project->manager ? substr($project->manager->name, 0, 1) : '?' }}
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $project->manager?->name ?? 'غير معين' }}</h5>
                                        <small class="text-muted">{{ $project->manager?->email ?? '' }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- الموقع -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">{{ __('الموقع') }}</h6>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-primary fa-lg me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $project->city ?? 'غير محدد' }}</h5>
                                        <small class="text-muted">{{ $project->region ?? '' }}</small>
                                    </div>
                                </div>
                                @if($project->address)
                                    <p class="text-muted mt-2 mb-0">
                                        <i class="fas fa-location-dot me-2"></i>{{ $project->address }}
                                    </p>
                                @endif
                            </div>

                            <!-- التواريخ -->
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">{{ __('التواريخ') }}</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-center p-2 bg-light rounded">
                                            <small class="text-muted d-block">{{ __('تاريخ البدء') }}</small>
                                            <strong>{{ $project->start_date ? $project->start_date->format('Y/m/d') : '---' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-2 bg-light rounded">
                                            <small class="text-muted d-block">{{ __('تاريخ الانتهاء') }}</small>
                                            <strong>{{ $project->end_date ? $project->end_date->format('Y/m/d') : '---' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @if($project->days_remaining !== null)
                                    <div class="mt-3">
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

                            <!-- وصف المشروع -->
                            @if($project->description)
                                <div class="col-md-12 mb-3">
                                    <h6 class="text-muted mb-2">{{ __('وصف المشروع') }}</h6>
                                    <div class="p-3 bg-light rounded">
                                        {{ $project->description }}
                                    </div>
                                </div>
                            @endif

                            <!-- معلومات العقد -->
                            @if($project->contract_number || $project->contract_value)
                                <div class="col-md-12 mt-3 pt-3 border-top">
                                    <h6 class="text-muted mb-2">{{ __('معلومات العقد') }}</h6>
                                    <div class="row">
                                        @if($project->contract_number)
                                            <div class="col-md-6">
                                                <small class="text-muted">{{ __('رقم العقد') }}</small>
                                                <p class="mb-0">{{ $project->contract_number }}</p>
                                            </div>
                                        @endif
                                        @if($project->contract_value)
                                            <div class="col-md-6">
                                                <small class="text-muted">{{ __('قيمة العقد') }}</small>
                                                <p class="mb-0">{{ number_format($project->contract_value, 2) }} ر.س</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- الجانب الأيمن - الإحصائيات -->
            <div class="col-lg-4">
                <!-- شريط التقدم -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-chart-line me-2"></i>
                            {{ __('تقدم المشروع') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block mb-3">
                                <div class="circular-progress" data-percent="{{ $project->progress_percentage }}"
                                    data-color="{{ $project->is_overdue ? '#dc3545' : '#28a745' }}">
                                    <div class="inner"></div>
                                    <div class="number">
                                        <h2 class="mb-0">{{ round($project->progress_percentage) }}<span>%</span></h2>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-2">نسبة الإنجاز</h5>
                            <p class="text-muted mb-0">
                                {{ $project->elapsed_days ?? 0 }} يوم منقضي من {{ $project->duration_days ?? 0 }} يوم
                            </p>
                        </div>
                    </div>
                </div>

                <!-- الإحصائيات المالية -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-calculator me-2"></i>
                            {{ __('الإحصائيات المالية') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">{{ __('الميزانية') }}</h6>
                            <h3 class="text-primary">{{ number_format($project->budget ?? 0, 2) }} <small
                                    class="fs-6">ر.س</small></h3>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-muted mb-2">{{ __('التكلفة الفعلية') }}</h6>
                            <h3 class="{{ $project->actual_cost > $project->budget ? 'text-danger' : 'text-success' }}">
                                {{ number_format($project->actual_cost ?? 0, 2) }} <small class="fs-6">ر.س</small>
                            </h3>
                        </div>

                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar {{ $project->isWithinBudget() ? 'bg-success' : 'bg-danger' }}"
                                role="progressbar" style="width: {{ $project->getBudgetUsagePercentage() }}%"
                                aria-valuenow="{{ $project->getBudgetUsagePercentage() }}" aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <small class="text-muted">نسبة الاستهلاك</small>
                            <small class="{{ $project->isWithinBudget() ? 'text-success' : 'text-danger' }}">
                                {{ round($project->getBudgetUsagePercentage(), 1) }}%
                            </small>
                        </div>
                    </div>
                </div>

                <!-- إحصائيات سريعة -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-chart-pie me-2"></i>
                            {{ __('إحصائيات سريعة') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light-primary rounded">
                                    <i class="fas fa-microscope fa-2x text-primary mb-2"></i>
                                    <h3 class="mb-0">{{ $projectStats['total_devices'] ?? 0 }}</h3>
                                    <small class="text-muted">{{ __('إجمالي الأجهزة') }}</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light-success rounded">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <h3 class="mb-0">{{ $projectStats['active_devices'] ?? 0 }}</h3>
                                    <small class="text-muted">{{ __('الأجهزة النشطة') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light-warning rounded">
                                    <i class="fas fa-tools fa-2x text-warning mb-2"></i>
                                    <h3 class="mb-0">{{ $projectStats['maintenance_devices'] ?? 0 }}</h3>
                                    <small class="text-muted">{{ __('قيد الصيانة') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light-danger rounded">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                    <h3 class="mb-0">{{ $projectStats['open_breakdowns'] ?? 0 }}</h3>
                                    <small class="text-muted">{{ __('أعطال مفتوحة') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- التبويبات -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-0">
                        <ul class="nav nav-tabs" id="projectTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="devices-tab" data-bs-toggle="tab"
                                    data-bs-target="#devices" type="button" role="tab">
                                    <i class="fas fa-microscope me-2"></i>{{ __('الأجهزة') }}
                                    <span class="badge bg-primary ms-2">{{ $projectStats['total_devices'] ?? 0 }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="maintenance-tab" data-bs-toggle="tab"
                                    data-bs-target="#maintenance" type="button" role="tab">
                                    <i class="fas fa-tools me-2"></i>{{ __('الصيانة') }}
                                    <span
                                        class="badge bg-warning ms-2">{{ $projectStats['maintenance_devices'] ?? 0 }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="breakdowns-tab" data-bs-toggle="tab"
                                    data-bs-target="#breakdowns" type="button" role="tab">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ __('الأعطال') }}
                                    <span class="badge bg-danger ms-2">{{ $projectStats['open_breakdowns'] ?? 0 }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity"
                                    type="button" role="tab">
                                    <i class="fas fa-history me-2"></i>{{ __('النشاطات') }}
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="projectTabsContent">
                            <!-- تبويب الأجهزة -->
                            <div class="tab-pane fade show active" id="devices" role="tabpanel">
                                <div class="p-4">
                                    @if($project->devices->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>{{ __('الجهاز') }}</th>
                                                        <th>{{ __('الرقم التسلسلي') }}</th>
                                                        <th>{{ __('النوع') }}</th>
                                                        <th>{{ __('الحالة') }}</th>
                                                        <th>{{ __('الموقع') }}</th>
                                                        <th>{{ __('الإجراءات') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($project->devices as $device)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="device-icon bg-primary bg-opacity-10 text-primary rounded-2 p-2 me-3">
                                                                        <i class="fas fa-microscope"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0">{{ $device->display_name }}</h6>
                                                                        <small
                                                                            class="text-muted">{{ $device->model ?? '---' }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <code
                                                                    class="bg-light p-1 rounded">{{ $device->serial_number }}</code>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                                    {{ ucfirst($device->device_type) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $statusConfig = [
                                                                        'active' => ['class' => 'success', 'text' => 'نشط'],
                                                                        'inactive' => ['class' => 'secondary', 'text' => 'غير نشط'],
                                                                        'under_maintenance' => ['class' => 'warning', 'text' => 'قيد الصيانة'],
                                                                        'out_of_service' => ['class' => 'danger', 'text' => 'خارج الخدمة']
                                                                    ];
                                                                    $config = $statusConfig[$device->status] ?? $statusConfig['inactive'];
                                                                @endphp
                                                                <span
                                                                    class="badge bg-{{ $config['class'] }} bg-opacity-10 text-{{ $config['class'] }}">
                                                                    {{ $config['text'] }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <small>
                                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                                    {{ $device->location ?? 'غير محدد' }}
                                                                </small>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('devices.show', $device->id) }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-microscope fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد أجهزة مرتبطة بهذا المشروع</h5>
                                            <p class="text-muted">قم بإضافة أجهزة إلى هذا المشروع</p>
                                            @can('manage devices')
                                                <a href="{{ route('devices.create') }}?project_id={{ $project->id }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>إضافة جهاز
                                                </a>
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- تبويب الصيانة -->
                            <div class="tab-pane fade" id="maintenance" role="tabpanel">
                                <div class="p-4">
                                    @if($project->pmPlans->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>{{ __('الجهاز') }}</th>
                                                        <th>{{ __('نوع الصيانة') }}</th>
                                                        <th>{{ __('تاريخ الاستحقاق') }}</th>
                                                        <th>{{ __('الحالة') }}</th>
                                                        <th>{{ __('الفني') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($project->pmPlans as $plan)
                                                        <tr>
                                                            <td>{{ $plan->device->display_name ?? '---' }}</td>
                                                            <td>{{ $plan->type }}</td>
                                                            <td>
                                                                @if($plan->due_date)
                                                                    <span
                                                                        class="badge {{ $plan->due_date < now() ? 'bg-danger' : 'bg-warning' }}">
                                                                        {{ $plan->due_date->format('Y/m/d') }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $plan->status == 'completed' ? 'success' : 'warning' }}">
                                                                    {{ $plan->status == 'completed' ? 'مكتمل' : 'معلق' }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $plan->assigned_to ? $plan->assignedTechnician->name : '---' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد خطط صيانة</h5>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- تبويب الأعطال -->
                            <div class="tab-pane fade" id="breakdowns" role="tabpanel">
                                <div class="p-4">
                                    @if($project->breakdowns->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>{{ __('الجهاز') }}</th>
                                                        <th>{{ __('نوع العطل') }}</th>
                                                        <th>{{ __('تاريخ الإبلاغ') }}</th>
                                                        <th>{{ __('الحالة') }}</th>
                                                        <th>{{ __('الأولوية') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($project->breakdowns as $breakdown)
                                                        <tr>
                                                            <td>{{ $breakdown->device->display_name ?? '---' }}</td>
                                                            <td>{{ $breakdown->breakdown_type }}</td>
                                                            <td>{{ $breakdown->reported_at->format('Y/m/d') }}</td>
                                                            <td>
                                                                @php
                                                                    $statusConfig = [
                                                                        'open' => ['class' => 'danger', 'text' => 'مفتوح'],
                                                                        'assigned' => ['class' => 'warning', 'text' => 'معين'],
                                                                        'in_progress' => ['class' => 'info', 'text' => 'قيد التنفيذ'],
                                                                        'closed' => ['class' => 'success', 'text' => 'مغلق']
                                                                    ];
                                                                    $config = $statusConfig[$breakdown->status] ?? $statusConfig['open'];
                                                                @endphp
                                                                <span class="badge bg-{{ $config['class'] }}">
                                                                    {{ $config['text'] }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $breakdown->priority == 'high' ? 'danger' : ($breakdown->priority == 'medium' ? 'warning' : 'info') }}">
                                                                    {{ $breakdown->priority == 'high' ? 'عالي' : ($breakdown->priority == 'medium' ? 'متوسط' : 'منخفض') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">لا توجد أعطال</h5>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- تبويب النشاطات -->
                            <div class="tab-pane fade" id="activity" role="tabpanel">
                                <div class="p-4">
                                    <div class="timeline">
                                        <!-- يمكن إضافة سجل النشاطات هنا -->
                                        <div class="text-center py-5">
                                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">سجل النشاطات</h5>
                                            <p class="text-muted">سيظهر هنا آخر النشاطات على المشروع</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ملاحظات إضافية -->
        @if($project->notes)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-sticky-note me-2"></i>
                                {{ __('ملاحظات إضافية') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-light rounded">
                                {{ $project->notes }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        .project-icon {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-initials {
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .circular-progress {
            width: 120px;
            height: 120px;
            position: relative;
        }

        .circular-progress .inner {
            position: absolute;
            z-index: 6;
            top: 50%;
            left: 50%;
            height: 100px;
            width: 100px;
            margin: -50px 0 0 -50px;
            background: #fff;
            border-radius: 100%;
        }

        .circular-progress .number {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            font-size: 18px;
            font-weight: 500;
            color: #4158d0;
        }

        .circular-progress canvas {
            position: absolute;
            top: 0;
            left: 0;
        }

        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1) !important;
        }

        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }

        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }

        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }

        .device-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            color: #6c757d;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link.active {
            color: #2a4c7d;
            background-color: transparent;
            border-bottom: 3px solid #2a4c7d;
        }

        @media (max-width: 768px) {
            .project-icon {
                width: 50px;
                height: 50px;
            }

            .nav-tabs .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .circular-progress {
                width: 100px;
                height: 100px;
            }

            .circular-progress .inner {
                height: 80px;
                width: 80px;
                margin: -40px 0 0 -40px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // رسم دائرة التقدم
            const progressCircle = document.querySelector('.circular-progress');
            if (progressCircle) {
                const percent = progressCircle.getAttribute('data-percent');
                const color = progressCircle.getAttribute('data-color');

                const canvas = document.createElement('canvas');
                canvas.width = 120;
                canvas.height = 120;
                progressCircle.appendChild(canvas);

                const ctx = canvas.getContext('2d');
                const x = canvas.width / 2;
                const y = canvas.height / 2;
                const radius = 54;
                const startAngle = 1.5 * Math.PI;
                const endAngle = startAngle + (percent / 100) * 2 * Math.PI;

                // الخلفية
                ctx.beginPath();
                ctx.arc(x, y, radius, 0, 2 * Math.PI);
                ctx.fillStyle = '#f8f9fa';
                ctx.fill();

                // شريط التقدم
                ctx.beginPath();
                ctx.arc(x, y, radius, startAngle, endAngle);
                ctx.strokeStyle = color;
                ctx.lineWidth = 8;
                ctx.lineCap = 'round';
                ctx.stroke();
            }

            // معاينة التبويبات عند التحميل
            const hash = window.location.hash;
            if (hash) {
                const tab = document.querySelector(`[data-bs-target="${hash}"]`);
                if (tab) {
                    const bsTab = new bootstrap.Tab(tab);
                    bsTab.show();
                }
            }
        });
    </script>
@endpush