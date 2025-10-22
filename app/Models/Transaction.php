<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'donor_id',
        'user_id',
        'category_id',
        'amount',
        'desc',
        'transaction_date',
        'payment_method',
        'status',
        'reference_number',
        'external_id',
        'payment_url',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Payment Methods
    public const CASH_METHOD = 'cash';

    public const TRANSFER_METHOD = 'transfer';

    public const PAYMENT_GATEWAY_METHOD = 'payment_gateway';

    // Status
    public const PENDING_STATUS = 'pending';

    public const COMPLETED_STATUS = 'completed';

    public const FAILED_STATUS = 'failed';

    public const CANCELLED_STATUS = 'cancelled';

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function paymentGateway(): HasOne
    {
        return $this->hasOne(PaymentGateway::class);
    }

    /**
     * Check if transaction is online payment
     */
    public function isOnlinePayment(): bool
    {
        return $this->payment_method === self::PAYMENT_GATEWAY_METHOD;
    }

    /**
     * Mark transaction as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => self::COMPLETED_STATUS,
            'paid_at' => now(),
        ]);
    }
}
