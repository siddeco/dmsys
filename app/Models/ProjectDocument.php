<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    protected $fillable = [
        'project_id',
        'type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
        'is_archived',
        'archived_at',
        'archived_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function download(ProjectDocument $document)
    {
        abort_unless(auth()->user()->can('view projects'), 403);

        return response()->download(
            storage_path('app/public/' . $document->file_path),
            $document->original_name
        );
    }

    protected $casts = [
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function archiver()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }


}

