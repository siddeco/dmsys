<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasTranslations, SoftDeletes;

    public $translatable = ['name'];

    protected $fillable = [
        // المعلومات الأساسية
        'name',
        'serial_number',
        'model',
        'manufacturer',
        'device_type',
        'category',

        // الموقع
        'project_id',
        'location',
        'room_number',
        'floor',
        'building',
        'city',
        'region',

        // التواريخ
        'purchase_date',
        'installation_date',
        'warranty_expiry',
        'last_calibration_date',
        'next_calibration_date',

        // الحالة
        'status',
        'condition',
        'is_archived',

        // المواصفات الفنية
        'power_requirements',
        'dimensions',
        'weight',
        'specifications',

        // الإدارة
        'assigned_to',
        'purchase_price',
        'current_value',
        'depreciation_rate',

        // الضمان والصيانة
        'service_provider',
        'service_contract_number',
        'preventive_maintenance_frequency',

        // ملاحظات
        'notes',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'purchase_date' => 'date',
        'installation_date' => 'date',
        'warranty_expiry' => 'date',
        'last_calibration_date' => 'date',
        'next_calibration_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'specifications' => 'array',
    ];

    protected $appends = ['english_name', 'display_name'];

    /**
     * الحصول على الاسم بالإنجليزية
     */
    public function getEnglishNameAttribute()
    {
        $name = $this->name;

        if (is_array($name)) {
            return $name['en'] ?? $name['ar'] ?? $this->serial_number;
        }

        if (is_string($name) && json_decode($name, true)) {
            $decoded = json_decode($name, true);
            if (is_array($decoded)) {
                return $decoded['en'] ?? $decoded['ar'] ?? $this->serial_number;
            }
        }

        return $name ?? $this->serial_number;
    }

    /**
     * الحصول على الاسم للعرض (الإنجليزية دائماً)
     */
    public function getDisplayNameAttribute()
    {
        return $this->english_name;
    }

    /**
     * العلاقة مع المشروع
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * العلاقة مع الفني المسؤول
     */
    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * العلاقة مع PM Plans
     */
    public function pmPlans()
    {
        return $this->hasMany(PmPlan::class);
    }

    /**
     * العلاقة مع PM Records
     */
    public function pmRecords()
    {
        return $this->hasMany(PmRecord::class);
    }

    /**
     * العلاقة مع Breakdowns
     */
    public function breakdowns()
    {
        return $this->hasMany(Breakdown::class);
    }

    /**
     * العلاقة مع قطع الغيار المستخدمة
     */
    public function sparePartUsages()
    {
        return $this->hasMany(SparePartUsage::class);
    }

    /**
     * العلاقة مع سجلات المعايرة
     */
    public function calibrations()
    {
        return $this->hasMany(CalibrationRecord::class);
    }

    /**
     * نطاق للأجهزة النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_archived', false)
            ->where('status', 'active');
    }

    /**
     * نطاق للأجهزة حسب النوع
     */
    public function scopeByType($query, $type)
    {
        return $query->where('device_type', $type);
    }

    /**
     * نطاق للأجهزة حسب الحالة
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * نطاق للأجهزة تحت الضمان
     */
    public function scopeUnderWarranty($query)
    {
        return $query->whereDate('warranty_expiry', '>', now());
    }

    /**
     * نطاق للأجهزة تحتاج معايرة
     */
    public function scopeNeedsCalibration($query)
    {
        return $query->whereDate('next_calibration_date', '<=', now()->addDays(30))
            ->where('status', 'active');
    }

    /**
     * إحصائيات الجهاز
     */
    public function getStatsAttribute()
    {
        return [
            'total_breakdowns' => $this->breakdowns()->count(),
            'open_breakdowns' => $this->breakdowns()->whereIn('status', ['open', 'assigned'])->count(),
            'pm_completed' => $this->pmRecords()->where('status', 'completed')->count(),
            'downtime_days' => $this->breakdowns()->where('status', 'closed')->sum('downtime_days'),
        ];
    }

    /**
     * التحقق إذا كان الجهاز تحت الضمان
     */
    public function isUnderWarranty()
    {
        return $this->warranty_expiry && $this->warranty_expiry > now();
    }

    /**
     * التحقق إذا كان الجهاز يحتاج معايرة قريباً
     */
    public function needsCalibrationSoon()
    {
        return $this->next_calibration_date &&
            $this->next_calibration_date <= now()->addDays(30);
    }
}