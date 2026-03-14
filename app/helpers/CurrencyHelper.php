<?php
/**
 * Currency Helper
 */

class CurrencyHelper {
    private static $symbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
        'CAD' => '$',
        'AUD' => '$',
        'GHS' => '₵',
        'NGN' => '₦'
    ];

    public static function getSymbol($code) {
        return self::$symbols[$code] ?? '$';
    }

    public static function format($amount, $code = 'USD') {
        $symbol = self::getSymbol($code);
        return $symbol . number_format($amount, 2);
    }

    /**
     * Format using the user's session currency if available
     */
    public static function autoFormat($amount) {
        $code = $_SESSION['user_currency'] ?? 'USD';
        return self::format($amount, $code);
    }
}
