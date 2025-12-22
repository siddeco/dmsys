<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breakdown extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_id',
        'project_id',
        'reported_by',
        'assigned_to',
        'pm_record_id',
        'ticket_number',
        'description',
        'priority',
        'status',
        'reported_at',
        'assigned_at',
        'started_at',
        'resolved_at',
        'closed_at',
        'resolution',
        'engineer_report',
        'downtime_days',
        'labor_cost',
        'parts_cost',
        'total_cost',
        'satisfaction_level',
        'customer_feedback'
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'labor_cost' => 'decimal:2',
        'parts_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'downtime_days' => 'integer',
    ];

    /**
     * العلاقات
     */

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function pmRecord()
    {
        return $this->belongsTo(PmRecord::class, 'pm_record_id');
    }

    public function sparePartUsages()
    {
        return $this->hasMany(SparePartUsage::class);
    }

    /**
     * النطاقات (Scopes)
     */

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'assigned']);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('reported_at', '>=', now()->subDays($days));
    }

    /**
     * التوابع المساعدة
     */

    public function isOpen()
    {
        return in_array($this->status, ['open', 'assigned', 'in_progress']);
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public function getTotalCostAttribute()
    {
        return ($this->labor_cost ?? 0) + ($this->parts_cost ?? 0);
    }

    public function getDowntimeHoursAttribute()
    {
        if ($this->started_at && $this->resolved_at) {
            return $this->started_at->diffInHours($this->resolved_at);
        }
        return 0;
    }

    /**
     * الأحداث
     */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($breakdown) {
            if (!$breakdown->ticket_number) {
                $breakdown->ticket_number = 'BD-' . date('Y') . '-' . str_pad(Breakdown::count() + 1, 3, '0', STR_PAD_LEFT);
            }
        });

        static::updating(function ($breakdown) {
            if ($breakdown->isDirty('status')) {
                if ($breakdown->status === 'assigned' && !$breakdown->assigned_at) {
                    $breakdown->assigned_at = now();
                } elseif ($breakdown->status === 'in_progress' && !$breakdown->started_at) {
                    $breakdown->started_at = now();
                } elseif ($breakdown->status === 'resolved' && !$breakdown->resolved_at) {
                    $breakdown->resolved_at = now();
                } elseif ($breakdown->status === 'closed' && !$breakdown->closed_at) {
                    $breakdown->closed_at = now();
                }
            }
        });
    }
}