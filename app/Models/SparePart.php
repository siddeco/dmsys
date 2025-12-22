<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'part_number',
        'name',
        'description',
        'manufacturer',
        'model_compatibility',
        'category',
        'criticality',
        'quantity',
        'minimum_stock',
        'reorder_quantity',
        'storage_location',
        'bin_number',
        'unit_price',
        'selling_price',
        'purchase_date',
        'expiry_date',
        'batch_number',
        'specifications',
        'dimensions',
        'weight',
        'image_path',
        'datasheet_path',
        'is_active',
        'status'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'minimum_stock' => 'integer',
        'reorder_quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'specifications' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقات
     */
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function usages()
    {
        return $this->hasMany(SparePartUsage::class);
    }

    public function breakdowns()
    {
        return $this->hasManyThrough(Breakdown::class, SparePartUsage::class, 'spare_part_id', 'id', 'id', 'breakdown_id');
    }

    public function pmRecords()
    {
        return $this->hasManyThrough(PmRecord::class, SparePartUsage::class, 'spare_part_id', 'id', 'id', 'pm_record_id');
    }

    /**
     * النطاقات
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity', '<=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByCriticality($query, $criticality)
    {
        return $query->where('criticality', $criticality);
    }

    /**
     * التوابع المساعدة
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    public function needsReorder(): bool
    {
        return $this->quantity <= $this->minimum_stock && $this->reorder_quantity;
    }

    public function getStockStatusAttribute()
    {
        if ($this->isOutOfStock()) {
            return 'out_of_stock';
        }

        if ($this->isLowStock()) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    public function getStockValueAttribute()
    {
        return $this->quantity * ($this->unit_price ?? 0);
    }

    /**
     * إدارة المخزون
     */
    public function issue(int $quantity, $reason = null, $referenceId = null, $type = 'breakdown')
    {
        if ($quantity > $this->quantity) {
            throw new \Exception('الكمية المطلوبة غير متوفرة في المخزون');
        }

        $this->decrement('quantity', $quantity);

        return SparePartUsage::create([
            'spare_part_id' => $this->id,
            'quantity' => $quantity,
            'type' => 'issue',
            'reason' => $reason,
            'transaction_date' => now(),
            'performed_by' => auth()->id(),
            'breakdown_id' => $type === 'breakdown' ? $referenceId : null,
            'pm_record_id' => $type === 'pm' ? $referenceId : null,
        ]);
    }

    public function return(int $quantity, $reason = null, $referenceId = null)
    {
        $this->increment('quantity', $quantity);

        return SparePartUsage::create([
            'spare_part_id' => $this->id,
            'quantity' => $quantity,
            'type' => 'return',
            'reason' => $reason,
            'transaction_date' => now(),
            'performed_by' => auth()->id(),
            'breakdown_id' => $referenceId,
        ]);
    }
}