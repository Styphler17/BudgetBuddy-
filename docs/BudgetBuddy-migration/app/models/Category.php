<?php
/**
 * Category Model – full CRUD
 */
class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*,
                COALESCE((
                    SELECT SUM(t.amount)
                    FROM transactions t
                    WHERE t.category_id = c.id AND t.type = 'expense'
                ), 0) AS spent
            FROM categories c
            WHERE c.user_id = ?
            ORDER BY c.name ASC
        ");
        $stmt->execute([(int)$userId]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO categories (user_id, name, emoji, color, budget, created_at)
                VALUES (:user_id, :name, :emoji, :color, :budget, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'name'    => $data['name'],
            'emoji'   => $data['emoji']  ?? '📁',
            'color'   => $data['color']  ?? '#3b82f6',
            'budget'  => $data['budget'] ?? 0,
        ]);
    }

    public function update($id, $userId, $data) {
        $sql = "UPDATE categories
                SET name = :name, emoji = :emoji, color = :color, budget = :budget
                WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'      => (int)$id,
            'user_id' => (int)$userId,
            'name'    => $data['name'],
            'emoji'   => $data['emoji']  ?? '📁',
            'color'   => $data['color']  ?? '#3b82f6',
            'budget'  => $data['budget'] ?? 0,
        ]);
    }

    public function delete($id, $userId) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
        return $stmt->execute([(int)$id, (int)$userId]);
    }
}
