<?php
/**
 * Account Model
 */

class Account {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all accounts for a user
     */
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE user_id = ? ORDER BY name ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Update account details
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach (['name', 'type', 'balance', 'currency'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE accounts SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Adjust account balance (increase or decrease)
     */
    public function adjustBalance($id, $amount) {
        $stmt = $this->db->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
        return $stmt->execute([$amount, $id]);
    }

    /**
     * Delete account
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM accounts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
