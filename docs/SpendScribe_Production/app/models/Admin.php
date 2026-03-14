<?php
/**
 * Admin Model
 */

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Find admin by email
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Verify admin password
     */
    public function verify($email, $password) {
        $admin = $this->findByEmail($email);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            return $admin;
        }
        return false;
    }

    /**
     * Get all admins
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM admins ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get system statistics
     */
    public function getSystemStats() {
        $stats = [];
        
        // Total Users
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
        $stats['totalUsers'] = $stmt->fetch()['total'];
        
        // Total Transactions
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM transactions");
        $stats['totalTransactions'] = $stmt->fetch()['total'];

        // Total Categories
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM categories");
        $stats['totalCategories'] = $stmt->fetch()['total'] ?? 0;

        // Total Goals
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM goals");
        $stats['totalGoals'] = $stmt->fetch()['total'] ?? 0;

        // Total Accounts
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM accounts");
        $stats['totalAccounts'] = $stmt->fetch()['total'] ?? 0;
        
        // Total Income/Expense
        $stmt = $this->db->query("SELECT SUM(amount) as total FROM transactions WHERE type = 'income'");
        $stats['totalIncome'] = $stmt->fetch()['total'] ?? 0;
        
        $stmt = $this->db->query("SELECT SUM(amount) as total FROM transactions WHERE type = 'expense'");
        $stats['totalExpense'] = $stmt->fetch()['total'] ?? 0;
        
        return $stats;
    }

    /**
     * Log admin action
     */
    public function logAction($adminId, $action, $targetType, $targetId = null, $details = null) {
        $sql = "INSERT INTO admin_logs (admin_id, action, target_type, target_id, details, ip_address, created_at) 
                VALUES (:admin_id, :action, :target_type, :target_id, :details, :ip, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'admin_id' => $adminId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'details' => $details,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);
    }

    /**
     * Get recent admin logs
     */
    public function getLogs($limit = 100) {
        $sql = "SELECT l.*, a.name as admin_name, a.email as admin_email 
                FROM admin_logs l 
                JOIN admins a ON l.admin_id = a.id 
                ORDER BY l.created_at DESC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Update admin details
     */
    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['name'])) {
            $fields[] = "name = :name";
            $params[':name'] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = "email = :email";
            $params[':email'] = $data['email'];
        }
        if (isset($data['password_hash'])) {
            $fields[] = "password_hash = :password_hash";
            $params[':password_hash'] = password_hash($data['password_hash'], PASSWORD_DEFAULT);
        }
        if (isset($data['role'])) {
            $fields[] = "role = :role";
            $params[':role'] = $data['role'];
        }
        if (isset($data['profile_pic'])) {
            $fields[] = "profile_pic = :profile_pic";
            $params[':profile_pic'] = $data['profile_pic'];
        }

        if (empty($fields)) return false;

        $sql = "UPDATE admins SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Create new admin
     */
    public function create($data) {
        $sql = "INSERT INTO admins (name, email, password_hash, role, created_at, updated_at) 
                VALUES (:name, :email, :password_hash, :role, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role'] ?? 'admin'
        ]);
    }
}
