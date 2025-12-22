@extends('layouts.app')

@section('content')
    @can('manage devices')

        <div class="container-fluid">
            <!-- ترويسة الصفحة -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1 text-dark">
                        <i class="fas fa-archive me-2 text-warning"></i>
                        الأجهزة المؤرشفة
                    </h2>
                    <p class="text-muted mb-0">إدارة الأجهزة المؤرشفة واستعادتها عند الحاجة</p>
                </div>

                <a href="{{ route('devices.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-right me-2"></i>
                    رجوع إلى الأجهزة النشطة
                </a>
            </div>

            <!-- فلترة البحث -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-filter me-2"></i>
                        فلترة الأجهزة المؤرشفة
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('devices.archived') }}">
                        <div class="row g-3">
                            <!-- البحث -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2">بحث</label>
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control"
                                        placeholder="ابحث بالاسم، الرقم التسلسلي، الموديل" value="{{ request('q') }}">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- المشروع -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2">المشروع</label>
                                <select name="project_id" class="form-select">
                                    <option value="">جميع المشاريع</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- أزرار الإجراء -->
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-flex gap-2 w-100">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-filter me-2"></i>
                                        تطبيق
                                    </button>
                                    <a href="{{ route('devices.archived') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جدول الأجهزة المؤرشفة -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-list me-2"></i>
                        قائمة الأجهزة المؤرشفة
                        <span class="badge bg-warning ms-2">{{ $devices->total() }}</span>
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">#</th>
                                    <th>اسم الجهاز</th>
                                    <th>الرقم التسلسلي</th>
                                    <th>المشروع</th>
                                    <th>الموقع</th>
                                    <th width="150">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($devices as $device)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">#{{ $device->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light-warning p-2 me-3">
                                                    <i class="fas fa-microscope text-warning"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $device->display_name }}</h6>
                                                    <small class="text-muted">{{ $device->model ?? 'لا يوجد موديل' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="bg-light p-1 rounded">{{ $device->serial_number }}</code>
                                        </td>
                                        <td>
                                            @if($device->project)
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $device->project->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">بدون مشروع</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                {{ $device->city ?? 'غير محدد' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- استعادة -->
                                                <form action="{{ route('devices.restore', $device->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-success"
                                                        onclick="return confirm('هل أنت متأكد من استعادة هذا الجهاز؟')"
                                                        title="استعادة الجهاز">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>

                                                <!-- عرض التفاصيل -->
                                                <a href="{{ route('devices.show', $device->id) }}"
                                                    class="btn btn-sm btn-outline-info" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-archive fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">لا توجد أجهزة مؤرشفة</h5>
                                                <p class="text-muted">جميع الأجهزة حالياً نشطة في النظام</p>
                                                <a href="{{ route('devices.index') }}" class="btn btn-primary mt-3">
                                                    <i class="fas fa-arrow-right me-2"></i>
                                                    عرض الأجهزة النشطة
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($devices->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                عرض {{ $devices->firstItem() ?? 0 }} - {{ $devices->lastItem() ?? 0 }} من أصل
                                {{ $devices->total() }}
                            </div>
                            <div>
                                {{ $devices->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    @else
        <div class="alert alert-danger border-0 shadow-sm mt-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-danger fs-4 me-3"></i>
                <div>
                    <h5 class="mb-1">غير مصرح لك</h5>
                    <p class="mb-0">ليس لديك الصلاحيات اللازمة للوصول إلى الأرشيف.</p>
                </div>
            </div>
        </div>
    @endcan
@endsection