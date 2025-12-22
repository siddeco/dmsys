<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'employee_id',
        'user_type',
        'organization_name',
        'organization_type',
        'city',
        'region',
        'address',
        'notes',
        'is_active',
        'last_login_at',
        'last_login_ip'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // العلاقات
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
            ->withPivot(['role', 'assigned_date', 'hourly_rate', 'notes']);
    }

    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function assignedDevices()
    {
        return $this->hasMany(Device::class, 'assigned_to');
    }

    public function reportedBreakdowns()
    {
        return $this->hasMany(Breakdown::class, 'reported_by');
    }

    public function assignedBreakdowns()
    {
        return $this->hasMany(Breakdown::class, 'assigned_to');
    }

    public function performedMaintenance()
    {
        return $this->hasMany(PmRecord::class, 'performed_by');
    }

    public function verifiedMaintenance()
    {
        return $this->hasMany(PmRecord::class, 'verified_by');
    }

    public function sparePartUsages()
    {
        return $this->hasMany(SparePartUsage::class, 'performed_by');
    }

    public function calibrations()
    {
        return $this->hasMany(CalibrationRecord::class, 'calibrated_by');
    }

    public function verifiedCalibrations()
    {
        return $this->hasMany(CalibrationRecord::class, 'verified_by');
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(ProjectDocument::class, 'uploaded_by');
    }

    public function reviewedDocuments()
    {
        return $this->hasMany(ProjectDocument::class, 'reviewed_by');
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeCompanyStaff($query)
    {
        return $query->where('user_type', 'company_staff');
    }
    public function scopeClientStaff($query)
    {
        return $query->where('user_type', 'client_staff');
    }
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }
    public function scopeByOrganizationType($query, $type)
    {
        return $query->where('organization_type', $type);
    }

    // التوابع المساعدة
    public function isCompanyStaff()
    {
        return $this->user_type === 'company_staff';
    }
    public function isClientStaff()
    {
        return $this->user_type === 'client_staff';
    }
    public function getStatsAttribute()
    {
        return [
            'managed_projects' => $this->managedProjects()->count(),
            'assigned_devices' => $this->assignedDevices()->count(),
            'active_breakdowns' => $this->assignedBreakdowns()->whereIn('status', ['open', 'assigned'])->count(),
        ];
    }
}