<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerSessionConfirmation extends Model
{
    protected $fillable = [
        'trainer_booking_id',
        'token',
        'user_confirmed_at',
        'trainer_confirmed_at',
        'confirmed_at',
    ];

    protected $casts = [
        'user_confirmed_at' => 'datetime',
        'trainer_confirmed_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(TrainerBooking::class, 'trainer_booking_id');
    }
}
