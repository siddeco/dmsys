<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SparePart extends Model
{
    protected $fillable = [
        'name',
        'part_number',
        'manufacturer',
        'category',
        'quantity',
        'min_quantity',
        'unit',
        'location',
        'notes',
        'alert_threshold',
    ];

    public function transactions()
    {
        return $this->hasMany(SparePartTransaction::class);
    }

    public function usages()
    {
        return $this->hasMany(SparePartUsage::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function isLow(): bool
    {
        if ($this->alert_threshold === null) {
            return false;
        }

        return $this->quantity <= $this->alert_threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }




}


