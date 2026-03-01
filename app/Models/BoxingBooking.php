<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoxingBooking extends Model
{
    protected $appends = [
        'member_phone',
        'trainer_phone',
    ];

    protected $fillable = [
        'member_id',
        'trainer_id',
        'boxing_package_id',
        'sessions_count',
        'sessions_remaining',
        'sessions_start_date',
        'sessions_end_date',
        'month_start_date',
        'month_end_date',
        'hold_start_date',
        'hold_end_date',
        'total_hold_days',
        'price_per_session',
        'total_price',
        'discount_percentage',
        'final_price',
        'status',
        'paid_status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'sessions_start_date' => 'datetime',
        'sessions_end_date' => 'datetime',
        'month_start_date' => 'datetime',
        'month_end_date' => 'datetime',
        'hold_start_date' => 'datetime',
        'hold_end_date' => 'datetime',
        'total_hold_days' => 'integer',
        'discount_percentage' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function boxingPackage(): BelongsTo
    {
        return $this->belongsTo(BoxingPackage::class);
    }

    public function sessionConfirmations()
    {
        return $this->hasMany(BoxingSessionConfirmation::class);
    }

    public function getMemberPhoneAttribute(): ?string
    {
        return $this->member?->phone;
    }

    public function getTrainerPhoneAttribute(): ?string
    {
        return $this->trainer?->phone;
    }

    public function isMonthBased(): bool
    {
        return (int) ($this->boxingPackage?->duration_months ?? 0) > 0;
    }

    public function isSessionBased(): bool
    {
        return ! $this->isMonthBased();
    }
}
