<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // المعلومات الأساسية
        'name',
        'code',

        // العميل
        'client_id',
        'client_name',
        'client_type',

        // الوصف
        'description',

        // الموقع
        'city',
        'region',
        'address',

        // التواريخ
        'start_date',
        'end_date',
        'actual_end_date',

        // الإدارة
        'project_manager_id',
        'status',
        'priority',

        // المالية
        'budget',
        'actual_cost',

        // العقد
        'contract_number',
        'contract_value',
        'warranty_period',

        // الحالة
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'contract_value' => 'decimal:2',
        'is_active' => 'boolean',
        'warranty_period' => 'integer',
    ];

    protected $appends = [
        'progress_percentage',
        'days_remaining',
        'is_overdue',
        'display_status',
        'display_priority',
    ];

    /**
     * حدث الإنشاء: إنشاء كود المشروع تلقائياً
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (!$project->code) {
                $project->code = static::generateProjectCode();
            }
        });
    }

    /**
     * إنشاء كود المشروع تلقائياً
     */
    public static function generateProjectCode()
    {
        $prefix = 'PROJ-' . date('Y') . '-';
        $lastProject = static::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastProject) {
            $lastNumber = intval(str_replace($prefix, '', $lastProject->code));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    /**
     * العلاقة مع العميل
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * العلاقة مع مدير المشروع
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    /**
     * العلاقة مع الأجهزة
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * العلاقة مع PM Plans
     */
    public function pmPlans()
    {
        return $this->hasMany(PmPlan::class);
    }

    /**
     * العلاقة مع Breakdowns
     */
    public function breakdowns()
    {
        return $this->hasMany(Breakdown::class);
    }

    /**
     * الحصول على نسبة التقدم
     */
    public function getProgressPercentageAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());

        if ($totalDays <= 0) {
            return 100;
        }

        $percentage = min(100, max(0, ($elapsedDays / $totalDays) * 100));

        // إذا كان المشروع مكتملاً، النسبة 100%
        if ($this->status === 'completed') {
            return 100;
        }

        return round($percentage, 2);
    }

    /**
     * الحصول على الأيام المتبقية
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->end_date) {
            return null;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    /**
     * التحقق إذا كان المشروع متأخراً
     */
    public function getIsOverdueAttribute()
    {
        if ($this->status === 'completed' || !$this->end_date) {
            return false;
        }

        return now()->greaterThan($this->end_date);
    }

    /**
     * الحصول على الحالة للعرض
     */
    public function getDisplayStatusAttribute()
    {
        $statuses = [
            'active' => ['text' => 'نشط', 'class' => 'success'],
            'completed' => ['text' => 'مكتمل', 'class' => 'primary'],
            'on_hold' => ['text' => 'متوقف', 'class' => 'warning'],
            'cancelled' => ['text' => 'ملغي', 'class' => 'danger'],
        ];

        return $statuses[$this->status] ?? ['text' => 'غير محدد', 'class' => 'secondary'];
    }

    /**
     * الحصول على الأولوية للعرض
     */
    public function getDisplayPriorityAttribute()
    {
        $priorities = [
            'low' => ['text' => 'منخفضة', 'class' => 'info', 'icon' => 'arrow-down'],
            'medium' => ['text' => 'متوسطة', 'class' => 'warning', 'icon' => 'minus'],
            'high' => ['text' => 'عالية', 'class' => 'danger', 'icon' => 'arrow-up'],
        ];

        return $priorities[$this->priority] ?? ['text' => 'غير محدد', 'class' => 'secondary', 'icon' => 'circle'];
    }

    /**
     * الحصول على نوع العميل للعرض
     */
    public function getDisplayClientTypeAttribute()
    {
        $types = [
            'hospital' => 'مستشفى',
            'clinic' => 'عيادة',
            'laboratory' => 'مختبر',
            'pharmacy' => 'صيدلية',
            'government' => 'حكومي',
            'company' => 'شركة',
            'other' => 'أخرى',
        ];

        return $types[$this->client_type] ?? 'غير محدد';
    }

    /**
     * نطاق للمشاريع النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('status', 'active');
    }

    /**
     * نطاق للمشاريع المكتملة
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * نطاق للمشاريع المتأخرة
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->whereDate('end_date', '<', now());
    }

    /**
     * نطاق للمشاريع حسب المنطقة
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * نطاق للمشاريع حسب الأولوية
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * نطاق للمشاريع حسب نوع العميل
     */
    public function scopeByClientType($query, $clientType)
    {
        return $query->where('client_type', $clientType);
    }

    /**
     * نطاق للمشاريع القريبة من الانتهاء (أقل من 30 يوم)
     */
    public function scopeEndingSoon($query)
    {
        return $query->where('status', 'active')
            ->whereDate('end_date', '<=', now()->addDays(30))
            ->whereDate('end_date', '>=', now());
    }

    /**
     * إحصائيات المشروع
     */
    public function getStatsAttribute()
    {
        return [
            'total_devices' => $this->devices()->count(),
            'active_devices' => $this->devices()->active()->count(),
            'maintenance_devices' => $this->devices()->where('status', 'under_maintenance')->count(),
            'open_breakdowns' => $this->breakdowns()->whereIn('status', ['open', 'assigned'])->count(),
            'total_budget_used' => $this->actual_cost ?? 0,
            'budget_remaining' => max(0, ($this->budget ?? 0) - ($this->actual_cost ?? 0)),
        ];
    }

    /**
     * تحقق إذا كان المشروع ضمن الميزانية
     */
    public function isWithinBudget()
    {
        if (!$this->budget || !$this->actual_cost) {
            return true;
        }

        return $this->actual_cost <= $this->budget;
    }

    /**
     * نسبة استخدام الميزانية
     */
    public function getBudgetUsagePercentage()
    {
        if (!$this->budget || $this->budget == 0) {
            return 0;
        }

        return min(100, ($this->actual_cost / $this->budget) * 100);
    }

    /**
     * التحقق إذا كان المشروع تحت الضمان
     */
    public function isUnderWarranty()
    {
        if (!$this->actual_end_date || !$this->warranty_period) {
            return false;
        }

        $warrantyEndDate = $this->actual_end_date->addMonths($this->warranty_period);
        return now()->lessThanOrEqualTo($warrantyEndDate);
    }

    /**
     * الأيام المتبقية في الضمان
     */
    public function getWarrantyDaysRemaining()
    {
        if (!$this->isUnderWarranty()) {
            return 0;
        }

        $warrantyEndDate = $this->actual_end_date->addMonths($this->warranty_period);
        return now()->diffInDays($warrantyEndDate, false);
    }

    /**
     * معلومات المشروع للعرض
     */
    public function getSummaryAttribute()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'client' => $this->client_name ?? $this->client?->name,
            'status' => $this->display_status['text'],
            'priority' => $this->display_priority['text'],
            'location' => $this->city . ($this->region ? ' - ' . $this->region : ''),
            'progress' => $this->progress_percentage,
            'days_remaining' => $this->days_remaining,
            'is_overdue' => $this->is_overdue,
        ];
    }

    /**
     * الحصول على المدة الإجمالية للمشروع بالأيام
     */
    public function getDurationDaysAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * الحصول على المدة المنقضية بالأيام
     */
    public function getElapsedDaysAttribute()
    {
        if (!$this->start_date) {
            return null;
        }

        return $this->start_date->diffInDays(now());
    }
}