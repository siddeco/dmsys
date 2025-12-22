<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalibrationRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_id',
        'project_id',
        'calibrated_by',
        'verified_by',
        'certificate_number',
        'calibration_standard',
        'calibration_date',
        'next_calibration_date',
        'calibrated_at',
        'status',
        'measurements',
        'tolerances',
        'calibration_equipment',
        'equipment_certificate',
        'certificate_path',
        'report_path',
        'calibration_cost',
        'invoice_number',
        'notes',
        'recommendations'
    ];

    protected $casts = [
        'calibration_date' => 'date',
        'next_calibration_date' => 'date',
        'calibrated_at' => 'datetime',
        'measurements' => 'array',
        'tolerances' => 'array',
        'calibration_cost' => 'decimal:2',
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

    public function calibrator()
    {
        return $this->belongsTo(User::class, 'calibrated_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * النطاقات
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeDueForCalibration($query)
    {
        return $query->where('next_calibration_date', '<=', now()->addDays(30));
    }

    public function scopeOverdue($query)
    {
        return $query->where('next_calibration_date', '<', now());
    }

    public function scopeByDevice($query, $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    public function scopeByCalibrator($query, $userId)
    {
        return $query->where('calibrated_by', $userId);
    }

    /**
     * التوابع المساعدة
     */
    public function isPassed()
    {
        return $this->status === 'passed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isDueForCalibration()
    {
        return $this->next_calibration_date <= now()->addDays(30);
    }

    public function isOverdue()
    {
        return $this->next_calibration_date < now();
    }

    public function isVerified()
    {
        return !is_null($this->verified_by);
    }

    public function getDaysUntilNextCalibrationAttribute()
    {
        return now()->diffInDays($this->next_calibration_date, false);
    }

    /**
     * الأحداث
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (!$record->certificate_number) {
                $record->certificate_number = 'CAL-' . date('Y') . '-' . str_pad(CalibrationRecord::count() + 1, 3, '0', STR_PAD_LEFT);
            }

            if (!$record->calibrated_at) {
                $record->calibrated_at = now();
            }
        });

        static::created(function ($record) {
            // تحديث تاريخ آخر معايرة للجهاز
            if ($record->device) {
                $record->device->update([
                    'last_calibration_date' => $record->calibration_date,
                    'next_calibration_date' => $record->next_calibration_date
                ]);
            }
        });
    }
}