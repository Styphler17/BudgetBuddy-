<?php
/**
 * Dashboard Controller
 */

class DashboardController extends BaseController {
    
    private $userId;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->userId = $_SESSION['user_id'];
        $this->processRecurring();
    }

    private function processRecurring() {
        $rtModel = new RecurringTransaction();
        $txModel = new Transaction();
        $due = $rtModel->getDue($this->userId);

        foreach ($due as $item) {
            // Create the transaction
            $txModel->create([
                'user_id' => $this->userId,
                'account_id' => $item['account_id'],
                'category_id' => $item['category_id'],
                'amount' => $item['amount'],
                'description' => $item['description'] . " (Recurring)",
                'type' => $item['type'],
                'date' => date('Y-m-d')
            ]);

            // Update next run date
            $nextRun = RecurringTransaction::calculateNextRun($item['frequency'], date('Y-m-d'));
            $rtModel->updateRunDates($item['id'], date('Y-m-d'), $nextRun);
        }
    }

    public function index() {
        $transactionModel = new Transaction();
        $accountModel = new Account();
        $categoryModel = new Category();
        
        $today = date('Y-m-d');
        $thisMonthStart = date('Y-m-01');
        
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $preferredCurrency = $user['currency'] ?? 'USD';
        
        $income = $transactionModel->getTotals($this->userId, 'income', $thisMonthStart, $today);
        $expense = $transactionModel->getTotals($this->userId, 'expense', $thisMonthStart, $today);
        $accounts = $accountModel->getByUserId($this->userId);
        $recentTransactions = $transactionModel->getByUserId($this->userId, 5);
        $categories = $categoryModel->getByUserId($this->userId);
        $goals = (new Goal())->getByUserId($this->userId);

        // Multi-currency Total Balance Calculation
        $currencyService = new CurrencyService();
        $totalBalance = 0;
        foreach ($accounts as $account) {
            $balance = (float)$account['balance'];
            $accountCurrency = $account['currency'] ?? 'USD';
            
            if ($accountCurrency !== $preferredCurrency) {
                $totalBalance += $currencyService->convert($balance, $accountCurrency, $preferredCurrency);
            } else {
                $totalBalance += $balance;
            }
        }

        // Calculate spending per category for the budget progress section
        $budgetProgress = [];
        foreach ($categories as $category) {
            if ($category['budget'] > 0) {
                $stmt = Database::getConnection()->prepare("
                    SELECT SUM(amount) as total 
                    FROM transactions 
                    WHERE user_id = ? AND category_id = ? AND type = 'expense' AND date BETWEEN ? AND ?
                ");
                $stmt->execute([$this->userId, $category['id'], $thisMonthStart, $today]);
                $spent = $stmt->fetch()['total'] ?? 0;
                
                $budgetProgress[] = [
                    'name' => $category['name'],
                    'spent' => $spent,
                    'limit' => $category['budget'],
                    'percentage' => ($spent / $category['budget']) * 100,
                    'color' => $category['color']
                ];
            }
        }

        $this->render('dashboard/index', [
            'title' => 'Dashboard',
            'layout' => 'dashboard',
            'metrics' => [
                'income' => $income,
                'expense' => $expense,
                'balance' => $totalBalance,
                'savings' => $income - $expense
            ],
            'recentTransactions' => $recentTransactions,
            'accounts' => $accounts,
            'budgetProgress' => $budgetProgress,
            'goals' => $goals
        ]);
    }

    public function transactions() {
        $transactionModel = new Transaction();
        $categoryModel = new Category();
        $accountModel = new Account();

        $filters = [
            'search' => $_GET['search'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'account_id' => $_GET['account_id'] ?? null,
            'type' => $_GET['type'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
        ];

        $transactions = $transactionModel->getByUserId($this->userId, 100, $filters);
        $categories = $categoryModel->getByUserId($this->userId);
        $accounts = $accountModel->getByUserId($this->userId);
        
        $this->render('dashboard/transactions', [
            'title' => 'Transactions',
            'layout' => 'dashboard',
            'transactions' => $transactions,
            'categories' => $categories,
            'accounts' => $accounts,
            'filters' => $filters
        ]);
    }

    public function transactionCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionModel = new Transaction();
            $data = [
                'user_id' => $this->userId,
                'account_id' => $_POST['account_id'] ?: null,
                'category_id' => $_POST['category_id'] ?: null,
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'type' => $_POST['type'],
                'date' => $_POST['date'] ?: date('Y-m-d')
            ];
            $transactionModel->create($data);

            // Savings Milestones Logic
            $goalModel = new Goal();
            $notificationModel = new Notification();
            $goals = $goalModel->getByUserId($this->userId);

            foreach ($goals as $goal) {
                if ($goal['target_amount'] <= 0) continue;
                
                $percent = ($goal['current_amount'] / $goal['target_amount']) * 100;
                $lastMilestone = $goal['last_milestone'] ?? 0;

                if ($percent >= 100 && $lastMilestone < 100) {
                    $notificationModel->create([
                        'user_id' => $this->userId,
                        'title' => 'Goal Achieved! 🏆',
                        'message' => "Congratulations! You've reached 100% of your goal: " . $goal['name'],
                        'type' => 'success',
                        'icon' => 'trophy'
                    ]);
                    $goalModel->updateMilestone($goal['id'], 100);
                } elseif ($percent >= 50 && $lastMilestone < 50) {
                    $notificationModel->create([
                        'user_id' => $this->userId,
                        'title' => 'Halfway There! ✨',
                        'message' => "Great job! You've reached 50% of your goal: " . $goal['name'],
                        'type' => 'info',
                        'icon' => 'star'
                    ]);
                    $goalModel->updateMilestone($goal['id'], 50);
                }
            }
        }
        $this->redirect('/transactions');
    }

    public function transactionExport() {
        $filters = [
            'search' => $_GET['search'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'account_id' => $_GET['account_id'] ?? null,
            'type' => $_GET['type'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
        ];
        
        $transactionModel = new Transaction();
        $transactions = $transactionModel->getForExport($this->userId, $filters);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transactions_export_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Description', 'Category', 'Account', 'Type', 'Amount']);

        foreach ($transactions as $tx) {
            fputcsv($output, [
                $tx['date'], 
                $tx['description'], 
                $tx['category_name'] ?? 'N/A', 
                $tx['account_name'] ?? 'N/A', 
                ucfirst($tx['type']), 
                number_format($tx['amount'], 2)
            ]);
        }
        fclose($output);
        exit;
    }

    public function transferCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fromId = $_POST['from_account_id'];
            $toId = $_POST['to_account_id'];
            $amount = $_POST['amount'];
            $date = $_POST['date'] ?: date('Y-m-d');
            $transferId = uniqid('tr_');

            $txModel = new Transaction();
            
            // Withdrawal from source
            $txModel->create([
                'user_id' => $this->userId,
                'account_id' => $fromId,
                'amount' => $amount,
                'description' => $_POST['description'] ?: "Transfer to account #$toId",
                'type' => 'expense',
                'is_transfer' => 1,
                'transfer_id' => $transferId,
                'date' => $date
            ]);

            // Deposit into destination
            $txModel->create([
                'user_id' => $this->userId,
                'account_id' => $toId,
                'amount' => $amount,
                'description' => $_POST['description'] ?: "Transfer from account #$fromId",
                'type' => 'income',
                'is_transfer' => 1,
                'transfer_id' => $transferId,
                'date' => $date
            ]);
        }
        $this->redirect('/accounts');
    }

    public function transactionDelete($id) {
        $transactionModel = new Transaction();
        $transactionModel->delete($id);
        $this->redirect('/transactions');
    }

    public function transactionUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionModel = new Transaction();
            $id = $_POST['id'];
            $data = [
                'account_id' => $_POST['account_id'] ?: null,
                'category_id' => $_POST['category_id'] ?: null,
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'type' => $_POST['type'],
                'date' => $_POST['date'] ?: date('Y-m-d')
            ];
            $transactionModel->update($id, $data);
        }
        $this->redirect('/transactions');
    }

    public function analytics() {
        $transactionModel = new Transaction();
        $categoryModel = new Category();
        
        $transactions = $transactionModel->getByUserId($this->userId, 1000);
        $categories = $categoryModel->getByUserId($this->userId);
        
        $income = $transactionModel->getTotals($this->userId, 'income', date('Y-m-01'), date('Y-m-d'));
        $expense = $transactionModel->getTotals($this->userId, 'expense', date('Y-m-01'), date('Y-m-d'));

        // Get daily stats for the last 30 days
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $dailyStats = $transactionModel->getDailyStats($this->userId, $startDate, date('Y-m-d'));

        // Calculate spending per category
        $categoryData = [];
        foreach ($categories as $cat) {
            $stmt = Database::getConnection()->prepare("SELECT SUM(amount) as total FROM transactions WHERE user_id = ? AND category_id = ? AND type = 'expense'");
            $stmt->execute([$this->userId, $cat['id']]);
            $total = $stmt->fetch()['total'] ?? 0;
            $categoryData[] = [
                'name' => $cat['name'],
                'spent' => (float)$total
            ];
        }

        $this->render('dashboard/analytics', [
            'title' => 'Analytics',
            'layout' => 'dashboard',
            'transactions' => $transactions,
            'categories' => $categories,
            'categoryData' => $categoryData,
            'dailyStats' => $dailyStats,
            'income' => $income,
            'expense' => $expense
        ]);
    }

    public function accounts() {
        $accountModel = new Account();
        $accounts = $accountModel->getByUserId($this->userId);
        
        $this->render('dashboard/accounts', [
            'title' => 'Accounts',
            'layout' => 'dashboard',
            'accounts' => $accounts
        ]);
    }

    public function accountCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountModel = new Account();
            $data = [
                'user_id' => $this->userId,
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'balance' => $_POST['balance'] ?: 0,
                'currency' => 'USD'
            ];
            $accountModel->create($data);
        }
        $this->redirect('/accounts');
    }

    public function accountDelete($id) {
        $accountModel = new Account();
        $accountModel->delete($id);
        $this->redirect('/accounts');
    }

    public function accountUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountModel = new Account();
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'balance' => $_POST['balance']
            ];
            $accountModel->update($id, $data);
        }
        $this->redirect('/accounts');
    }

    public function settings() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $activityLogs = (new AuditLog())->getByUserId($this->userId, 10);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'update_profile') {
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email']
                ];
                $userModel->update($this->userId, $data);
                $_SESSION['user_name'] = $data['name'];
                
                // Audit Log
                try {
                    (new AuditLog())->log($this->userId, 'Profile Update', 'User updated display name or email');
                } catch (Exception $e) {}
                
                $this->redirect('/settings');
            }
            
            if ($action === 'update_password') {
                $password = $_POST['password'];
                $confirm = $_POST['confirm_password'];
                if ($password === $confirm && !empty($password)) {
                    $data = ['password_hash' => password_hash($password, PASSWORD_DEFAULT)];
                    $userModel->update($this->userId, $data);
                    
                    // Audit Log
                    try {
                        (new AuditLog())->log($this->userId, 'Password Change', 'User changed their password');
                    } catch (Exception $e) {}
                }
                $this->redirect('/settings');
            }

            if ($action === 'update_2fa') {
                $enable = isset($_POST['enable_2fa']);
                if ($enable) {
                    // Generate a random secret for 2FA if not already set
                    $secret = bin2hex(random_bytes(16));
                    $data = [
                        'two_factor_enabled' => 1,
                        'two_factor_secret' => $secret
                    ];
                    // Audit Log
                    try {
                        (new AuditLog())->log($this->userId, '2FA Enabled', 'User enabled Two-Factor Authentication');
                    } catch (Exception $e) {}
                } else {
                    $data = [
                        'two_factor_enabled' => 0,
                        'two_factor_secret' => null
                    ];
                    // Audit Log
                    try {
                        (new AuditLog())->log($this->userId, '2FA Disabled', 'User disabled Two-Factor Authentication');
                    } catch (Exception $e) {}
                }
                $userModel->update($this->userId, $data);
                $this->redirect('/settings');
            }

            if ($action === 'delete_account') {
                // Audit Log (Log before deletion)
                try {
                    (new AuditLog())->log($this->userId, 'Account Deletion', 'User deleted their account');
                } catch (Exception $e) {}
                
                $userModel->delete($this->userId);
                session_destroy();
                $this->redirect('/');
            }
        }
        
        $this->render('dashboard/settings', [
            'title' => 'Settings',
            'layout' => 'dashboard',
            'user' => $user,
            'activityLogs' => $activityLogs
        ]);
    }

    public function goals() {
        $goalModel = new Goal();
        $goals = $goalModel->getByUserId($this->userId);
        
        $this->render('dashboard/goals', [
            'title' => 'Goals',
            'layout' => 'dashboard',
            'goals' => $goals
        ]);
    }

    public function goalCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goalModel = new Goal();
            $data = [
                'user_id' => $this->userId,
                'name' => $_POST['name'],
                'target_amount' => $_POST['target_amount'],
                'current_amount' => $_POST['current_amount'] ?: 0,
                'deadline' => $_POST['deadline'] ?: null
            ];
            $goalModel->create($data);
        }
        $this->redirect('/goals');
    }

    public function goalDelete($id) {
        $goalModel = new Goal();
        $goalModel->delete($id);
        $this->redirect('/goals');
    }

    public function goalUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $goalModel = new Goal();
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'target_amount' => $_POST['target_amount'],
                'current_amount' => $_POST['current_amount'],
                'deadline' => $_POST['deadline'] ?: null
            ];
            $goalModel->update($id, $data);
        }
        $this->redirect('/goals');
    }

    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getByUserId($this->userId);
        
        $this->render('dashboard/categories', [
            'title' => 'Categories',
            'layout' => 'dashboard',
            'categories' => $categories
        ]);
    }

    public function categoryCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = new Category();
            $data = [
                'user_id' => $this->userId,
                'name' => $_POST['name'],
                'emoji' => $_POST['emoji'] ?: '📊',
                'color' => $_POST['color'] ?: '#3b82f6',
                'budget' => $_POST['budget'] ?: 0
            ];
            $categoryModel->create($data);
        }
        $this->redirect('/categories');
    }

    public function categoryDelete($id) {
        $categoryModel = new Category();
        $categoryModel->delete($id);
        $this->redirect('/categories');
    }

    public function categoryUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = new Category();
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'emoji' => $_POST['emoji'] ?: '📊',
                'color' => $_POST['color'] ?: '#3b82f6',
                'budget' => $_POST['budget'] ?: 0
            ];
            $categoryModel->update($id, $data);
        }
        $this->redirect('/categories');
    }

    public function notifications() {
        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUserId($this->userId);

        $this->render('dashboard/notifications', [
            'title' => 'Notifications',
            'layout' => 'dashboard',
            'notifications' => $notifications
        ]);
    }

    public function notificationsMarkRead() {
        $notificationModel = new Notification();
        $notificationModel->markAllAsRead($this->userId);
        $this->redirect('/notifications');
    }

    public function notificationsClear() {
        $notificationModel = new Notification();
        $notificationModel->clearAll($this->userId);
        $this->redirect('/notifications');
    }

    public function recurring() {
        $rtModel = new RecurringTransaction();
        $categoryModel = new Category();
        $accountModel = new Account();

        $recurring = $rtModel->getByUserId($this->userId);
        $categories = $categoryModel->getByUserId($this->userId);
        $accounts = $accountModel->getByUserId($this->userId);

        $this->render('dashboard/recurring', [
            'title' => 'Recurring Transactions',
            'layout' => 'dashboard',
            'recurring' => $recurring,
            'categories' => $categories,
            'accounts' => $accounts
        ]);
    }

    public function recurringCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rtModel = new RecurringTransaction();
            $data = [
                'user_id' => $this->userId,
                'account_id' => $_POST['account_id'],
                'category_id' => $_POST['category_id'] ?: null,
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'type' => $_POST['type'],
                'frequency' => $_POST['frequency'],
                'start_date' => $_POST['start_date'] ?: date('Y-m-d')
            ];
            $rtModel->create($data);
        }
        $this->redirect('/recurring');
    }

    public function recurringDelete($id) {
        $rtModel = new RecurringTransaction();
        $rtModel->delete($id);
        $this->redirect('/recurring');
    }

    public function recurringUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rtModel = new RecurringTransaction();
            $id = $_POST['id'];
            $data = [
                'account_id' => $_POST['account_id'],
                'category_id' => $_POST['category_id'] ?: null,
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'type' => $_POST['type'],
                'frequency' => $_POST['frequency'],
                'start_date' => $_POST['start_date']
            ];
            $rtModel->update($id, $data);
        }
        $this->redirect('/recurring');
    }
}
