<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\BlockedClient;
use App\Models\ActivityLog;

/**
 * Service untuk tracking aktivitas mencurigakan secara otomatis.
 * IP akan dicatat ke tabel blocked_clients dengan is_blocked = false,
 * sehingga admin dapat review dan melakukan ban permanen jika diperlukan.
 */
class SuspiciousActivityTracker
{
    // Threat levels
    const LEVEL_LOW = 'low';           // Informasi saja
    const LEVEL_MEDIUM = 'medium';     // Perlu diperhatikan
    const LEVEL_HIGH = 'high';         // Perlu tindakan segera
    const LEVEL_CRITICAL = 'critical'; // Auto-block jika threshold tercapai

    // Activity types
    const TYPE_LOGIN_BRUTE_FORCE = 'login_brute_force';
    const TYPE_REGISTER_SPAM = 'register_spam';
    const TYPE_PASSWORD_RESET_ABUSE = 'password_reset_abuse';
    const TYPE_ILLEGAL_CONTENT = 'illegal_content';
    const TYPE_XSS_ATTEMPT = 'xss_attempt';
    const TYPE_UNAUTHORIZED_ACCESS = 'unauthorized_access';
    const TYPE_HONEYPOT_TRIGGERED = 'honeypot_triggered';
    const TYPE_RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';

    /**
     * Threshold untuk auto-block berdasarkan level
     */
    protected array $autoBlockThresholds = [
        self::LEVEL_LOW => 20,      // 20 low-level attempts
        self::LEVEL_MEDIUM => 10,   // 10 medium-level attempts
        self::LEVEL_HIGH => 5,      // 5 high-level attempts
        self::LEVEL_CRITICAL => 3,  // 3 critical-level attempts
    ];

    /**
     * Track suspicious activity dari sebuah IP.
     * 
     * @param Request $request
     * @param string $type Jenis aktivitas (gunakan konstanta TYPE_*)
     * @param string $reason Deskripsi aktivitas mencurigakan
     * @param string $level Level ancaman (gunakan konstanta LEVEL_*)
     * @param string|null $route Route yang diakses
     * @return BlockedClient
     */
    public function track(
        Request $request, 
        string $type, 
        string $reason, 
        string $level = self::LEVEL_MEDIUM, 
        ?string $route = null
    ): BlockedClient {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $route = $route ?? $request->path();

        // Buat atau update record di blocked_clients
        $blockedClient = BlockedClient::firstOrCreate(
            ['ip_address' => $ip],
            [
                'user_agent' => $userAgent,
                'blocked_route' => $route,
                'is_blocked' => false,
                'attempt_count' => 0,
            ]
        );

        // Update data
        $blockedClient->increment('attempt_count');
        $blockedClient->update([
            'user_agent' => $userAgent,
            'blocked_route' => $route,
            'reason' => $this->formatReason($type, $reason, $level),
        ]);

        // Log activity
        $this->logActivity($request, $type, $reason, $level, $blockedClient);

        // Check auto-block threshold
        $this->checkAutoBlock($blockedClient, $level, $type);

        return $blockedClient;
    }

    /**
     * Format reason dengan type dan level untuk tracking
     */
    protected function formatReason(string $type, string $reason, string $level): string
    {
        $levelEmoji = match($level) {
            self::LEVEL_LOW => '🔵',
            self::LEVEL_MEDIUM => '🟡',
            self::LEVEL_HIGH => '🟠',
            self::LEVEL_CRITICAL => '🔴',
            default => '⚪',
        };

        $typeLabels = [
            self::TYPE_LOGIN_BRUTE_FORCE => 'Brute Force Login',
            self::TYPE_REGISTER_SPAM => 'Spam Registrasi',
            self::TYPE_PASSWORD_RESET_ABUSE => 'Abuse Reset Password',
            self::TYPE_ILLEGAL_CONTENT => 'Konten Ilegal',
            self::TYPE_XSS_ATTEMPT => 'Percobaan XSS',
            self::TYPE_UNAUTHORIZED_ACCESS => 'Akses Tidak Sah',
            self::TYPE_HONEYPOT_TRIGGERED => 'Bot Terdeteksi',
            self::TYPE_RATE_LIMIT_EXCEEDED => 'Rate Limit Terlampaui',
        ];

        $typeLabel = $typeLabels[$type] ?? $type;

        return "{$levelEmoji} [{$typeLabel}] {$reason}";
    }

    /**
     * Log activity ke activity_logs
     */
    protected function logActivity(
        Request $request, 
        string $type, 
        string $reason, 
        string $level,
        BlockedClient $blockedClient
    ): void {
        $logLevel = match($level) {
            self::LEVEL_LOW => ActivityLog::LEVEL_INFO,
            self::LEVEL_MEDIUM => ActivityLog::LEVEL_WARNING,
            self::LEVEL_HIGH => ActivityLog::LEVEL_DANGER,
            self::LEVEL_CRITICAL => ActivityLog::LEVEL_DANGER,
            default => ActivityLog::LEVEL_INFO,
        };

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'suspicious_activity_' . $type,
            'description' => "Aktivitas mencurigakan terdeteksi: {$reason} (Percobaan #{$blockedClient->attempt_count})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'level' => $logLevel,
        ]);
    }

    /**
     * Check apakah IP harus auto-block berdasarkan threshold
     */
    protected function checkAutoBlock(BlockedClient $blockedClient, string $level, string $type): void
    {
        $threshold = $this->autoBlockThresholds[$level] ?? 10;

        if ($blockedClient->attempt_count >= $threshold && !$blockedClient->is_blocked) {
            $blockedClient->block(
                "Auto-blocked: Melebihi threshold {$threshold} percobaan untuk level {$level} ({$type})",
                60 * 24 // Block 24 jam
            );

            ActivityLog::create([
                'user_id' => null,
                'action' => 'ip_auto_blocked',
                'description' => "IP {$blockedClient->ip_address} di-block otomatis setelah {$blockedClient->attempt_count} percobaan ({$type})",
                'ip_address' => $blockedClient->ip_address,
                'user_agent' => $blockedClient->user_agent,
                'level' => ActivityLog::LEVEL_DANGER,
            ]);
        }
    }

    /**
     * Get statistics untuk dashboard
     */
    public function getStats(): array
    {
        return [
            'total_tracked' => BlockedClient::count(),
            'active_blocks' => BlockedClient::activeBlocks()->count(),
            'watchlist' => BlockedClient::where('is_blocked', false)->where('attempt_count', '>', 0)->count(),
            'today_attempts' => BlockedClient::whereDate('updated_at', today())->sum('attempt_count'),
        ];
    }
}
