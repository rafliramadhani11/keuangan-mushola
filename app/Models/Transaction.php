<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
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
    ];

    public const CASH_METHOD = 'cash';

    public const TRANSFER_METHOD = 'transfer';

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

    #[Scope]
    public function incomeData(Builder $query): void
    {
        $query->whereHas('category', function (Builder $q) {
            $q->where('type', Category::INCOME);
        });
    }

    #[Scope]
    public function expenseData(Builder $query): void
    {
        $query->whereHas('category', function (Builder $q) {
            $q->where('type', Category::EXPENSE);
        });
    }
}
