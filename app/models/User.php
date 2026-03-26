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
     * Get all users
     */
    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Create a new user with verification token
     */
    public function create($data) {
        $token = bin2hex(random_bytes(32));
        $sql = "INSERT INTO users (name, email, password_hash, currency, is_active, email_verified, verification_token, created_at, updated_at) 
                VALUES (?, ?, ?, ?, 1, 0, ?, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['currency'] ?? 'USD',
            $token
        ]);

        if ($result) {
            return $token;
        }
        return false;
    }

    /**
     * Verify email by token
     */
    public function verifyEmail($token) {
        $stmt = $this->db->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE verification_token = ?");
        return $stmt->execute([$token]);
    }

    /**
     * Update user details (updated to include 2FA fields)
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowedFields = ['name', 'email', 'password_hash', 'currency', 'is_active', 'email_verified', 'two_factor_secret', 'two_factor_enabled', 'recovery_codes', 'profile_pic'];
        foreach ($allowedFields as $f) {
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
     * Verify user password and status
     */
    public function verify($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }

        // Check password
        if (password_verify($password, $user['password_hash'])) {
            // Check if email is verified
            if (!$user['email_verified']) {
                return ['status' => 'unverified'];
            }

            // Check if account is active
            if (!$user['is_active']) {
                return ['status' => 'inactive'];
            }

            // Check if 2FA is enabled
            if ($user['two_factor_enabled']) {
                return ['status' => 'require_2fa', 'user' => $user];
            }

            return ['status' => 'success', 'user' => $user];
        }
        
        return false;
    }

    /**
     * Store a password-reset token (hashed) and expiry for the given user.
     */
    public function setResetToken(int $userId, string $plainToken, string $expires): void {
        $hash = hash('sha256', $plainToken);
        $stmt = $this->db->prepare(
            "UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE id = ?"
        );
        $stmt->execute([$hash, $expires, $userId]);
    }

    /**
     * Find a user by a plain-text reset token (compares against stored hash).
     * Returns null if not found or expired.
     */
    public function findByResetToken(string $plainToken): ?array {
        $hash = hash('sha256', $plainToken);
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()"
        );
        $stmt->execute([$hash]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Set a new password and clear the reset token.
     */
    public function resetPassword(int $userId, string $newPassword): void {
        $stmt = $this->db->prepare(
            "UPDATE users SET password_hash = ?, password_reset_token = NULL, password_reset_expires = NULL, updated_at = NOW() WHERE id = ?"
        );
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
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
