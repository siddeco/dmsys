<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'type',
        'file_path',
        'original_name',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

