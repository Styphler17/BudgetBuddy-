<?php
/**
 * API Controller
 */

class ApiController extends BaseController {
    
    private $userId;

    public function __construct() {
        // Simple API Auth - for now using session, eventually could use Bearer Tokens
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        $this->userId = $_SESSION['user_id'];
    }

    /**
     * Helper to send JSON response
     */
    protected function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * GET /api/metrics
     */
    public function getMetrics() {
        $transactionModel = new Transaction();
        $accountModel = new Account();
        
        $today = date('Y-m-d');
        $thisMonthStart = date('Y-m-01');
        
        $income = $transactionModel->getTotals($this->userId, 'income', $thisMonthStart, $today);
        $expense = $transactionModel->getTotals($this->userId, 'expense', $thisMonthStart, $today);
        $accounts = $accountModel->getByUserId($this->userId);
        
        $totalBalance = array_reduce($accounts, function($carry, $item) {
            return $carry + $item['balance'];
        }, 0);

        $this->jsonResponse([
            'income' => (float)$income,
            'expense' => (float)$expense,
            'balance' => (float)$totalBalance,
            'savings' => (float)($income - $expense)
        ]);
    }

    /**
     * GET /api/transactions
     */
    public function getTransactions() {
        $transactionModel = new Transaction();
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
        $transactions = $transactionModel->getByUserId($this->userId, $limit);
        
        $this->jsonResponse([
            'count' => count($transactions),
            'transactions' => $transactions
        ]);
    }

    /**
     * GET /api/activity
     */
    public function getActivity() {
        $auditLog = new AuditLog();
        $logs = $auditLog->getByUserId($this->userId);
        
        $this->jsonResponse([
            'logs' => $logs
        ]);
    }
}
