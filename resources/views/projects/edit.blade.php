@extends('layouts.app')

@section('title', 'تعديل مشروع')

@section('content')

@can('manage projects')

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-primary">
                <i class="fas fa-edit me-2"></i>
                {{ __('تعديل مشروع: ') }} <span class="text-dark">{{ $project->name }}</span>
            </h4>
            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-right me-2"></i>
                {{ __('رجوع إلى التفاصيل') }}
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        <form action="{{ route('projects.update', $project->id) }}" method="POST" id="editProjectForm">
            @csrf
            @method('PUT')

            <!-- توجيه سريع -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-info border-0 bg-light-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4 text-info"></i>
                            <div>
                                <h6 class="mb-1">تعديل بيانات المشروع</h6>
                                <p class="mb-0">قم بتحديث بيانات المشروع، الحقول المطلوبة <span class="text-danger">*</span></p>
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
                        <!-- اسم المشروع -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    {{ __('اسم المشروع') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $project->name) }}" 
                                       placeholder="أدخل اسم المشروع" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- رمز المشروع -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('رمز المشروع') }}</label>
                                <input type="text" name="code" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code', $project->code) }}" 
                                       placeholder="أدخل رمز المشروع">
                                <small class="text-muted">مثال: PROJ-2024-001</small>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الوصف -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('وصف المشروع') }}</label>
                                <textarea name="description" 
                                          class="form-control @error('description') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="أدخل وصفاً مفصلاً للمشروع...">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الجزء ٢: العميل -->
            <div class="section-card mb-4">
                <div class="section-header bg-light-primary rounded-top p-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-users me-2"></i>
                        العميل
                    </h5>
                </div>
                <div class="section-body p-4">
                    <div class="row g-3">
                        <!-- العميل (من النظام) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('العميل') }}</label>
                                <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                    <option value="">-- اختر عميل من النظام --</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" 
                                            {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- أو اسم العميل يدوياً -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('اسم العميل (يدوياً)') }}</label>
                                <input type="text" name="client_name" 
                                       class="form-control @error('client_name') is-invalid @enderror" 
                                       value="{{ old('client_name', $project->client_name) }}" 
                                       placeholder="أدخل اسم العميل يدوياً">
                                <small class="text-muted">في حالة عدم وجود العميل في النظام</small>
                                @error('client_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- نوع العميل -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('نوع العميل') }}</label>
                                <select name="client_type" class="form-select @error('client_type') is-invalid @enderror">
                                    <option value="">-- اختر نوع العميل --</option>
                                    <option value="hospital" {{ old('client_type', $project->client_type) == 'hospital' ? 'selected' : '' }}>مستشفى</option>
                                    <option value="clinic" {{ old('client_type', $project->client_type) == 'clinic' ? 'selected' : '' }}>عيادة</option>
                                    <option value="laboratory" {{ old('client_type', $project->client_type) == 'laboratory' ? 'selected' : '' }}>مختبر</option>
                                    <option value="pharmacy" {{ old('client_type', $project->client_type) == 'pharmacy' ? 'selected' : '' }}>صيدلية</option>
                                    <option value="government" {{ old('client_type', $project->client_type) == 'government' ? 'selected' : '' }}>حكومي</option>
                                    <option value="company" {{ old('client_type', $project->client_type) == 'company' ? 'selected' : '' }}>شركة</option>
                                    <option value="other" {{ old('client_type', $project->client_type) == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('client_type')
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
                        <!-- المدينة -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('المدينة') }}</label>
                                <input type="text" name="city" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       value="{{ old('city', $project->city) }}" 
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
                                    @foreach (['الرياض','مكة المكرمة','المدينة المنورة','القصيم','الشرقية','عسير','تبوك','حائل','الحدود الشمالية','جازان','نجران','الباحة','الجوف'] as $region)
                                        <option value="{{ $region }}" 
                                            {{ old('region', $project->region) == $region ? 'selected' : '' }}>
                                            {{ $region }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('region')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- العنوان -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('العنوان التفصيلي') }}</label>
                                <textarea name="address" 
                                          class="form-control @error('address') is-invalid @enderror" 
                                          rows="2" 
                                          placeholder="أدخل العنوان التفصيلي للمشروع...">{{ old('address', $project->address) }}</textarea>
                                @error('address')
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
                        <!-- تاريخ البدء -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('تاريخ البدء') }}</label>
                                <input type="date" name="start_date" 
                                       class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- تاريخ الانتهاء المتوقع -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('تاريخ الانتهاء المتوقع') }}</label>
                                <input type="date" name="end_date" 
                                       class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- تاريخ الانتهاء الفعلي -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('تاريخ الانتهاء الفعلي') }}</label>
                                <input type="date" name="actual_end_date" 
                                       class="form-control @error('actual_end_date') is-invalid @enderror" 
                                       value="{{ old('actual_end_date', $project->actual_end_date ? $project->actual_end_date->format('Y-m-d') : '') }}">
                                @error('actual_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الجزء ٥: الإدارة -->
            <div class="section-card mb-4">
                <div class="section-header bg-light-primary rounded-top p-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-clipboard-check me-2"></i>
                        الإدارة
                    </h5>
                </div>
                <div class="section-body p-4">
                    <div class="row g-3">
                        <!-- مدير المشروع -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('مدير المشروع') }}</label>
                                <select name="project_manager_id" class="form-select @error('project_manager_id') is-invalid @enderror">
                                    <option value="">-- اختر مدير المشروع --</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}" 
                                            {{ old('project_manager_id', $project->project_manager_id) == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }} ({{ $manager->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_manager_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    {{ __('الحالة') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach(['active','completed','on_hold','cancelled'] as $stat)
                                        <option value="{{ $stat }}" 
                                            {{ old('status', $project->status) == $stat ? 'selected' : '' }}>
                                            @php
                                                $statusNames = [
                                                    'active' => 'نشط',
                                                    'completed' => 'مكتمل',
                                                    'on_hold' => 'متوقف',
                                                    'cancelled' => 'ملغي'
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

                        <!-- الأولوية -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">
                                    {{ __('الأولوية') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                    @foreach(['low','medium','high'] as $pri)
                                        <option value="{{ $pri }}" 
                                            {{ old('priority', $project->priority) == $pri ? 'selected' : '' }}>
                                            @php
                                                $priorityNames = [
                                                    'low' => 'منخفضة',
                                                    'medium' => 'متوسطة',
                                                    'high' => 'عالية'
                                                ];
                                            @endphp
                                            {{ $priorityNames[$pri] ?? $pri }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الجزء ٦: المالية والعقد -->
            <div class="section-card mb-4">
                <div class="section-header bg-light-primary rounded-top p-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-file-contract me-2"></i>
                        المالية والعقد
                    </h5>
                </div>
                <div class="section-body p-4">
                    <div class="row g-3">
                        <!-- الميزانية -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('الميزانية') }}</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="budget" 
                                           class="form-control @error('budget') is-invalid @enderror" 
                                           value="{{ old('budget', $project->budget) }}" 
                                           placeholder="0.00">
                                    <span class="input-group-text">ر.س</span>
                                </div>
                                @error('budget')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- التكلفة الفعلية -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('التكلفة الفعلية') }}</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="actual_cost" 
                                           class="form-control @error('actual_cost') is-invalid @enderror" 
                                           value="{{ old('actual_cost', $project->actual_cost) }}" 
                                           placeholder="0.00">
                                    <span class="input-group-text">ر.س</span>
                                </div>
                                @error('actual_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- رقم العقد -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('رقم العقد') }}</label>
                                <input type="text" name="contract_number" 
                                       class="form-control @error('contract_number') is-invalid @enderror" 
                                       value="{{ old('contract_number', $project->contract_number) }}" 
                                       placeholder="رقم العقد">
                                @error('contract_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- قيمة العقد -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('قيمة العقد') }}</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="contract_value" 
                                           class="form-control @error('contract_value') is-invalid @enderror" 
                                           value="{{ old('contract_value', $project->contract_value) }}" 
                                           placeholder="0.00">
                                    <span class="input-group-text">ر.س</span>
                                </div>
                                @error('contract_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- فترة الضمان -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('فترة الضمان') }}</label>
                                <div class="input-group">
                                    <input type="number" name="warranty_period" 
                                           class="form-control @error('warranty_period') is-invalid @enderror" 
                                           value="{{ old('warranty_period', $project->warranty_period) }}" 
                                           placeholder="12">
                                    <span class="input-group-text">شهر</span>
                                </div>
                                <small class="text-muted">عدد أشهر فترة الضمان بعد الانتهاء</small>
                                @error('warranty_period')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الجزء ٧: ملاحظات -->
            <div class="section-card mb-4">
                <div class="section-header bg-light-primary rounded-top p-3">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-sticky-note me-2"></i>
                        ملاحظات
                    </h5>
                </div>
                <div class="section-body p-4">
                    <div class="row g-3">
                        <!-- ملاحظات -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-2">{{ __('ملاحظات إضافية') }}</label>
                                <textarea name="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          rows="4" 
                                          placeholder="أي ملاحظات إضافية حول المشروع...">{{ old('notes', $project->notes) }}</textarea>
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
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-outline-danger me-2">
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
            <p class="mb-0">{{ __('ليس لديك الصلاحيات اللازمة لتعديل المشاريع.') }}</p>
        </div>
    </div>
</div>
@endcan

@endsection

@push('styles')
<style>
    /* نفس الأنماط من صفحة إضافة المشروع */
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
        // حساب نسبة التقدم عند تغيير التواريخ
        const startDate = document.querySelector('input[name="start_date"]');
        const endDate = document.querySelector('input[name="end_date"]');
        
        function calculateProgress() {
            if (startDate && startDate.value && endDate && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                const now = new Date();
                
                const totalDays = (end - start) / (1000 * 60 * 60 * 24);
                const elapsedDays = (now - start) / (1000 * 60 * 60 * 24);
                
                if (totalDays > 0) {
                    const percentage = Math.min(100, Math.max(0, (elapsedDays / totalDays) * 100));
                    console.log('نسبة التقدّم:', percentage.toFixed(2) + '%');
                }
            }
        }
        
        if (startDate) startDate.addEventListener('change', calculateProgress);
        if (endDate) endDate.addEventListener('change', calculateProgress);
        
        // حساب تكلفة الباقي
        const budget = document.querySelector('input[name="budget"]');
        const actualCost = document.querySelector('input[name="actual_cost"]');
        
        function calculateRemainingBudget() {
            if (budget && budget.value && actualCost && actualCost.value) {
                const budgetVal = parseFloat(budget.value);
                const actualVal = parseFloat(actualCost.value);
                
                if (budgetVal > 0) {
                    const remaining = budgetVal - actualVal;
                    const percentage = (actualVal / budgetVal) * 100;
                    
                    console.log('الميزانية المتبقية:', remaining.toFixed(2) + ' ر.س');
                    console.log('نسبة الاستهلاك:', percentage.toFixed(2) + '%');
                }
            }
        }
        
        if (budget) budget.addEventListener('change', calculateRemainingBudget);
        if (actualCost) actualCost.addEventListener('change', calculateRemainingBudget);
        
        // التحقق من الحقول المطلوبة
        const form = document.getElementById('editProjectForm');
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