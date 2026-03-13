<?php
/**
 * User Model
 */

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Update user details
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach (['name', 'email', 'password_hash', 'currency', 'is_active', 'email_verified'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[":$f"] = $data[$f];
            }
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Get all users
     */
    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Create a new user
     */
    public function create($data) {
        $sql = "INSERT INTO users (name, email, password_hash, currency, is_active, email_verified, created_at, updated_at) 
                VALUES (?, ?, ?, ?, 1, 0, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['currency'] ?? 'USD'
        ]);
    }
    
    /**
     * Verify user password and status
     */
    public function verify($email, $password) {
        $user = $this->findByEmail($email);
        $log_file = dirname(dirname(__DIR__)) . '/login_debug.log';
        
        if (!$user) {
            file_put_contents($log_file, date('[Y-m-d H:i:s]') . " FAIL: User not found ($email)\n", FILE_APPEND);
            return false;
        }

        if (empty($user['password_hash'])) {
            file_put_contents($log_file, date('[Y-m-d H:i:s]') . " FAIL: Empty hash for $email\n", FILE_APPEND);
            return false;
        }

        // Check password
        if (password_verify($password, $user['password_hash'])) {
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                file_put_contents($log_file, date('[Y-m-d H:i:s]') . " FAIL: Inactive account ($email)\n", FILE_APPEND);
                return false;
            }
            file_put_contents($log_file, date('[Y-m-d H:i:s]') . " SUCCESS: Login for $email\n", FILE_APPEND);
            return $user;
        } else {
            file_put_contents($log_file, date('[Y-m-d H:i:s]') . " FAIL: password_verify failed for $email\n", FILE_APPEND);
        }
        
        return false;
    }

    /**
     * Delete user account and all associated data
     */
    public function delete($id) {
        // Cascading deletes should be handled by DB foreign keys, but we can be explicit if needed.
        // The schema shows ON DELETE CASCADE for most tables.
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
