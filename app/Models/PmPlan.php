<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;


class PmPlan extends Model
{
    protected $fillable = [
        'device_id',
        'interval_months',
        'next_pm_date',
        'notes',
        'assigned_to',
        'status',
    ];

    protected $casts = [
        'next_pm_date' => 'date',
    ];
    // الجهاز
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // الفني المسؤول
    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // سجلات الصيانة
    public function records()
    {
        return $this->hasMany(PmRecord::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'model');
    }


    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'new', 'pending' => [
                'label' => 'New',
                'class' => 'bg-secondary',
            ],
            'assigned' => [
                'label' => 'Assigned',
                'class' => 'bg-info',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'class' => 'bg-primary',
            ],
            'done', 'completed' => [
                'label' => 'Completed',
                'class' => 'bg-success',
            ],
            default => [
                'label' => ucfirst($this->status),
                'class' => 'bg-dark',
            ],
        };
    }


    public function getTimingBadgeAttribute(): ?array
    {
        if (!$this->next_pm_date || $this->status === 'completed') {
            return null;
        }

        $today = Carbon::today();

        if ($this->next_pm_date->lt($today)) {
            return [
                'label' => 'Overdue',
                'class' => 'bg-danger',
            ];
        }

        if ($this->next_pm_date->lte($today->copy()->addDays(30))) {
            return [
                'label' => 'Due Soon',
                'class' => 'bg-warning text-dark',
            ];
        }

        return [
            'label' => 'On Track',
            'class' => 'bg-success-subtle text-success',
        ];
    }




}
