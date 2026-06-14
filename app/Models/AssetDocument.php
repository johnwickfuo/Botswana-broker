<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDocument extends Model
{
    use HasFactory;

    public const TYPE_CERTIFICATE = 'certificate';
    public const TYPE_SUPPORTING = 'supporting';

    /**
     * Disk used for the (private) document storage.
     */
    public const DISK = 'local';

    protected $fillable = [
        'asset_id',
        'type',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function isCertificate(): bool
    {
        return $this->type === self::TYPE_CERTIFICATE;
    }

    /**
     * Human-readable file size (e.g. "1.4 MB").
     */
    public function getReadableSizeAttribute(): string
    {
        $bytes = (int) $this->size;
        if ($bytes <= 0) {
            return '—';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);
        return round($bytes / (1024 ** $power), 1) . ' ' . $units[$power];
    }
}
