<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'activity_logs';

    /**
     * Indicates if the model should be timestamped.
     * We only use created_at, no updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'level',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Action constants
     */
    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';
    const ACTION_RESTORE = 'RESTORE';
    const ACTION_FORCE_DELETE = 'FORCE_DELETE';
    const ACTION_LOGIN = 'LOGIN';
    const ACTION_LOGIN_FAILED = 'LOGIN_FAILED';
    const ACTION_LOGOUT = 'LOGOUT';
    const ACTION_VIEW = 'VIEW';
    const ACTION_EXPORT = 'EXPORT';
    const ACTION_IMPORT = 'IMPORT';
    const ACTION_PASSWORD_CHANGE = 'PASSWORD_CHANGE';
    const ACTION_SETTINGS_UPDATE = 'SETTINGS_UPDATE';

    /**
     * Level constants
     */
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_DANGER = 'danger';
    const LEVEL_CRITICAL = 'critical';

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity (polymorphic).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to filter by action.
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by level.
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by IP address.
     */
    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Scope a query to filter by subject.
     */
    public function scopeForSubject($query, $subjectType, $subjectId = null)
    {
        $query->where('subject_type', $subjectType);
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        return $query;
    }

    /**
     * Scope for security-related logs (login, failed attempts, etc.)
     */
    public function scopeSecurityLogs($query)
    {
        return $query->whereIn('action', [
            self::ACTION_LOGIN,
            self::ACTION_LOGIN_FAILED,
            self::ACTION_LOGOUT,
            self::ACTION_PASSWORD_CHANGE,
        ]);
    }

    /**
     * Scope for recent logs.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Create a new activity log entry.
     */
    public static function log(
        string $action,
        string $description,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        string $level = self::LEVEL_INFO
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'level' => $level,
            'created_at' => now(),
        ]);
    }

    /**
     * Get the action label in Indonesian.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            self::ACTION_CREATE => 'Membuat',
            self::ACTION_UPDATE => 'Mengubah',
            self::ACTION_DELETE => 'Menghapus',
            self::ACTION_RESTORE => 'Memulihkan',
            self::ACTION_FORCE_DELETE => 'Menghapus Permanen',
            self::ACTION_LOGIN => 'Login',
            self::ACTION_LOGIN_FAILED => 'Login Gagal',
            self::ACTION_LOGOUT => 'Logout',
            self::ACTION_VIEW => 'Melihat',
            self::ACTION_EXPORT => 'Mengekspor',
            self::ACTION_IMPORT => 'Mengimpor',
            self::ACTION_PASSWORD_CHANGE => 'Mengubah Password',
            self::ACTION_SETTINGS_UPDATE => 'Mengubah Pengaturan',
            default => $this->action,
        };
    }

    /**
     * Get the level badge class.
     */
    public function getLevelBadgeClassAttribute(): string
    {
        return match ($this->level) {
            self::LEVEL_INFO => 'bg-blue-100 text-blue-800',
            self::LEVEL_WARNING => 'bg-yellow-100 text-yellow-800',
            self::LEVEL_DANGER => 'bg-red-100 text-red-800',
            self::LEVEL_CRITICAL => 'bg-red-600 text-white',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
