<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class PmPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'interval_months',
        'next_pm_date',
        'notes',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'next_pm_date' => 'date',
        'interval_months' => 'integer',
    ];

    /**
     * علاقة مع الجهاز
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * علاقة مع سجلات الصيانة
     */
    public function records()
    {
        return $this->hasMany(PmRecord::class)
                    ->orderBy('performed_at', 'desc'); // ترتيب أحدث سجل أولاً
    }

    /**
     * الحصول على تاريخ PM القادم بتنسيق مناسب
     */
    public function getNextPmFormattedAttribute()
    {
        return $this->next_pm_date
            ? Carbon::parse($this->next_pm_date)->format('Y-m-d')
            : null;
    }

    /**
     * حساب عدد سجلات الصيانة مباشرة
     */
    public function getRecordsCountAttribute()
    {
        return $this->records()->count();
    }
}
