<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentGateway extends Model
{
    // Gateway names
    const XENDIT = 'xendit';

    const MIDTRANS = 'midtrans';

    const MANUAL = 'manual';

    // Status
    const PENDING = 'pending';

    const PAID = 'paid';

    const EXPIRED = 'expired';

    const FAILED = 'failed';

    protected $fillable = [
        'transaction_id',
        'gateway_name',
        'external_id',
        'payment_channel',
        'amount',
        'currency',
        'status',
        'payment_url',
        'invoice_url',
        'expired_at',
        'paid_at',
        'callback_token',
        'webhook_data',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'webhook_data' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Relationship to Transaction
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope: Only pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
    }

    /**
     * Scope: Only paid payments
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::PAID);
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => self::PAID,
            'paid_at' => now(),
        ]);
    }
}
