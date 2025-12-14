<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'model_type',
        'model_id',
        'file_path',
        'file_type',
        'uploaded_by',
    ];
}

