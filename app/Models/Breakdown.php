<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breakdown extends Model
{
    use HasFactory;

    protected $fillable = [
    'device_id',
    'project_id',
    'reported_by',
    'assigned_to',
    'title',
    'description',
    'status',
    'reported_at',
];

    /**
     * الجهاز المرتبط بالبلاغ
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * المشروع المستهدف
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * الفني المَسنود له البلاغ
     */
    public function assignedEngineer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reporter()
{
    return $this->belongsTo(User::class, 'reported_by');
}

public function assignee()
{
    return $this->belongsTo(User::class, 'assigned_to');
}

public function assignedUser()
{
    return $this->belongsTo(User::class, 'assigned_to');
}


}
