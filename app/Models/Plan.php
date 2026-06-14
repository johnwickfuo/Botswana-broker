<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema;

class Plan extends Model
{
    use HasFactory, SoftDeletes;

    // amount_type values (Phase 3 investment-plan builder)
    public const AMOUNT_FIXED = 'fixed';
    public const AMOUNT_RANGED = 'ranged';

    // return_type values (shared with the legacy column; "fixed_amount" is the
    // legacy alias for a flat fixed return)
    public const RETURN_FIXED = 'fixed';
    public const RETURN_PERCENTAGE = 'percentage';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'min_price',
        'max_price',
        'min_return',
        'max_return',
        'bonus_percentage',
        'duration',
        'duration_type',
        'payout_interval',
        'return_type',
        'profit_calculation',
        'allow_compounding',
        'compounding_percentage',
        'featured',
        'badge_text',
        'color_scheme',
        'active',
        'sort_order',
        'features',
        // Phase 3 investment-plan fields
        'asset_id',
        'amount_type',
        'fixed_amount',
        'min_amount',
        'max_amount',
        'fixed_return',
        'return_percentage',
        // Phase 5 investment limits
        'capacity_amount',
        'offer_starts_at',
        'offer_ends_at',
    ];

    protected $casts = [
        'min_price' => 'decimal:8',
        'max_price' => 'decimal:8',
        'min_return' => 'decimal:2',
        'max_return' => 'decimal:2',
        'bonus_percentage' => 'decimal:2',
        'allow_compounding' => 'boolean',
        'featured' => 'boolean',
        'active' => 'boolean',
        'features' => 'array',
        // Phase 3 investment-plan fields
        'fixed_amount' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fixed_return' => 'decimal:2',
        'return_percentage' => 'decimal:2',
        // Phase 5 investment limits
        'capacity_amount' => 'decimal:2',
        'offer_starts_at' => 'datetime',
        'offer_ends_at' => 'datetime',
    ];

    /**
     * Is the plan's offer window currently open? (Phase 5)
     */
    public function isOfferOpen(?\DateTimeInterface $now = null): bool
    {
        $now = $now ? \Illuminate\Support\Carbon::instance($now) : now();

        if ($this->offer_starts_at && $now->lt($this->offer_starts_at)) {
            return false;
        }
        if ($this->offer_ends_at && $now->gt($this->offer_ends_at)) {
            return false;
        }
        return true;
    }

    /**
     * Total principal already committed to this plan by active/matured
     * investments. (Phase 5)
     */
    public function committedCapacity(): float
    {
        return (float) $this->userPlans()
            ->whereIn('status', ['active', 'matured'])
            ->sum('invested_amount');
    }

    /**
     * Remaining investable capacity, or null when uncapped. (Phase 5)
     */
    public function remainingCapacity(): ?float
    {
        if ($this->capacity_amount === null) {
            return null;
        }
        return max(0.0, (float) $this->capacity_amount - $this->committedCapacity());
    }

    /**
     * Get the user plans associated with this plan
     */
    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    /**
     * The government asset this investment plan belongs to (Phase 3).
     */
    public function asset()
    {
        return $this->belongsTo(\App\Models\Asset::class);
    }

    /**
     * Spec-compliant return breakdown for this plan, delegated to the single
     * ReturnCalculator service. Handles all four amount_type/return_type
     * combinations.
     *
     * @return array{principal: float, return: float, total: float}
     */
    public function calculateReturn(?float $amount = null): array
    {
        return app(\App\Services\ReturnCalculator::class)->calculate($this, $amount);
    }

    /**
     * Get the features for this plan
     */
    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    /**
     * Get the categories for this plan
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PlanCategory::class, 'plan_plan_category');
    }

    /**
     * Calculate the duration in days
     */
    public function getDurationInDays(): int
    {
        $multiplier = match ($this->duration_type) {
            'days' => 1,
            'weeks' => 7,
            'months' => 30,
            'years' => 365,
            default => 1,
        };

        return (int) $this->duration * $multiplier;
    }

    /**
     * Calculate the expected return amount
     */
    public function calculateExpectedReturn(float $amount): float
    {
        $returnPercentage = ($this->min_return + $this->max_return) / 2;

        // Simple ROI calculation
        if ($this->return_type === 'percentage') {
            return $amount * ($returnPercentage / 100) + $amount;
        }

        return $amount + $this->price; // Fixed amount return
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Check if the active column exists - this is used until the migration can be run
        static::addGlobalScope('defaultActive', function ($query) {
            if (!\Schema::hasColumn('plans', 'active')) {
                return $query;
            }

            return $query->where(function($q) {
                $q->where('active', true)->orWhereNull('active');
            });
        });
    }

    /**
     * Get active plans
     */
    public function scopeActive($query)
    {
        if (!\Schema::hasColumn('plans', 'active')) {
            return $query;
        }

        return $query->where('active', true);
    }

    /**
     * Get featured plans
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Get plans by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function($q) use ($categoryId) {
            $q->where('plan_category_id', $categoryId);
        });
    }
}
