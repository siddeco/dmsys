<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Device extends Model
{
    use Translatable;

    protected $fillable = [
        'serial_number',
        'model',
        'manufacturer',
        'location',
        'installation_date',
        'status'
    ];

    public $translatedAttributes = ['name', 'description'];
}
