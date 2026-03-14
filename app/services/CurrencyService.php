<?php
/**
 * Currency Conversion Service
 */

class CurrencyService {
    private $db;
    private $apiUrl = "https://open.er-api.com/v6/latest/"; // Public free API (no key needed for basic use)

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get exchange rate from database or API
     */
    public function getRate($from, $to) {
        if ($from === $to) return 1.0;

        // 1. Try to get from database (cached for 24h)
        $stmt = $this->db->prepare("SELECT rate, updated_at FROM exchange_rates WHERE from_currency = ? AND to_currency = ?");
        $stmt->execute([$from, $to]);
        $row = $stmt->fetch();

        if ($row && (time() - strtotime($row['updated_at']) < 86400)) {
            return (float)$row['rate'];
        }

        // 2. Try to fetch from API
        try {
            $response = @file_get_contents($this->apiUrl . $from);
            if ($response) {
                $data = json_decode($response, true);
                if ($data && isset($data['rates'][$to])) {
                    $rate = (float)$data['rates'][$to];
                    
                    // Update cache
                    $this->updateCache($from, $to, $rate);
                    return $rate;
                }
            }
        } catch (Exception $e) {
            // Log error if needed
        }

        // 3. Fallback to existing database rate even if old
        if ($row) return (float)$row['rate'];

        // 4. Static fallbacks for common pairs if everything fails
        $fallbacks = [
            'USD_EUR' => 0.92,
            'EUR_USD' => 1.09,
            'GBP_USD' => 1.27,
            'USD_GBP' => 0.79,
            'GHS_USD' => 0.08,
            'USD_GHS' => 12.50
        ];
        
        return $fallbacks[$from . '_' . $to] ?? 1.0;
    }

    /**
     * Convert an amount from one currency to another
     */
    public function convert($amount, $from, $to) {
        if ($from === $to) return $amount;
        $rate = $this->getRate($from, $to);
        return $amount * $rate;
    }

    /**
     * Update rate cache in database
     */
    private function updateCache($from, $to, $rate) {
        $sql = "INSERT INTO exchange_rates (from_currency, to_currency, rate, updated_at) 
                VALUES (?, ?, ?, NOW()) 
                ON DUPLICATE KEY UPDATE rate = ?, updated_at = NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$from, $to, $rate, $rate]);
    }
}
