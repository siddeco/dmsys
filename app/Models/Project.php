<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'client', 'city', 'description', 'start_date', 'end_date'
    ];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    // جميع خطط PM الخاصة بالمشروع عبر الأجهزة
    public function pmPlans()
    {
        return $this->hasManyThrough(PmPlan::class, Device::class);
    }

    public function pmRecords()
    {
        return $this->hasManyThrough(PmRecord::class, Device::class);
    }

    public function documents()
{
    return $this->hasMany(ProjectDocument::class);
}

}
