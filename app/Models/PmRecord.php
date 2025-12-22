<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PmRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pm_plan_id',
        'device_id',
        'project_id',
        'performed_by',
        'verified_by',
        'record_number',
        'description',
        'scheduled_date',
        'performed_date',
        'started_at',
        'completed_at',
        'status',
        'outcome',
        'measurements',
        'calibration_data',
        'completed_tasks',
        'findings',
        'recommendations',
        'labor_hours',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'customer_signature_path',
        'engineer_signature_path',
        'report_path',
        'notes'
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'performed_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'measurements' => 'array',
        'calibration_data' => 'array',
        'completed_tasks' => 'array',
        'labor_hours' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * العلاقات
     */

    public function pmPlan()
    {
        return $this->belongsTo(PmPlan::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function breakdowns()
    {
        return $this->hasMany(Breakdown::class);
    }

    public function sparePartUsages()
    {
        return $this->hasMany(SparePartUsage::class);
    }

    /**
     * النطاقات
     */

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    public function scopeByTechnician($query, $userId)
    {
        return $query->where('performed_by', $userId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('performed_date', '>=', now()->subDays($days));
    }

    public function scopeByOutcome($query, $outcome)
    {
        return $query->where('outcome', $outcome);
    }

    /**
     * التوابع المساعدة
     */

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isVerified()
    {
        return !is_null($this->verified_by);
    }

    public function getTotalCostAttribute()
    {
        return ($this->labor_cost ?? 0) + ($this->parts_cost ?? 0);
    }

    public function getDurationHoursAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInHours($this->completed_at);
        }
        return null;
    }

    /**
     * الأحداث
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (!$record->record_number) {
                $record->record_number = 'PM-' . date('Y') . '-' . str_pad(PmRecord::count() + 1, 3, '0', STR_PAD_LEFT);
            }

            if (!$record->status) {
                $record->status = 'scheduled';
            }
        });

        static::updating(function ($record) {
            if ($record->isDirty('status') && $record->status === 'completed' && !$record->completed_at) {
                $record->completed_at = now();
            }
        });

        static::created(function ($record) {
            // تحديث آخر تاريخ صيانة للجهاز
            if ($record->device) {
                $record->device->update([
                    'last_calibration_date' => $record->performed_date
                ]);
            }

            // تحديث آخر تاريخ صيانة للخطة
            if ($record->pmPlan) {
                $record->pmPlan->update([
                    'last_performed_date' => $record->performed_date,
                    'next_due_date' => $record->pmPlan->calculateNextDueDate()
                ]);
            }
        });
    }
}