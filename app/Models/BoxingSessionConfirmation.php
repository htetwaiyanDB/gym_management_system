<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxingSessionConfirmation extends Model
{
    protected $fillable = [
        'boxing_booking_id',
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

    public function booking()
    {
        return $this->belongsTo(BoxingBooking::class, 'boxing_booking_id');
    }
}
