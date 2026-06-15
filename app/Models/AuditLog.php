<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_type', 'actor_id', 'actor_name', 'action',
        'subject_type', 'subject_id', 'description', 'properties', 'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Record a sensitive action. Resolves the current actor (admin guard
     * first, then the web user, else system) automatically. Never throws — an
     * audit failure must not break the underlying action.
     */
    public static function record(string $action, $subject = null, ?string $description = null, array $properties = []): void
    {
        try {
            [$actorType, $actorId, $actorName] = self::resolveActor();

            self::create([
                'actor_type'   => $actorType,
                'actor_id'     => $actorId,
                'actor_name'   => $actorName,
                'action'       => $action,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject->id ?? null,
                'description'  => $description,
                'properties'   => $properties ?: null,
                'ip_address'   => request()?->ip(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Audit log failed for action ' . $action . ': ' . $e->getMessage());
        }
    }

    private static function resolveActor(): array
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $name = trim(($admin->firstName ?? $admin->name ?? '') . ' ' . ($admin->lastName ?? ''));
            return ['admin', $admin->id, $name ?: 'Admin'];
        }

        if (Auth::check()) {
            $user = Auth::user();
            return ['user', $user->id, trim(($user->name ?? '') . ' ' . ($user->l_name ?? '')) ?: 'User'];
        }

        return ['system', null, 'System'];
    }
}
