<?php
/**
 * Account Model – full CRUD
 */
class Account {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE user_id = ? ORDER BY name ASC");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO accounts (user_id, name, type, balance, currency, created_at)
                VALUES (:user_id, :name, :type, :balance, :currency, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id'  => $data['user_id'],
            'name'     => $data['name'],
            'type'     => $data['type'],
            'balance'  => $data['balance']  ?? 0,
            'currency' => $data['currency'] ?? 'USD',
        ]);
    }

    public function update($id, $userId, $data) {
        $sql = "UPDATE accounts
                SET name = :name, type = :type, balance = :balance, currency = :currency
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'       => (int)$id,
            'user_id'  => (int)$userId,
            'name'     => $data['name'],
            'type'     => $data['type'],
            'balance'  => $data['balance'],
            'currency' => $data['currency'] ?? 'USD',
        ]);
    }

    public function delete($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM accounts WHERE id = ? AND user_id = ?");
        return $stmt->execute([(int)$id, (int)$userId]);
    }
}
