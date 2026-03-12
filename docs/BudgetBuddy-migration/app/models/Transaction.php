<?php
/**
 * Transaction Model – full CRUD
 */
class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByUserId($userId, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name AS category_name, c.emoji AS category_emoji, c.color AS category_color
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = ?
            ORDER BY t.date DESC, t.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limit,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.name AS category_name, c.emoji AS category_emoji
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.id = ?
        ");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO transactions (user_id, category_id, amount, description, type, date, created_at)
                VALUES (:user_id, :category_id, :amount, :description, :type, :date, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id'     => $data['user_id'],
            'category_id' => $data['category_id'] ?: null,
            'amount'      => $data['amount'],
            'description' => $data['description'] ?? '',
            'type'        => $data['type'],
            'date'        => $data['date'] ?? date('Y-m-d'),
        ]);
    }

    public function update($id, $userId, $data) {
        $sql = "UPDATE transactions
                SET category_id = :category_id, amount = :amount,
                    description = :description, type = :type, date = :date
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'          => (int)$id,
            'user_id'     => (int)$userId,
            'category_id' => $data['category_id'] ?: null,
            'amount'      => $data['amount'],
            'description' => $data['description'] ?? '',
            'type'        => $data['type'],
            'date'        => $data['date'],
        ]);
    }

    public function delete($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
        return $stmt->execute([(int)$id, (int)$userId]);
    }

    public function getTotals($userId, $type, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) AS total
            FROM transactions
            WHERE user_id = ? AND type = ? AND date BETWEEN ? AND ?
        ");
        $stmt->execute([$userId, $type, $startDate, $endDate]);
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }
}
