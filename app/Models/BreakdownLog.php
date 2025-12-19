<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakdownLog extends Model
{
    protected $fillable = [
        'breakdown_id',
        'user_id',
        'action',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

