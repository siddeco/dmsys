<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PmRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pm_plan_id',
        'device_id',
        'performed_at',
        'engineer_name',
        'status',
        'report',
        'report_file',
        'scan_image',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'performed_at' => 'date',
    ];

    /**
     * علاقة مع خطة الصيانة
     */
    public function plan()
    {
        return $this->belongsTo(PmPlan::class, 'pm_plan_id');
    }

    /**
     * علاقة مع الجهاز
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Accessor: formatted date
     */
    public function getPerformedAtFormattedAttribute()
    {
        return $this->performed_at
            ? Carbon::parse($this->performed_at)->format('Y-m-d')
            : null;
    }

    /**
     * Accessor: readable status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'ok'           => 'OK - Working Fine',
            'needs_parts'  => 'Needs Spare Parts',
            'critical'     => 'Critical - Requires Intervention',
            default        => 'Unknown',
        };
    }
}
