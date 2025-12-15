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
        'city',
        'installation_date',
        'status',
        'project_id',
        'name',
        'is_archived'
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

protected $casts = [
    'is_archived' => 'boolean',
];

public function scopeActive($query)
{
    return $query->where('is_archived', false);
}




}
