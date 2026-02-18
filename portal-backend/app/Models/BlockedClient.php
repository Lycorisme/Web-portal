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
        'user_id',
        'user_name',
        'last_login_at',
        'login_count',
    ];

    protected $casts = [
        'is_blocked' => 'boolean',
        'attempt_count' => 'integer',
        'blocked_until' => 'datetime',
        'last_login_at' => 'datetime',
        'login_count' => 'integer',
    ];

    /**
     * Get the user associated with this IP record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a successful login IP for monitoring.
     * Creates or updates a record for the IP+user combination.
     */
    public static function recordLogin(\Illuminate\Http\Request $request, User $user): self
    {
        $record = static::where('ip_address', $request->ip())
            ->where('user_id', $user->id)
            ->first();

        if ($record) {
            $record->update([
                'user_name' => $user->name,
                'user_agent' => $request->userAgent(),
                'last_login_at' => now(),
                'login_count' => $record->login_count + 1,
            ]);
            return $record;
        }

        // Check if IP exists without user (e.g. from failed attempts)
        $existingIp = static::where('ip_address', $request->ip())
            ->whereNull('user_id')
            ->first();

        if ($existingIp) {
            // Update existing record with user info
            $existingIp->update([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_agent' => $request->userAgent(),
                'last_login_at' => now(),
                'login_count' => $existingIp->login_count + 1,
            ]);
            return $existingIp;
        }

        // Create new monitoring record (not blocked)
        return static::create([
            'ip_address' => $request->ip(),
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_agent' => $request->userAgent(),
            'is_blocked' => false,
            'attempt_count' => 0,
            'reason' => null,
            'blocked_route' => null,
            'last_login_at' => now(),
            'login_count' => 1,
        ]);
    }

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
     * Scope for logged-in IPs (monitoring only).
     */
    public function scopeLoggedIn($query)
    {
        return $query->where('is_blocked', false)
            ->whereNotNull('user_id')
            ->whereNotNull('last_login_at');
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
