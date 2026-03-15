<?php
/**
 * Category Model
 */

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all categories for a user
     */
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE user_id = ? ORDER BY name ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Create a new category
     */
    public function create($data) {
        $sql = "INSERT INTO categories (user_id, name, emoji, color, budget, created_at) 
                VALUES (:user_id, :name, :emoji, :color, :budget, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'emoji' => $data['emoji'] ?? '📁',
            'color' => $data['color'] ?? '#3b82f6',
            'budget' => $data['budget'] ?? 0
        ]);
    }

    /**
     * Update a category
     */
    public function update($id, $data) {
        $sql = "UPDATE categories SET name = :name, emoji = :emoji, color = :color, budget = :budget WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'emoji' => $data['emoji'],
            'color' => $data['color'],
            'budget' => $data['budget']
        ]);
    }

    /**
     * Delete a category
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
