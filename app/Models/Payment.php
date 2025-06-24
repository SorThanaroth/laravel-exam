<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount_paid',
        'payment_date',
        'status',
        'transaction_id',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public $timestamps = false;

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getIsSuccessfulAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function getIsRefundedAttribute(): bool
    {
        return $this->status === 'refunded';
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }
}