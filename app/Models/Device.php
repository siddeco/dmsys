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
        'name',
    ];
}
