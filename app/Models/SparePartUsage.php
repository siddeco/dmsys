<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePartUsage extends Model
{
    use SoftDeletes;

    protected $table = 'spare_part_usages';

    protected $fillable = [
        'spare_part_id',
        'breakdown_id',
        'pm_record_id',
        'device_id',
        'project_id',
        'performed_by',
        'transaction_number',
        'type',
        'quantity',
        'transaction_date',
        'recorded_at',
        'reason',
        'notes',
        'unit_cost',
        'total_cost',
        'from_location',
        'to_location',
        'reference_number',
        'document_path'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'recorded_at' => 'datetime',
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * العلاقات
     */
    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function breakdown()
    {
        return $this->belongsTo(Breakdown::class);
    }

    public function pmRecord()
    {
        return $this->belongsTo(PmRecord::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * النطاقات
     */
    public function scopeIssues($query)
    {
        return $query->where('type', 'issue');
    }

    public function scopeReturns($query)
    {
        return $query->where('type', 'return');
    }

    public function scopeBySparePart($query, $sparePartId)
    {
        return $query->where('spare_part_id', $sparePartId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('transaction_date', '>=', now()->subDays($days));
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * التوابع المساعدة
     */
    public function isIssue(): bool
    {
        return $this->type === 'issue';
    }

    public function isReturn(): bool
    {
        return $this->type === 'return';
    }

    public function getTransactionTypeAttribute()
    {
        return $this->isIssue() ? 'إصدار' : 'إرجاع';
    }

    public function getCostAttribute()
    {
        if ($this->total_cost) {
            return $this->total_cost;
        }

        if ($this->unit_cost) {
            return $this->quantity * $this->unit_cost;
        }

        if ($this->sparePart && $this->sparePart->unit_price) {
            return $this->quantity * $this->sparePart->unit_price;
        }

        return 0;
    }

    /**
     * الأحداث
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($usage) {
            if (!$usage->transaction_number) {
                $usage->transaction_number = 'SPU-' . date('Y') . '-' . str_pad(SparePartUsage::count() + 1, 3, '0', STR_PAD_LEFT);
            }

            if (!$usage->transaction_date) {
                $usage->transaction_date = now();
            }

            if (!$usage->recorded_at) {
                $usage->recorded_at = now();
            }

            // حساب التكلفة إذا لم تكن محددة
            if (!$usage->total_cost && $usage->sparePart && $usage->sparePart->unit_price) {
                $usage->unit_cost = $usage->sparePart->unit_price;
                $usage->total_cost = $usage->quantity * $usage->sparePart->unit_price;
            }
        });
    }
}