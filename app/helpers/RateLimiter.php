<?php
/**
 * Simple session + file-based rate limiter for authentication endpoints.
 * Tracks attempts per (action, key) pair. Lockout is per-IP by default.
 */
class RateLimiter {

    private static int $maxAttempts = 5;
    private static int $decaySeconds = 900; // 15 minutes

    /**
     * Record a failed attempt and return whether the caller is now locked out.
     */
    public static function hit(string $action, string $key = ''): bool {
        $ip   = self::getIp();
        $slot = 'rl_' . md5($action . '|' . $ip . '|' . $key);
        self::init($slot);

        if (self::isLockedOut($slot)) return true;

        $_SESSION[$slot]['attempts']++;
        $_SESSION[$slot]['last_attempt'] = time();

        return $_SESSION[$slot]['attempts'] >= self::$maxAttempts;
    }

    /**
     * Check if already locked out without recording a new attempt.
     */
    public static function isLockedOut(string $action, string $key = ''): bool {
        // When called with action+key (public interface) we need the slot
        if (!str_starts_with($action, 'rl_')) {
            $ip   = self::getIp();
            $slot = 'rl_' . md5($action . '|' . $ip . '|' . $key);
            self::init($slot);
            return self::isLockedOut($slot);
        }
        // Internal call with already-computed slot
        $slot = $action;
        if (!isset($_SESSION[$slot])) return false;

        $data = $_SESSION[$slot];
        // Decay: if last attempt was > decaySeconds ago, reset
        if ((time() - $data['last_attempt']) > self::$decaySeconds) {
            unset($_SESSION[$slot]);
            return false;
        }
        return $data['attempts'] >= self::$maxAttempts;
    }

    /**
     * Clear attempts on successful authentication.
     */
    public static function clear(string $action, string $key = ''): void {
        $ip   = self::getIp();
        $slot = 'rl_' . md5($action . '|' . $ip . '|' . $key);
        unset($_SESSION[$slot]);
    }

    /**
     * Return seconds until the lockout expires, or 0 if not locked out.
     */
    public static function retryAfter(string $action, string $key = ''): int {
        $ip   = self::getIp();
        $slot = 'rl_' . md5($action . '|' . $ip . '|' . $key);
        if (!isset($_SESSION[$slot])) return 0;
        $elapsed = time() - $_SESSION[$slot]['last_attempt'];
        $remaining = self::$decaySeconds - $elapsed;
        return max(0, $remaining);
    }

    private static function init(string $slot): void {
        if (!isset($_SESSION[$slot])) {
            $_SESSION[$slot] = ['attempts' => 0, 'last_attempt' => time()];
        }
        // Decay reset
        if ((time() - $_SESSION[$slot]['last_attempt']) > self::$decaySeconds) {
            $_SESSION[$slot] = ['attempts' => 0, 'last_attempt' => time()];
        }
    }

    private static function getIp(): string {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
