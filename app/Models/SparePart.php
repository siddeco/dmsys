<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'part_number',
        'manufacturer',
        'device_id',
        'quantity',
        'alert_threshold',
        'description',
    ];

    // القطعة قد تكون مرتبطة بجهاز
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // هل وصلت للحد الأدنى؟
    public function isLow()
    {
        return $this->quantity <= $this->alert_threshold;
    }
}
