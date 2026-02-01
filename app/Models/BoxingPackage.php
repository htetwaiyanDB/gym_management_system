<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxingPackage extends Model
{
    protected $fillable = [
        'name',
        'package_type',
        'sessions_count',
        'duration_months',
        'price',
    ];

    protected $casts = [
        'sessions_count' => 'integer',
        'duration_months' => 'integer',
        'price' => 'decimal:2',
    ];
}
