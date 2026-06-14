<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name',
        'description',
        'category',
        'symbol',
        'status',
    ];

    /**
     * All official documents (certificates + supporting) for this asset.
     */
    public function documents()
    {
        return $this->hasMany(AssetDocument::class);
    }

    /**
     * Government certificate file(s).
     */
    public function certificates()
    {
        return $this->hasMany(AssetDocument::class)->where('type', AssetDocument::TYPE_CERTIFICATE);
    }

    /**
     * Supporting documents.
     */
    public function supportingDocuments()
    {
        return $this->hasMany(AssetDocument::class)->where('type', AssetDocument::TYPE_SUPPORTING);
    }

    /**
     * Scope: only active assets (available to citizens).
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
