<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Asset extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    // Investment-term values (assets are the investable entity).
    public const AMOUNT_FIXED = 'fixed';
    public const AMOUNT_RANGED = 'ranged';
    public const RETURN_FIXED = 'fixed';
    public const RETURN_PERCENTAGE = 'percentage';

    protected $fillable = [
        'name',
        'description',
        'category',
        'symbol',
        'status',
        // Investment terms (set by admin per asset)
        'amount_type',
        'fixed_amount',
        'min_amount',
        'max_amount',
        'return_type',
        'fixed_return',
        'return_percentage',
        'duration',
        'duration_type',
        'payout_interval',
        'capacity_amount',
        'offer_starts_at',
        'offer_ends_at',
    ];

    protected $casts = [
        'fixed_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fixed_return' => 'decimal:2',
        'return_percentage' => 'decimal:2',
        'capacity_amount' => 'decimal:2',
        'offer_starts_at' => 'datetime',
        'offer_ends_at' => 'datetime',
    ];

    /**
     * All official documents (certificates + supporting) for this asset.
     */
    public function documents()
    {
        return $this->hasMany(AssetDocument::class);
    }

    public function certificates()
    {
        return $this->hasMany(AssetDocument::class)->where('type', AssetDocument::TYPE_CERTIFICATE);
    }

    public function supportingDocuments()
    {
        return $this->hasMany(AssetDocument::class)->where('type', AssetDocument::TYPE_SUPPORTING);
    }

    /**
     * Investments made into this asset (UserPlan records keyed by asset_id).
     */
    public function investments()
    {
        return $this->hasMany(UserPlan::class, 'asset_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Is the asset's investment offer currently open?
     */
    public function isOfferOpen(?\DateTimeInterface $now = null): bool
    {
        $now = $now ? Carbon::instance($now) : Carbon::now();

        if ($this->offer_starts_at && $now->lt($this->offer_starts_at)) {
            return false;
        }
        if ($this->offer_ends_at && $now->gt($this->offer_ends_at)) {
            return false;
        }
        return true;
    }

    /**
     * Total principal already committed to this asset.
     */
    public function committedCapacity(): float
    {
        return (float) $this->investments()
            ->whereIn('status', ['active', 'matured'])
            ->sum('invested_amount');
    }

    /**
     * Remaining investable capacity, or null when uncapped.
     */
    public function remainingCapacity(): ?float
    {
        if ($this->capacity_amount === null) {
            return null;
        }
        return max(0.0, (float) $this->capacity_amount - $this->committedCapacity());
    }

    /**
     * Duration of the investment term in days.
     */
    public function getDurationInDays(): int
    {
        $units = (int) ($this->duration ?? 0);

        return match ($this->duration_type) {
            'weeks' => $units * 7,
            'months' => $units * 30,
            'years' => $units * 365,
            default => $units,
        };
    }
}
