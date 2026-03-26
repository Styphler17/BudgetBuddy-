<?php
/**
 * Security Helper for 2FA (TOTP) and Base32
 * Implements RFC 4226 (HOTP) and RFC 6238 (TOTP)
 */

class SecurityHelper {
    
    /**
     * Generate a random Base32 secret for 2FA
     */
    public static function generate2FASecret($length = 16) {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $base32chars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Verify a TOTP code against a secret
     */
    public static function verifyTOTP($secret, $code, $discrepancy = 1) {
        if (empty($secret) || empty($code)) return false;
        
        $currentTimeSlice = floor(time() / 30);

        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = self::getCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the code for a given secret and time slice
     */
    private static function getCode($secret, $timeSlice) {
        $secretKey = self::base32Decode($secret);

        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        
        // Hash it with SHA1
        $hmac = hash_hmac('SHA1', $time, $secretKey, true);
        
        // Dynamic truncation
        $offset = ord(substr($hmac, -1)) & 0x0F;
        $hashPart = substr($hmac, $offset, 4);
        
        // Unpack binary value
        $value = unpack('N', $hashPart);
        $value = $value[1];
        $value = $value & 0x7FFFFFFF;

        $modulo = pow(10, 6);
        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Helper to decode Base32 strings
     */
    private static function base32Decode($base32) {
        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $base32charsFlipped = array_flip(str_split($base32chars));

        $output = '';
        $i = 0;
        $buffer = 0;
        $bufferSize = 0;

        $base32 = strtoupper($base32);
        $base32 = str_replace('=', '', $base32);

        for ($i = 0; $i < strlen($base32); $i++) {
            $char = $base32[$i];
            if (!isset($base32charsFlipped[$char])) continue;

            $buffer = ($buffer << 5) | $base32charsFlipped[$char];
            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;
                $output .= chr(($buffer >> $bufferSize) & 0xFF);
            }
        }

        return $output;
    }
}
