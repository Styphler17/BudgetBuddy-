<?php
/**
 * Goal Model – full CRUD
 */
class Goal {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY deadline ASC");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM goals WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO goals (user_id, name, target_amount, current_amount, deadline, category_id, created_at)
                VALUES (:user_id, :name, :target_amount, :current_amount, :deadline, :category_id, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id'        => $data['user_id'],
            'name'           => $data['name'],
            'target_amount'  => $data['target_amount'],
            'current_amount' => $data['current_amount'] ?? 0,
            'deadline'       => $data['deadline']       ?: null,
            'category_id'    => $data['category_id']    ?: null,
        ]);
    }

    public function update($id, $userId, $data) {
        $sql = "UPDATE goals
                SET name = :name, target_amount = :target_amount,
                    current_amount = :current_amount, deadline = :deadline,
                    category_id = :category_id
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'             => (int)$id,
            'user_id'        => (int)$userId,
            'name'           => $data['name'],
            'target_amount'  => $data['target_amount'],
            'current_amount' => $data['current_amount'] ?? 0,
            'deadline'       => $data['deadline']       ?: null,
            'category_id'    => $data['category_id']    ?: null,
        ]);
    }

    public function delete($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM goals WHERE id = ? AND user_id = ?");
        return $stmt->execute([(int)$id, (int)$userId]);
    }

    public function updateProgress($id, $userId, $amount) {
        $stmt = $this->db->prepare("UPDATE goals SET current_amount = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$amount, (int)$id, (int)$userId]);
    }
}
