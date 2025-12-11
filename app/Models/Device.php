<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Device extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'serial_number',
        'model',
        'manufacturer',
        'location',
        'installation_date',
        'status',
        'project_id',
        'name'
    ];

    public function pmPlans()
{
    return $this->hasMany(PmPlan::class);
}

public function pmRecords()
{
    return $this->hasMany(PmRecord::class);
}

public function project()
{
    return $this->belongsTo(Project::class);
}



}
