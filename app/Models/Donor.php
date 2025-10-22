<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'notes',
        'type',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    public const INDIVIDUAL = 'individual';

    public const ORGANIZATION = 'organization';

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Find donor by email or create new one
     * Prevents duplicate non-anonymous donors
     */
    public static function findOrCreateByEmail(?string $email, array $data): self
    {
        // If no email or anonymous, always create new
        if (! $email || ($data['is_anonymous'] ?? false)) {
            return static::create($data);
        }

        // Try to find existing non-anonymous donor
        $donor = static::where('email', $email)
            ->where('is_anonymous', false)
            ->first();

        if ($donor) {
            // Update existing donor data if needed
            $donor->update([
                'name' => $data['name'] ?? $donor->name,
                'phone' => $data['phone'] ?? $donor->phone,
                'address' => $data['address'] ?? $donor->address,
            ]);

            return $donor;
        }

        // Create new donor
        return static::create($data);
    }

    /**
     * Scope to filter anonymous donors
     */
    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    /**
     * Scope to filter non-anonymous donors
     */
    public function scopeNonAnonymous($query)
    {
        return $query->where('is_anonymous', false);
    }
}
