@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
        <h1 class="h2">لوحة التحكم</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">اليوم</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">هذا الأسبوع</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">هذا الشهر</button>
            </div>
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-download me-2"></i>تصدير تقرير
            </button>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white border-0 shadow">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3">مرحباً بعودتك، {{ Auth::user()->name }} 👋</h5>
                            <p class="card-text mb-0">إليك نظرة سريعة على أداء نظام إدارة الأجهزة الطبية.</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <i class="fas fa-chart-line fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="fas fa-microscope fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">+12%</span>
                        </div>
                    </div>
                    <h3 class="stat-number mb-1">{{ $stats['total_devices'] }}</h3>
                    <p class="stat-label text-muted mb-0">إجمالي الأجهزة</p>
                    <div class="mt-3">
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>
                            {{ $stats['active_devices'] }} جهاز نشط
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="fas fa-tools fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger">-2%</span>
                        </div>
                    </div>
                    <h3 class="stat-number mb-1">{{ $stats['open_breakdowns'] }}</h3>
                    <p class="stat-label text-muted mb-0">أعطال قيد المعالجة</p>
                    <div class="mt-3">
                        <small class="text-danger">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            تحتاج متابعة عاجلة
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">+8%</span>
                        </div>
                    </div>
                    <h3 class="stat-number mb-1">{{ $stats['pending_pm_plans'] }}</h3>
                    <p class="stat-label text-muted mb-0">خطط صيانة</p>
                    <div class="mt-3">
                        <small class="text-success">
                            <i class="fas fa-clock me-1"></i>
                            {{ round(($stats['pending_pm_plans'] / $stats['total_devices']) * 100) }}% من الأجهزة
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="stat-icon bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success">+5%</span>
                        </div>
                    </div>
                    <h3 class="stat-number mb-1">{{ $stats['under_warranty_devices'] }}</h3>
                    <p class="stat-label text-muted mb-0">أجهزة تحت الضمان</p>
                    <div class="mt-3">
                        <small class="text-info">
                            <i class="fas fa-check-circle me-1"></i>
                            ضمان فعال
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Recent Devices -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>أحدث الأجهزة المضافة</h5>
                        <a href="{{ route('devices.index') }}" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الجهاز</th>
                                    <th>الرقم التسلسلي</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_devices as $device)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-microscope text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <strong>{{ $device->display_name ?? $device->name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td><code>{{ $device->serial_number }}</code></td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $device->device_type }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $device->status == 'active' ? 'success' : ($device->status == 'maintenance' ? 'warning' : 'secondary') }}">
                                                {{ $device->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('devices.show', $device->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">لا توجد أجهزة مضافة حديثاً</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>توزيع الأجهزة حسب النوع</h5>
                </div>
                <div class="card-body">
                    <canvas id="devicesByTypeChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Calibration Alerts -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-exclamation me-2"></i>أجهزة تحتاج معايرة</h5>
                        <span class="badge bg-warning">{{ $devices_needing_calibration->count() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($devices_needing_calibration as $device)
                        <div class="alert alert-light border mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $device->display_name ?? $device->name }}</h6>
                                    <small class="text-muted">{{ $device->serial_number }}</small>
                                </div>
                                <span class="badge bg-{{ $device->next_calibration_date->isPast() ? 'danger' : 'warning' }}">
                                    {{ $device->next_calibration_date->format('Y-m-d') }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <small class="text-{{ $device->next_calibration_date->isPast() ? 'danger' : 'warning' }}">
                                    @if($device->next_calibration_date->isPast())
                                        <i class="fas fa-exclamation-triangle me-1"></i>تأخر {{ $device->next_calibration_date->diffInDays() }} يوم
                                    @else
                                        <i class="fas fa-clock me-1"></i>متبقي {{ $device->next_calibration_date->diffInDays() }} يوم
                                    @endif
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                            <p class="text-muted mb-0">جميع الأجهزة محدثة المعايرة</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>إجراءات سريعة</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('devices.create') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-plus fa-2x mb-2"></i>
                                <span>إضافة جهاز</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('breakdowns.create') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                <span>تقرير عطل</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('pm.plans.create') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                                <span>جدولة صيانة</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.spare-parts') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <span>عرض تقارير</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>النشاطات الأخيرة</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>تم إضافة جهاز جديد</h6>
                                <p class="text-muted mb-0">جهاز أشعة سينية تم تسجيله في النظام</p>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>منذ ٢ ساعة</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>تم إكمال صيانة وقائية</h6>
                                <p class="text-muted mb-0">جهاز التصوير المقطعي - تقرير PM #452</p>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>منذ ٤ ساعات</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6>تم الإبلاغ عن عطل جديد</h6>
                                <p class="text-muted mb-0">جهاز التنفس الصناعي - عطل في الضغط</p>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>منذ ٦ ساعات</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            border-radius: 12px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .timeline-content {
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Chart for Device Types
        const typeCtx = document.getElementById('devicesByTypeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($devices_by_type->toArray())) !!},
                datasets: [{
                    label: 'عدد الأجهزة',
                    data: {!! json_encode(array_values($devices_by_type->toArray())) !!},
                    backgroundColor: 'rgba(42, 76, 125, 0.7)',
                    borderColor: 'rgba(42, 76, 125, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush