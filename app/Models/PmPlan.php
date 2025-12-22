<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PmPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_id',
        'created_by',
        'title',
        'description',
        'frequency',
        'custom_days',
        'start_date',
        'next_due_date',
        'last_performed_date',
        'tasks',
        'instructions',
        'is_active',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'next_due_date' => 'date',
        'last_performed_date' => 'date',
        'tasks' => 'array',
        'is_active' => 'boolean',
        'custom_days' => 'integer',
    ];

    /**
     * العلاقات
     */

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pmRecords()
    {
        return $this->hasMany(PmRecord::class);
    }

    public function project()
    {
        return $this->through('device')->has('project');
    }

    /**
     * النطاقات
     */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDue($query)
    {
        return $query->where('next_due_date', '<=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('next_due_date', '<', now()->subDays(7));
    }

    public function scopeByFrequency($query, $frequency)
    {
        return $query->where('frequency', $frequency);
    }

    public function scopeByDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    /**
     * التوابع المساعدة
     */

    public function isDue()
    {
        return $this->next_due_date <= now();
    }

    public function isOverdue()
    {
        return $this->next_due_date < now()->subDays(7);
    }

    public function calculateNextDueDate()
    {
        $lastDate = $this->last_performed_date ?: $this->start_date;

        switch ($this->frequency) {
            case 'daily':
                return $lastDate->addDay();
            case 'weekly':
                return $lastDate->addWeek();
            case 'monthly':
                return $lastDate->addMonth();
            case 'quarterly':
                return $lastDate->addMonths(3);
            case 'half_yearly':
                return $lastDate->addMonths(6);
            case 'yearly':
                return $lastDate->addYear();
            case 'custom':
                return $lastDate->addDays($this->custom_days);
            default:
                return $lastDate->addMonth();
        }
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->isOverdue()) {
            return 'overdue';
        }

        if ($this->isDue()) {
            return 'due';
        }

        return 'pending';
    }

    /**
     * الأحداث
     */

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($plan) {
            if ($plan->isDirty('last_performed_date')) {
                $plan->next_due_date = $plan->calculateNextDueDate();
            }
        });
    }
}