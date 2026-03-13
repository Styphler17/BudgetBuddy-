<?php
/**
 * Transaction Model
 */

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get transactions for a user with optional filtering
     */
    public function getByUserId($userId, $limit = 50, $filters = []) {
        $sql = "
            SELECT t.*, c.name as category_name, c.emoji as category_emoji, c.color as category_color, a.name as account_name 
            FROM transactions t 
            LEFT JOIN categories c ON t.category_id = c.id 
            LEFT JOIN accounts a ON t.account_id = a.id
            WHERE t.user_id = :user_id
        ";
        $params = [':user_id' => (int)$userId];

        if (!empty($filters['search'])) {
            $sql .= " AND (t.description LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['account_id'])) {
            $sql .= " AND t.account_id = :account_id";
            $params[':account_id'] = $filters['account_id'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND t.type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['start_date'])) {
            $sql .= " AND t.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $sql .= " AND t.date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        $sql .= " ORDER BY t.date DESC, t.created_at DESC LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Create a new transaction (also updates account balance)
     */
    public function create($data) {
        $sql = "INSERT INTO transactions (user_id, category_id, account_id, amount, description, type, is_transfer, transfer_id, date, created_at) 
                VALUES (:user_id, :category_id, :account_id, :amount, :description, :type, :is_transfer, :transfer_id, :date, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'user_id' => $data['user_id'],
            'category_id' => $data['category_id'] ?? null,
            'account_id' => $data['account_id'] ?? null,
            'amount' => $data['amount'],
            'description' => $data['description'] ?? '',
            'type' => $data['type'], // 'income' or 'expense'
            'is_transfer' => $data['is_transfer'] ?? 0,
            'transfer_id' => $data['transfer_id'] ?? null,
            'date' => $data['date'] ?? date('Y-m-d')
        ]);

        if ($result && !empty($data['account_id'])) {
            $accountModel = new Account();
            $adjAmount = ($data['type'] === 'income') ? (float)$data['amount'] : -(float)$data['amount'];
            $accountModel->adjustBalance($data['account_id'], $adjAmount);
        }
        return $result;
    }

    /**
     * Delete transaction (also reverts account balance)
     */
    public function delete($id) {
        // Fetch transaction first to know the account and amount to revert
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        $tx = $stmt->fetch();

        if ($tx) {
            $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = ?");
            if ($stmt->execute([$id])) {
                if ($tx['account_id']) {
                    $accountModel = new Account();
                    $adjAmount = ($tx['type'] === 'income') ? -(float)$tx['amount'] : (float)$tx['amount'];
                    $accountModel->adjustBalance($tx['account_id'], $adjAmount);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Get transactions for export
     */
    public function getForExport($userId, $filters = []) {
        return $this->getByUserId($userId, 10000, $filters);
    }

    /**
     * Get daily totals for income and expenses over a period
     */
    public function getDailyStats($userId, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT date, 
                   SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                   SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            FROM transactions 
            WHERE user_id = ? AND is_transfer = 0 AND date BETWEEN ? AND ?
            GROUP BY date
            ORDER BY date ASC
        ");
        $stmt->execute([$userId, $startDate, $endDate]);
        return $stmt->fetchAll();
    }

    /**
     * Get total spent/earned in a specific period (excludes transfers)
     */
    public function getTotals($userId, $type, $startDate, $endDate) {
        $stmt = $this->db->prepare("
            SELECT SUM(amount) as total 
            FROM transactions 
            WHERE user_id = ? AND type = ? AND is_transfer = 0 AND date BETWEEN ? AND ?
        ");
        $stmt->execute([$userId, $type, $startDate, $endDate]);
        $row = $stmt->fetch();
        return $row['total'] ?? 0;
    }
}
