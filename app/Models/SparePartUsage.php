<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartUsage extends Model
{
    protected $table = 'spare_part_usages';

    protected $fillable = [
        'spare_part_id',
        'breakdown_id',
        'pm_record_id',
        'quantity',
        'type',          // issue | return
        'performed_by',
    ];

    /* ================= RELATIONS ================= */

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function breakdown()
    {
        return $this->belongsTo(Breakdown::class);
    }

    public function pmRecord()
    {
        return $this->belongsTo(PmRecord::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
