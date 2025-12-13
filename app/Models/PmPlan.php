<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

}
