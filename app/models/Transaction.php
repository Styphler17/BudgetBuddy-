<?php
/**
 * Transaction Model
 */

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get transactions for a user
     */
    public function getByUserId($userId, $limit = 50) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name as category_name, c.emoji as category_emoji, c.color as category_color 
            FROM transactions t 
            LEFT JOIN categories c ON t.category_id = c.id 
            WHERE t.user_id = ? 
            ORDER BY t.date DESC, t.created_at DESC 
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Create a new transaction
     */
    public function create($data) {
        $sql = "INSERT INTO transactions (user_id, category_id, amount, description, type, date, created_at) 
                VALUES (:user_id, :category_id, :amount, :description, :type, :date, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'] ?? null,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? '',
            'type' => $data['type'], // 'income' or 'expense'
            'date' => $data['date'] ?? date('Y-m-d')
        ]);
    }

    /**
     * Update transaction
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach (['category_id', 'amount', 'description', 'type', 'date'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE transactions SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete transaction
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get total spent/earned in a specific period
     */
    public function getTotals($userId, $type, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT SUM(amount) as total 
            FROM transactions 
            WHERE user_id = ? AND type = ? AND date BETWEEN ? AND ?
        ");
        $stmt->execute([$userId, $type, $startDate, $endDate]);
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }
}
