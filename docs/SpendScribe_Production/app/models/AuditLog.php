<?php
/**
 * AuditLog Model
 */

class AuditLog {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Create a new log entry
     */
    public function log($userId, $action, $details = null) {
        $sql = "INSERT INTO user_logs (user_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userId,
            $action,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    /**
     * Get logs for a specific user
     */
    public function getByUserId($userId, $limit = 20) {
        $sql = "SELECT * FROM user_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
