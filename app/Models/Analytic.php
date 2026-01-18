<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Analytic extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'trackable_type',
        'trackable_id',
        'ip_address',
        'user_agent',
        'referer',
    ];

    public static function track(string $eventType, User $user, ?Model $trackable = null): void
    {
        // Don't track if the authenticated user is viewing their own content
        if (auth()->check() && auth()->id() === $user->id) {
            return;
        }

        self::create([
            'user_id' => $user->id,
            'event_type' => $eventType,
            'trackable_type' => $trackable ? get_class($trackable) : null,
            'trackable_id' => $trackable?->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }
}
