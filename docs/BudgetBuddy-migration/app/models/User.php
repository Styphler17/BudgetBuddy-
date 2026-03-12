<?php
/**
 * User Model
 */
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function getAll($limit = 100) {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO users (name, email, password_hash, currency, created_at, updated_at)
                VALUES (:name, :email, :password_hash, :currency, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'currency'      => $data['currency'] ?? 'USD',
        ]);
    }

    public function update($id, $data) {
        $fields = [];
        $params = ['id' => (int)$id];

        if (!empty($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = $data['name'];
        }
        if (!empty($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }
        if (!empty($data['currency'])) {
            $fields[] = 'currency = :currency';
            $params['currency'] = $data['currency'];
        }
        if (!empty($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) return false;

        $fields[] = 'updated_at = NOW()';
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function verify($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
}
