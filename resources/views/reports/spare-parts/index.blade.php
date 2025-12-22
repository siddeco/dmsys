@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 text-primary">تقارير قطع الغيار</h1>
                <p class="text-muted">عرض وتحليل مخزون قطع الغيار</p>
            </div>
        </div>

        <!-- بطاقات الإحصائيات -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-primary border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">إجمالي القطع</h6>
                        <h3 class="mb-0">{{ $stats['total_parts'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-warning border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">قطع منخفضة</h6>
                        <h3 class="mb-0">{{ $stats['low_stock_parts'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-danger border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">قطع نفذت</h6>
                        <h3 class="mb-0">{{ $stats['out_of_stock_parts'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-success border-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-1">قيمة المخزون</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_value'], 2) }} ر.س</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- الفلاتر -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">حالة المخزون</label>
                        <select name="stock_status" class="form-select">
                            <option value="">الكل</option>
                            <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>طبيعي</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>نفذ</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">الجهاز</label>
                        <select name="device_id" class="form-select">
                            <option value="">الكل</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fas fa-filter"></i> تصفية
                        </button>
                    </div>

                    <div class="col-md-3 text-end">
                        <label class="form-label">&nbsp;</label>
                        <div class="btn-group d-block">
                            <a href="{{ route('reports.spare-parts.consumption.export.excel') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                            <a href="{{ route('reports.spare-parts.consumption.export.pdf') }}" class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- جدول قطع الغيار -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>رقم القطعة</th>
                                <th>الجهاز</th>
                                <th>المخزون</th>
                                <th>حد التنبيه</th>
                                <th>الدخول</th>
                                <th>الخروج</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parts as $part)
                                <tr>
                                    <td>{{ $part->name }}</td>
                                    <td>{{ $part->part_number ?? '-' }}</td>
                                    <td>{{ $part->device?->name ?? 'عام' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $part->quantity == 0 ? 'danger' : ($part->quantity <= $part->alert_threshold ? 'warning' : 'success') }}">
                                            {{ $part->quantity }}
                                        </span>
                                    </td>
                                    <td>{{ $part->alert_threshold }}</td>
                                    <td>{{ $part->total_in }}</td>
                                    <td>{{ $part->total_out }}</td>
                                    <td>
                                        @if($part->quantity == 0)
                                            <span class="badge bg-danger">نفذ</span>
                                        @elseif($part->quantity <= $part->alert_threshold)
                                            <span class="badge bg-warning">منخفض</span>
                                        @else
                                            <span class="badge bg-success">طبيعي</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">لا توجد قطع غيار</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الترقيم -->
                <div class="mt-3">
                    {{ $parts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection