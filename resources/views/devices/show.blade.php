@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">الأجهزة</a></li>
                        <li class="breadcrumb-item active">{{ $device->display_name }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-microscope text-primary me-2"></i>
                        {{ $device->display_name }}
                    </h1>
                    <div class="btn-group">
                        <a href="{{ route('devices.edit', $device->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        @if($device->is_archived)
                            <form action="{{ route('devices.restore', $device->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-undo"></i> استعادة
                                </button>
                            </form>
                        @else
                            <form action="{{ route('devices.archive', $device->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-archive"></i> أرشفة
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Info -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات أساسية</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">الرقم التسلسلي:</th>
                                <td>{{ $device->serial_number }}</td>
                            </tr>
                            <tr>
                                <th>الموديل:</th>
                                <td>{{ $device->model ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>الشركة المصنعة:</th>
                                <td>{{ $device->manufacturer ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>نوع الجهاز:</th>
                                <td><span class="badge bg-info">{{ $device->device_type }}</span></td>
                            </tr>
                            <tr>
                                <th>الفئة:</th>
                                <td>{{ $device->category ?? 'غير محدد' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>الموقع</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">المشروع:</th>
                                <td>
                                    @if($device->project)
                                        <a href="{{ route('projects.show', $device->project->id) }}">
                                            {{ $device->project->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">لا يوجد</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>المكان:</th>
                                <td>{{ $device->location ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>رقم الغرفة:</th>
                                <td>{{ $device->room_number ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>المبنى:</th>
                                <td>{{ $device->building ?? 'غير محدد' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header {{ $device->status == 'active' ? 'bg-success' : 'bg-warning' }} text-white">
                        <h6 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>الحالة</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">الحالة:</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $device->status == 'active' ? 'success' : ($device->status == 'maintenance' ? 'warning' : 'danger') }}">
                                        {{ $device->status }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>الحالة الفنية:</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $device->condition == 'excellent' ? 'success' : ($device->condition == 'good' ? 'info' : ($device->condition == 'fair' ? 'warning' : 'danger')) }}">
                                        {{ $device->condition ?? 'غير محدد' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>تاريخ التركيب:</th>
                                <td>{{ $device->installation_date?->format('Y-m-d') ?? 'غير محدد' }}</td>
                            </tr>
                            <tr>
                                <th>تاريخ انتهاء الضمان:</th>
                                <td>
                                    @if($device->warranty_expiry)
                                        <span class="badge bg-{{ $device->warranty_expiry > now() ? 'success' : 'danger' }}">
                                            {{ $device->warranty_expiry->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">غير محدد</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="deviceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pm-tab" data-bs-toggle="tab" data-bs-target="#pm" type="button">
                            <i class="fas fa-calendar-check me-1"></i>سجلات الصيانة
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="breakdowns-tab" data-bs-toggle="tab" data-bs-target="#breakdowns"
                            type="button">
                            <i class="fas fa-tools me-1"></i>الأعطال
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="calibrations-tab" data-bs-toggle="tab" data-bs-target="#calibrations"
                            type="button">
                            <i class="fas fa-ruler me-1"></i>المعايرة
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-3 border border-top-0" id="deviceTabsContent">
                    <!-- PM Records Tab -->
                    <div class="tab-pane fade show active" id="pm" role="tabpanel">
                        @if($device->pmRecords->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>الفني</th>
                                            <th>النتيجة</th>
                                            <th>التقرير</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($device->pmRecords as $record)
                                            <tr>
                                                <td>{{ $record->performed_at->format('Y-m-d') }}</td>
                                                <td>{{ $record->engineer_name }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $record->status == 'ok' ? 'success' : ($record->status == 'needs_parts' ? 'warning' : 'danger') }}">
                                                        {{ $record->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($record->report_file)
                                                        <a href="{{ Storage::url($record->report_file) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file-pdf"></i> عرض التقرير
                                                        </a>
                                                    @else
                                                        <span class="text-muted">لا يوجد</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                لا توجد سجلات صيانة وقائية لهذا الجهاز.
                            </div>
                        @endif
                    </div>

                    <!-- Breakdowns Tab -->
                    <div class="tab-pane fade" id="breakdowns" role="tabpanel">
                        @if($device->breakdowns->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>العنوان</th>
                                            <th>الحالة</th>
                                            <th>وقت التوقف</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($device->breakdowns as $breakdown)
                                            <tr>
                                                <td>{{ $breakdown->reported_at->format('Y-m-d') }}</td>
                                                <td>{{ $breakdown->title }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $breakdown->status == 'closed' ? 'success' : ($breakdown->status == 'in_progress' ? 'warning' : 'danger') }}">
                                                        {{ $breakdown->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $breakdown->downtime_hours ?? 0 }} ساعة</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                لا توجد أعطال مسجلة لهذا الجهاز.
                            </div>
                        @endif
                    </div>

                    <!-- Calibrations Tab -->
                    <div class="tab-pane fade" id="calibrations" role="tabpanel">
                        @if($device->calibrations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>التاريخ</th>
                                            <th>نوع المعايرة</th>
                                            <th>النتيجة</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($device->calibrations as $calibration)
                                            <tr>
                                                <td>{{ $calibration->calibration_date->format('Y-m-d') }}</td>
                                                <td>{{ $calibration->calibration_type }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $calibration->result == 'passed' ? 'success' : 'danger' }}">
                                                        {{ $calibration->result }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($calibration->notes, 50) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                لا توجد سجلات معايرة لهذا الجهاز.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // تفعيل تبويبات Bootstrap
        var triggerTabList = [].slice.call(document.querySelectorAll('#deviceTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)

            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    </script>
@endsection