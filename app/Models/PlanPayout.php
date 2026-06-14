<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanPayout extends Model
{
    use HasFactory;

    public const TYPE_RETURN = 'return';
    public const TYPE_PRINCIPAL = 'principal';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';

    protected $fillable = [
        'user_plan_id',
        'user_id',
        'amount',
        'roi_percentage',
        'type',
        'status',
        'due_date',
        'processed_at',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:8',
        'roi_percentage' => 'decimal:2',
        'due_date' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user plan that owns the payout
     */
    public function userPlan(): BelongsTo
    {
        return $this->belongsTo(UserPlan::class);
    }

    /**
     * Get the user that owns the payout
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending payouts
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processed payouts
     */
    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }
}
