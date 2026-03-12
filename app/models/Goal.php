<?php
/**
 * Goal Model
 */

class Goal {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all goals for a user
     */
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM goals WHERE user_id = ? ORDER BY deadline ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Create a new goal
     */
    public function create($data) {
        $sql = "INSERT INTO goals (user_id, name, target_amount, current_amount, deadline, created_at) 
                VALUES (:user_id, :name, :target_amount, :current_amount, :deadline, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'target_amount' => $data['target_amount'],
            'current_amount' => $data['current_amount'] ?? 0,
            'deadline' => $data['deadline'] ?? null
        ]);
    }

    /**
     * Update goal progress
     */
    public function updateProgress($id, $amount) {
        $stmt = $this->db->prepare("UPDATE goals SET current_amount = current_amount + ? WHERE id = ?");
        return $stmt->execute([$amount, $id]);
    }

    /**
     * Update goal details
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach (['name', 'target_amount', 'current_amount', 'deadline'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE goals SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Delete goal
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM goals WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
