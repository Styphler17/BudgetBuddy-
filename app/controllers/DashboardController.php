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
        
        // Sync currency to session for helpers
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $_SESSION['user_currency'] = $user['currency'] ?? 'USD';

        // Fetch notification count
        $_SESSION['unread_notifications'] = (new Notification())->getUnreadCount($this->userId);

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
        
        $income = $transactionModel->getTotals($this->userId, 'income', $thisMonthStart, $today, $preferredCurrency);
        $expense = $transactionModel->getTotals($this->userId, 'expense', $thisMonthStart, $today, $preferredCurrency);
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
                'savings' => $income - $expense,
                'currency' => $preferredCurrency
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

        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $preferredCurrency = $user['currency'] ?? 'USD';

        $transactions = $transactionModel->getByUserId($this->userId, 100, $filters);
        $categories = $categoryModel->getByUserId($this->userId);
        $accounts = $accountModel->getByUserId($this->userId);
        
        $this->render('dashboard/transactions', [
            'title' => 'Transactions',
            'layout' => 'dashboard',
            'transactions' => $transactions,
            'categories' => $categories,
            'accounts' => $accounts,
            'filters' => $filters,
            'currency' => $preferredCurrency
        ]);
    }

    public function transactionCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
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
            $_SESSION['success_message'] = 'Transaction recorded successfully!';

            // Budget alert check (only for expense transactions with a category)
            if (($data['type'] ?? '') === 'expense' && !empty($data['category_id'])) {
                $this->checkBudgetAlerts((int)$data['category_id']);
            }

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
        
        $redirectTo = $_POST['redirect_to'] ?? '/transactions';
        $this->redirect($redirectTo);
    }

    private function checkBudgetAlerts(int $categoryId): void {
        $categoryModel    = new Category();
        $notificationModel = new Notification();

        $categories = $categoryModel->getByUserId($this->userId);
        $category   = null;
        foreach ($categories as $c) {
            if ((int)$c['id'] === $categoryId) {
                $category = $c;
                break;
            }
        }

        if (!$category || (float)$category['budget'] <= 0) return;

        $thisMonthStart = date('Y-m-01');
        $today          = date('Y-m-d');

        $stmt = Database::getConnection()->prepare(
            "SELECT COALESCE(SUM(amount), 0) as total
             FROM transactions
             WHERE user_id = ? AND category_id = ? AND type = 'expense' AND date BETWEEN ? AND ?"
        );
        $stmt->execute([$this->userId, $categoryId, $thisMonthStart, $today]);
        $spent      = (float)($stmt->fetch()['total'] ?? 0);
        $budget     = (float)$category['budget'];
        $percentage = ($spent / $budget) * 100;

        $lastAlert = $category['last_budget_alert'] ?? 0;

        if ($percentage >= 100 && $lastAlert < 100) {
            $notificationModel->create([
                'user_id' => $this->userId,
                'title'   => 'Budget Exceeded!',
                'message' => "You have exceeded your budget for \"{$category['name']}\" this month.",
                'type'    => 'warning',
                'icon'    => 'alert-triangle'
            ]);
            $this->updateCategoryBudgetAlert($categoryId, 100);
        } elseif ($percentage >= 80 && $lastAlert < 80) {
            $notificationModel->create([
                'user_id' => $this->userId,
                'title'   => 'Budget Warning',
                'message' => "You've used " . round($percentage) . "% of your \"{$category['name']}\" budget this month.",
                'type'    => 'info',
                'icon'    => 'alert-circle'
            ]);
            $this->updateCategoryBudgetAlert($categoryId, 80);
        }
    }

    private function updateCategoryBudgetAlert(int $categoryId, int $level): void {
        $stmt = Database::getConnection()->prepare(
            "UPDATE categories SET last_budget_alert = ? WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$level, $categoryId, $this->userId]);
    }

    public function transactionExport() {
        // Audit Log
        try {
            (new AuditLog())->log($this->userId, 'Export', 'User exported transactions to CSV');
        } catch (Exception $e) {}

        // Clear any previous output to ensure headers can be sent
        if (ob_get_length()) ob_end_clean();

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

    public function transactionPrint() {
        $filters = [
            'search' => $_GET['search'] ?? null,
            'category_id' => $_GET['category_id'] ?? null,
            'account_id' => $_GET['account_id'] ?? null,
            'type' => $_GET['type'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
        ];
        
        $transactionModel = new Transaction();
        $transactions = $transactionModel->getByUserId($this->userId, 1000, $filters);
        
        $userModel = new User();
        $user = $userModel->findById($this->userId);

        $this->render('dashboard/print', [
            'title' => 'Financial Report',
            'layout' => null, // No sidebar/topbar for printing
            'transactions' => $transactions,
            'user' => $user,
            'filters' => $filters,
            'currency' => $user['currency'] ?? 'USD'
        ]);
    }

    public function transferCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
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
        $_SESSION['success_message'] = 'Transaction deleted.';
        $this->redirect('/transactions');
    }

    public function transactionUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
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
            $_SESSION['success_message'] = 'Transaction updated.';
        }
        $this->redirect('/transactions');
    }

    public function analytics() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $preferredCurrency = $user['currency'] ?? 'USD';

        $transactionModel = new Transaction();
        $categoryModel = new Category();
        
        $transactions = $transactionModel->getByUserId($this->userId, 1000);
        $categories = $categoryModel->getByUserId($this->userId);
        
        $income = $transactionModel->getTotals($this->userId, 'income', date('Y-m-01'), date('Y-m-d'), $preferredCurrency);
        $expense = $transactionModel->getTotals($this->userId, 'expense', date('Y-m-01'), date('Y-m-d'), $preferredCurrency);

        // Get daily stats for the last 30 days (Multi-currency aware)
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $dailyStats = $transactionModel->getDailyStats($this->userId, $startDate, date('Y-m-d'), $preferredCurrency);

        // Calculate spending per category (Multi-currency aware)
        $categoryData = $transactionModel->getCategorySpending($this->userId, $categories, $preferredCurrency);

        $this->render('dashboard/analytics', [
            'title' => 'Analytics',
            'layout' => 'dashboard',
            'transactions' => $transactions,
            'categories' => $categories,
            'categoryData' => $categoryData,
            'dailyStats' => $dailyStats,
            'income' => $income,
            'expense' => $expense,
            'currency' => $preferredCurrency
        ]);
    }

    public function accounts() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $preferredCurrency = $user['currency'] ?? 'USD';

        $accountModel = new Account();
        $accounts = $accountModel->getByUserId($this->userId);
        
        $this->render('dashboard/accounts', [
            'title' => 'Accounts',
            'layout' => 'dashboard',
            'accounts' => $accounts,
            'currency' => $preferredCurrency
        ]);
    }

    public function accountCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $accountModel = new Account();
            $data = [
                'user_id' => $this->userId,
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'balance' => $_POST['balance'] ?: 0,
                'currency' => $_POST['currency'] ?? 'USD'
            ];
            $accountModel->create($data);
            $_SESSION['success_message'] = 'Account created successfully!';
        }
        $this->redirect('/accounts');
    }

    public function accountDelete($id) {
        $accountModel = new Account();
        $accountModel->delete($id);
        $_SESSION['success_message'] = 'Account deleted.';
        $this->redirect('/accounts');
    }

    public function accountUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $accountModel = new Account();
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'balance' => $_POST['balance'],
                'currency' => $_POST['currency'] ?? 'USD'
            ];
            $accountModel->update($id, $data);
            $_SESSION['success_message'] = 'Account updated successfully!';
        }
        $this->redirect('/accounts');
    }

    public function settings() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $activityLogs = (new AuditLog())->getByUserId($this->userId, 10);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $action = $_POST['action'] ?? '';
            
            if ($action === 'update_profile') {
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email']
                ];

                // Handle Profile Picture Upload
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
                    $fileSize    = $_FILES['profile_pic']['size'];
                    $fileName    = $_FILES['profile_pic']['name'];

                    $allowedMimes      = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $maxSize           = 2 * 1024 * 1024; // 2MB

                    $finfo        = new finfo(FILEINFO_MIME_TYPE);
                    $detectedMime = $finfo->file($fileTmpPath);
                    $fileExt      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if ($fileSize > $maxSize) {
                        $_SESSION['error_message'] = 'Image is too large. Max 2MB allowed.';
                    } elseif (!in_array($detectedMime, $allowedMimes) || !in_array($fileExt, $allowedExtensions)) {
                        $_SESSION['error_message'] = 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.';
                    } else {
                        $uploadDir = __DIR__ . '/../../public/uploads/profile_pics/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                        // Delete old file if stored on disk
                        if (!empty($user['profile_pic']) && !str_starts_with($user['profile_pic'], 'data:')) {
                            $oldFile = $uploadDir . basename($user['profile_pic']);
                            if (file_exists($oldFile)) unlink($oldFile);
                        }

                        $newFilename = bin2hex(random_bytes(16)) . '.' . $fileExt;
                        if (move_uploaded_file($fileTmpPath, $uploadDir . $newFilename)) {
                            $data['profile_pic'] = $newFilename;
                            $_SESSION['user_profile_pic'] = $newFilename;
                        }
                    }
                }

                $userModel->update($this->userId, $data);
                $_SESSION['user_name'] = $data['name'];
                $_SESSION['success_message'] = 'Profile updated successfully!';
                
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
                    $_SESSION['success_message'] = 'Password changed successfully!';
                    
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
                    // Generate a random Base32 secret for 2FA
                    $secret = SecurityHelper::generate2FASecret();
                    $data = [
                        'two_factor_enabled' => 1,
                        'two_factor_secret' => $secret
                    ];
                    $_SESSION['success_message'] = '2FA enabled successfully!';
                    // Audit Log
                    try {
                        (new AuditLog())->log($this->userId, '2FA Enabled', 'User enabled Two-Factor Authentication');
                    } catch (Exception $e) {}
                } else {
                    $data = [
                        'two_factor_enabled' => 0,
                        'two_factor_secret' => null
                    ];
                    $_SESSION['success_message'] = '2FA disabled.';
                    // Audit Log
                    try {
                        (new AuditLog())->log($this->userId, '2FA Disabled', 'User disabled Two-Factor Authentication');
                    } catch (Exception $e) {}
                }
                $userModel->update($this->userId, $data);
                $this->redirect('/settings');
            }

            if ($action === 'update_currency') {
                $data = ['currency' => $_POST['currency']];
                $userModel->update($this->userId, $data);
                $_SESSION['success_message'] = 'Preferred currency updated!';
                
                // Immediate session sync
                $_SESSION['user_currency'] = $_POST['currency'];
                
                // Audit Log
                try {
                    (new AuditLog())->log($this->userId, 'Currency Update', 'User updated preferred currency to ' . $_POST['currency']);
                } catch (Exception $e) {}
                
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
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $preferredCurrency = $user['currency'] ?? 'USD';

        $goalModel = new Goal();
        $goals = $goalModel->getByUserId($this->userId);
        
        $this->render('dashboard/goals', [
            'title' => 'Goals',
            'layout' => 'dashboard',
            'goals' => $goals,
            'currency' => $preferredCurrency
        ]);
    }

    public function goalCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $goalModel = new Goal();
            $data = [
                'user_id' => $this->userId,
                'name' => $_POST['name'],
                'target_amount' => $_POST['target_amount'],
                'current_amount' => $_POST['current_amount'] ?: 0,
                'deadline' => $_POST['deadline'] ?: null
            ];
            $goalModel->create($data);
            $_SESSION['success_message'] = 'Savings goal created!';
        }
        $this->redirect('/goals');
    }

    public function goalDelete($id) {
        $goalModel = new Goal();
        $goalModel->delete($id);
        $_SESSION['success_message'] = 'Goal deleted.';
        $this->redirect('/goals');
    }

    public function goalUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $goalModel = new Goal();
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'target_amount' => $_POST['target_amount'],
                'current_amount' => $_POST['current_amount'],
                'deadline' => $_POST['deadline'] ?: null
            ];
            $goalModel->update($id, $data);
            $_SESSION['success_message'] = 'Goal updated.';
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
            $this->validateCsrfToken();
            $categoryModel = new Category();
            $data = [
                'user_id' => $this->userId,
                'name' => $_POST['name'],
                'emoji' => $_POST['emoji'] ?: '📊',
                'color' => $_POST['color'] ?: '#3b82f6',
                'budget' => $_POST['budget'] ?: 0
            ];
            $categoryModel->create($data);
            $_SESSION['success_message'] = 'Category created successfully!';
        }
        $this->redirect('/categories');
    }

    public function categoryDelete($id) {
        $categoryModel = new Category();
        $categoryModel->delete($id);
        $_SESSION['success_message'] = 'Category deleted.';
        $this->redirect('/categories');
    }

    public function categoryUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();
            $categoryModel = new Category();
            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'emoji' => $_POST['emoji'] ?: '📊',
                'color' => $_POST['color'] ?: '#3b82f6',
                'budget' => $_POST['budget'] ?: 0
            ];
            $categoryModel->update($id, $data);
            $_SESSION['success_message'] = 'Category updated.';
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
        $_SESSION['success_message'] = 'All notifications marked as read.';
        $this->redirect('/notifications');
    }

    public function notificationsClear() {
        $notificationModel = new Notification();
        $notificationModel->clearAll($this->userId);
        $_SESSION['success_message'] = 'Notification history cleared.';
        $this->redirect('/notifications');
    }

    public function recurring() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);
        $preferredCurrency = $user['currency'] ?? 'USD';

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
            'accounts' => $accounts,
            'currency' => $preferredCurrency
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
            $_SESSION['success_message'] = 'Recurring transaction scheduled.';
        }
        $this->redirect('/recurring');
    }

    public function recurringDelete($id) {
        $rtModel = new RecurringTransaction();
        $rtModel->delete($id);
        $_SESSION['success_message'] = 'Recurring transaction cancelled.';
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
            $_SESSION['success_message'] = 'Recurring schedule updated.';
        }
        $this->redirect('/recurring');
    }
}
