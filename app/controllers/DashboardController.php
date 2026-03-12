<?php
/**
 * Dashboard Controller
 */

class DashboardController extends BaseController {
    
    private $userId;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/BudgetBuddy-/login');
        }
        $this->userId = $_SESSION['user_id'];
    }

    public function index() {
        $transactionModel = new Transaction();
        $accountModel = new Account();
        $categoryModel = new Category();
        
        $today = date('Y-m-d');
        $thisMonthStart = date('Y-m-01');
        
        $income = $transactionModel->getTotals($this->userId, 'income', $thisMonthStart, $today);
        $expense = $transactionModel->getTotals($this->userId, 'expense', $thisMonthStart, $today);
        $accounts = $accountModel->getByUserId($this->userId);
        $recentTransactions = $transactionModel->getByUserId($this->userId, 5);
        $categories = $categoryModel->getByUserId($this->userId);

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
        
        $totalBalance = array_reduce($accounts, function($carry, $item) {
            return $carry + $item['balance'];
        }, 0);

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
            'budgetProgress' => $budgetProgress
        ]);
    }

    public function transactions() {
        $transactionModel = new Transaction();
        $transactions = $transactionModel->getByUserId($this->userId, 100);
        
        $this->render('dashboard/transactions', [
            'title' => 'Transactions',
            'layout' => 'dashboard',
            'transactions' => $transactions
        ]);
    }

    public function transactionCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionModel = new Transaction();
            $data = [
                'user_id' => $this->userId,
                'category_id' => $_POST['category_id'] ?: null,
                'amount' => $_POST['amount'],
                'description' => $_POST['description'],
                'type' => $_POST['type'],
                'date' => $_POST['date'] ?: date('Y-m-d')
            ];
            $transactionModel->create($data);
        }
        $this->redirect('/BudgetBuddy-/transactions');
    }

    public function transactionDelete($id) {
        $transactionModel = new Transaction();
        $transactionModel->delete($id);
        $this->redirect('/BudgetBuddy-/transactions');
    }

    public function analytics() {
        $transactionModel = new Transaction();
        $categoryModel = new Category();
        
        $transactions = $transactionModel->getByUserId($this->userId, 1000);
        $categories = $categoryModel->getByUserId($this->userId);
        
        $income = $transactionModel->getTotals($this->userId, 'income', date('Y-m-01'), date('Y-m-d'));
        $expense = $transactionModel->getTotals($this->userId, 'expense', date('Y-m-01'), date('Y-m-d'));

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
        $this->redirect('/BudgetBuddy-/accounts');
    }

    public function accountDelete($id) {
        $accountModel = new Account();
        $accountModel->delete($id);
        $this->redirect('/BudgetBuddy-/accounts');
    }

    public function settings() {
        $userModel = new User();
        $user = $userModel->findById($this->userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'update_profile') {
                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email']
                ];
                $userModel->update($this->userId, $data);
                $_SESSION['user_name'] = $data['name'];
                $this->redirect('/BudgetBuddy-/settings');
            }
            
            if ($action === 'update_password') {
                $password = $_POST['password'];
                $confirm = $_POST['confirm_password'];
                if ($password === $confirm && !empty($password)) {
                    $data = ['password_hash' => password_hash($password, PASSWORD_DEFAULT)];
                    $userModel->update($this->userId, $data);
                }
                $this->redirect('/BudgetBuddy-/settings');
            }
        }
        
        $this->render('dashboard/settings', [
            'title' => 'Settings',
            'layout' => 'dashboard',
            'user' => $user
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
        $this->redirect('/BudgetBuddy-/goals');
    }

    public function goalDelete($id) {
        $goalModel = new Goal();
        $goalModel->delete($id);
        $this->redirect('/BudgetBuddy-/goals');
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
        $this->redirect('/BudgetBuddy-/categories');
    }

    public function categoryDelete($id) {
        $categoryModel = new Category();
        $categoryModel->delete($id);
        $this->redirect('/BudgetBuddy-/categories');
    }

    public function notifications() {
        // Notifications might need a model too, or fetch from system...
        $this->render('dashboard/notifications', [
            'title' => 'Notifications',
            'layout' => 'dashboard'
        ]);
    }
}
