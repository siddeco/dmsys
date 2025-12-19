<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartTransaction extends Model
{
    protected $fillable = [
        'spare_part_id',
        'type',
        'quantity',
        'pm_record_id',
        'breakdown_id',
        'performed_by',
        'notes',
    ];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function pmRecord()
    {
        return $this->belongsTo(PmRecord::class);
    }

    public function breakdown()
    {
        return $this->belongsTo(Breakdown::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}

