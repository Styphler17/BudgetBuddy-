<?php
/**
 * Dashboard Controller – full CRUD for transactions, accounts, categories, goals, settings
 */
class DashboardController extends BaseController {

    private $userId;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/SpendScribe-/login');
        }
        $this->userId = $_SESSION['user_id'];
    }

    // ── Flash helpers ────────────────────────────────────────────────────────

    private function flash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash() {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    // ── Dashboard Home ───────────────────────────────────────────────────────

    public function index() {
        $transactionModel = new Transaction();
        $accountModel     = new Account();

        $today          = date('Y-m-d');
        $thisMonthStart = date('Y-m-01');

        $income   = $transactionModel->getTotals($this->userId, 'income',  $thisMonthStart, $today);
        $expense  = $transactionModel->getTotals($this->userId, 'expense', $thisMonthStart, $today);
        $accounts = $accountModel->getByUserId($this->userId);
        $recentTransactions = $transactionModel->getByUserId($this->userId, 5);

        $totalBalance = array_reduce($accounts, fn($carry, $item) => $carry + $item['balance'], 0);

        $this->render('dashboard/index', [
            'title'   => 'Dashboard',
            'layout'  => 'dashboard',
            'metrics' => [
                'income'   => $income,
                'expense'  => $expense,
                'balance'  => $totalBalance,
                'savings'  => $income - $expense,
            ],
            'recentTransactions' => $recentTransactions,
            'accounts' => $accounts,
            'flash'    => $this->getFlash(),
        ]);
    }

    // ── Transactions ─────────────────────────────────────────────────────────

    public function transactions() {
        $transactionModel = new Transaction();
        $categoryModel    = new Category();
        $transactions = $transactionModel->getByUserId($this->userId, 200);
        $categories   = $categoryModel->getByUserId($this->userId);

        $this->render('dashboard/transactions', [
            'title'        => 'Transactions',
            'layout'       => 'dashboard',
            'transactions' => $transactions,
            'categories'   => $categories,
            'flash'        => $this->getFlash(),
        ]);
    }

    public function createTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/transactions');
        }
        $model = new Transaction();
        $ok = $model->create([
            'user_id'     => $this->userId,
            'category_id' => $_POST['category_id'] ?: null,
            'amount'      => $_POST['amount'],
            'description' => $_POST['description'] ?? '',
            'type'        => $_POST['type'],
            'date'        => $_POST['date'] ?? date('Y-m-d'),
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Transaction added.' : 'Failed to add transaction.');
        $this->redirect('/SpendScribe-/transactions');
    }

    public function updateTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/transactions');
        }
        $model = new Transaction();
        $ok = $model->update($_POST['id'], $this->userId, [
            'category_id' => $_POST['category_id'] ?: null,
            'amount'      => $_POST['amount'],
            'description' => $_POST['description'] ?? '',
            'type'        => $_POST['type'],
            'date'        => $_POST['date'],
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Transaction updated.' : 'Failed to update transaction.');
        $this->redirect('/SpendScribe-/transactions');
    }

    public function deleteTransaction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/transactions');
        }
        $model = new Transaction();
        $ok = $model->delete($_POST['id'], $this->userId);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Transaction deleted.' : 'Failed to delete transaction.');
        $this->redirect('/SpendScribe-/transactions');
    }

    // ── Accounts ─────────────────────────────────────────────────────────────

    public function accounts() {
        $accountModel = new Account();
        $accounts = $accountModel->getByUserId($this->userId);

        $this->render('dashboard/accounts', [
            'title'    => 'Accounts',
            'layout'   => 'dashboard',
            'accounts' => $accounts,
            'flash'    => $this->getFlash(),
        ]);
    }

    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/accounts');
        }
        $model = new Account();
        $ok = $model->create([
            'user_id'  => $this->userId,
            'name'     => $_POST['name'],
            'type'     => $_POST['type'],
            'balance'  => $_POST['balance'] ?? 0,
            'currency' => $_POST['currency'] ?? 'USD',
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Account added.' : 'Failed to add account.');
        $this->redirect('/SpendScribe-/accounts');
    }

    public function updateAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/accounts');
        }
        $model = new Account();
        $ok = $model->update($_POST['id'], $this->userId, [
            'name'     => $_POST['name'],
            'type'     => $_POST['type'],
            'balance'  => $_POST['balance'],
            'currency' => $_POST['currency'] ?? 'USD',
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Account updated.' : 'Failed to update account.');
        $this->redirect('/SpendScribe-/accounts');
    }

    public function deleteAccount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/accounts');
        }
        $model = new Account();
        $ok = $model->delete($_POST['id'], $this->userId);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Account deleted.' : 'Failed to delete account.');
        $this->redirect('/SpendScribe-/accounts');
    }

    // ── Categories ───────────────────────────────────────────────────────────

    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getByUserId($this->userId);

        $this->render('dashboard/categories', [
            'title'      => 'Categories',
            'layout'     => 'dashboard',
            'categories' => $categories,
            'flash'      => $this->getFlash(),
        ]);
    }

    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/categories');
        }
        $model = new Category();
        $ok = $model->create([
            'user_id' => $this->userId,
            'name'    => $_POST['name'],
            'emoji'   => $_POST['emoji']  ?? '📁',
            'color'   => $_POST['color']  ?? '#3b82f6',
            'budget'  => $_POST['budget'] ?? 0,
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Category added.' : 'Failed to add category.');
        $this->redirect('/SpendScribe-/categories');
    }

    public function updateCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/categories');
        }
        $model = new Category();
        $ok = $model->update($_POST['id'], $this->userId, [
            'name'   => $_POST['name'],
            'emoji'  => $_POST['emoji']  ?? '📁',
            'color'  => $_POST['color']  ?? '#3b82f6',
            'budget' => $_POST['budget'] ?? 0,
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Category updated.' : 'Failed to update category.');
        $this->redirect('/SpendScribe-/categories');
    }

    public function deleteCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/categories');
        }
        $model = new Category();
        $ok = $model->delete($_POST['id'], $this->userId);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Category deleted.' : 'Failed to delete category.');
        $this->redirect('/SpendScribe-/categories');
    }

    // ── Goals ────────────────────────────────────────────────────────────────

    public function goals() {
        $goalModel     = new Goal();
        $categoryModel = new Category();
        $goals      = $goalModel->getByUserId($this->userId);
        $categories = $categoryModel->getByUserId($this->userId);

        $this->render('dashboard/goals', [
            'title'      => 'Goals',
            'layout'     => 'dashboard',
            'goals'      => $goals,
            'categories' => $categories,
            'flash'      => $this->getFlash(),
        ]);
    }

    public function createGoal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/goals');
        }
        $model = new Goal();
        $ok = $model->create([
            'user_id'        => $this->userId,
            'name'           => $_POST['name'],
            'target_amount'  => $_POST['target_amount'],
            'current_amount' => $_POST['current_amount'] ?? 0,
            'deadline'       => $_POST['deadline']       ?: null,
            'category_id'    => $_POST['category_id']    ?: null,
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Goal created.' : 'Failed to create goal.');
        $this->redirect('/SpendScribe-/goals');
    }

    public function updateGoal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/goals');
        }
        $model = new Goal();
        $ok = $model->update($_POST['id'], $this->userId, [
            'name'           => $_POST['name'],
            'target_amount'  => $_POST['target_amount'],
            'current_amount' => $_POST['current_amount'] ?? 0,
            'deadline'       => $_POST['deadline']       ?: null,
            'category_id'    => $_POST['category_id']    ?: null,
        ]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Goal updated.' : 'Failed to update goal.');
        $this->redirect('/SpendScribe-/goals');
    }

    public function deleteGoal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/goals');
        }
        $model = new Goal();
        $ok = $model->delete($_POST['id'], $this->userId);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Goal deleted.' : 'Failed to delete goal.');
        $this->redirect('/SpendScribe-/goals');
    }

    // ── Settings ─────────────────────────────────────────────────────────────

    public function settings() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);

        $this->render('dashboard/settings', [
            'title'  => 'Settings',
            'layout' => 'dashboard',
            'user'   => $user,
            'flash'  => $this->getFlash(),
        ]);
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/settings');
        }
        $model = new User();
        $data = [
            'name'     => trim($_POST['name']     ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
            'currency' => trim($_POST['currency'] ?? 'USD'),
        ];

        // Check email uniqueness if changed
        $current = $model->findById($this->userId);
        if ($data['email'] !== $current['email']) {
            $existing = $model->findByEmail($data['email']);
            if ($existing) {
                $this->flash('error', 'That email address is already in use.');
                $this->redirect('/SpendScribe-/settings');
            }
        }

        $ok = $model->update($this->userId, $data);
        if ($ok) {
            $_SESSION['user_name']  = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            $this->flash('success', 'Profile updated successfully.');
        } else {
            $this->flash('error', 'Failed to update profile.');
        }
        $this->redirect('/SpendScribe-/settings');
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/SpendScribe-/settings');
        }
        $model = new User();
        $user  = $model->findById($this->userId);

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword     = $_POST['new_password']     ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!password_verify($currentPassword, $user['password_hash'])) {
            $this->flash('error', 'Current password is incorrect.');
            $this->redirect('/SpendScribe-/settings');
        }
        if (strlen($newPassword) < 6) {
            $this->flash('error', 'New password must be at least 6 characters.');
            $this->redirect('/SpendScribe-/settings');
        }
        if ($newPassword !== $confirmPassword) {
            $this->flash('error', 'New passwords do not match.');
            $this->redirect('/SpendScribe-/settings');
        }

        $ok = $model->update($this->userId, ['password' => $newPassword]);
        $this->flash($ok ? 'success' : 'error', $ok ? 'Password changed successfully.' : 'Failed to change password.');
        $this->redirect('/SpendScribe-/settings');
    }

    // ── Other pages ──────────────────────────────────────────────────────────

    public function analytics() {
        $transactionModel = new Transaction();
        $this->render('dashboard/analytics', [
            'title'  => 'Analytics',
            'layout' => 'dashboard',
        ]);
    }

    public function notifications() {
        $this->render('dashboard/notifications', [
            'title'  => 'Notifications',
            'layout' => 'dashboard',
        ]);
    }
}
