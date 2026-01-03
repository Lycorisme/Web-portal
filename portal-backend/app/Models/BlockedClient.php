<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'attempt_count',
        'is_blocked',
        'blocked_until',
        'reason',
        'blocked_route',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'attempt_count' => 'integer',
        'blocked_until' => 'datetime',
    ];

    /**
     * Scope a query to only include blocked clients.
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }

    /**
     * Scope a query to only include active blocks (not expired).
     */
    public function scopeActiveBlocks($query)
    {
        return $query->blocked()
            ->where(function ($q) {
                $q->whereNull('blocked_until')
                  ->orWhere('blocked_until', '>', now());
            });
    }

    /**
     * Scope a query to find by IP address.
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Check if the block has expired.
     */
    public function isExpired(): bool
    {
        if (!$this->blocked_until) {
            return false; // Permanent block
        }
        
        return $this->blocked_until->isPast();
    }

    /**
     * Unblock the client.
     */
    public function unblock(): bool
    {
        return $this->update([
            'is_blocked' => false,
            'attempt_count' => 0,
        ]);
    }

    /**
     * Block the client for a specific duration.
     */
    public function block(string $reason, ?int $minutes = null): bool
    {
        return $this->update([
            'is_blocked' => true,
            'reason' => $reason,
            'blocked_until' => $minutes ? now()->addMinutes($minutes) : null,
        ]);
    }

    /**
     * Increment the attempt count.
     */
    public function incrementAttempt(): int
    {
        $this->increment('attempt_count');
        return $this->attempt_count;
    }

    /**
     * Check if IP should be blocked based on attempt count threshold.
     */
    public function shouldBlock(int $threshold = 5): bool
    {
        return $this->attempt_count >= $threshold;
    }
}
