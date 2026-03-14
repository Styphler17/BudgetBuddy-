<?php
/**
 * Notification Model
 */

class Notification {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getByUserId($userId, $limit = 50) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function clearAll($userId) {
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, title, message, type, icon) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['message'],
            $data['type'] ?? 'info',
            $data['icon'] ?? 'bell'
        ]);
    }
}
