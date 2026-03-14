<?php
/**
 * Recurring Transaction Model
 */

class RecurringTransaction {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all recurring transactions for a user
     */
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT rt.*, c.name as category_name, a.name as account_name 
            FROM recurring_transactions rt 
            LEFT JOIN categories c ON rt.category_id = c.id 
            LEFT JOIN accounts a ON rt.account_id = a.id
            WHERE rt.user_id = ? 
            ORDER BY rt.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Get due recurring transactions
     */
    public function getDue($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM recurring_transactions 
            WHERE user_id = ? AND is_active = 1 AND next_run_date <= ?
        ");
        $stmt->execute([$userId, date('Y-m-d')]);
        return $stmt->fetchAll();
    }

    /**
     * Create recurring transaction
     */
    public function create($data) {
        $sql = "INSERT INTO recurring_transactions (user_id, account_id, category_id, amount, description, type, frequency, start_date, next_run_date) 
                VALUES (:user_id, :account_id, :category_id, :amount, :description, :type, :frequency, :start_date, :next_run_date)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'account_id' => $data['account_id'],
            'category_id' => $data['category_id'] ?? null,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? '',
            'type' => $data['type'],
            'frequency' => $data['frequency'],
            'start_date' => $data['start_date'],
            'next_run_date' => $data['start_date']
        ]);
    }

    /**
     * Update run dates
     */
    public function updateRunDates($id, $lastRunDate, $nextRunDate) {
        $stmt = $this->db->prepare("UPDATE recurring_transactions SET last_run_date = ?, next_run_date = ? WHERE id = ?");
        return $stmt->execute([$lastRunDate, $nextRunDate, $id]);
    }

    /**
     * Update recurring transaction
     */
    public function update($id, $data) {
        $sql = "UPDATE recurring_transactions SET 
                account_id = :account_id, 
                category_id = :category_id, 
                amount = :amount, 
                description = :description, 
                type = :type, 
                frequency = :frequency, 
                start_date = :start_date,
                updated_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'account_id' => $data['account_id'],
            'category_id' => $data['category_id'] ?? null,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? '',
            'type' => $data['type'],
            'frequency' => $data['frequency'],
            'start_date' => $data['start_date']
        ]);
    }

    /**
     * Delete recurring transaction
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM recurring_transactions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Calculate next run date
     */
    public static function calculateNextRun($frequency, $currentDate) {
        $date = new DateTime($currentDate);
        switch ($frequency) {
            case 'daily': $date->modify('+1 day'); break;
            case 'weekly': $date->modify('+1 week'); break;
            case 'monthly': $date->modify('+1 month'); break;
            case 'yearly': $date->modify('+1 year'); break;
        }
        return $date->format('Y-m-d');
    }
}
