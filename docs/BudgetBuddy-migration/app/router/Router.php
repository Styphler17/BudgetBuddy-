<?php
/**
 * SpendScribe Router – handles URI mapping including CRUD sub-routes
 */
class Router {
    private $basePath = '/SpendScribe-';

    public function handleRequest() {
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }
        $uri      = strtok($uri, '?');
        $uri      = trim($uri, '/');
        $segments = explode('/', $uri);
        $route    = $segments[0];
        $sub      = $segments[1] ?? '';

        switch ($route) {

            // ── Public pages ─────────────────────────────────────────────
            case '':
            case 'home':
                (new HomeController())->index();
                break;

            case 'contact':
                (new HomeController())->contact();
                break;

            // ── Auth ─────────────────────────────────────────────────────
            case 'login':
                (new AuthController())->login();
                break;

            case 'register':
                (new AuthController())->register();
                break;

            case 'forgot-password':
                (new AuthController())->forgotPassword();
                break;

            case 'logout':
                (new AuthController())->logout();
                break;

            // ── Dashboard ────────────────────────────────────────────────
            case 'dashboard':
                (new DashboardController())->index();
                break;

            // ── Transactions ─────────────────────────────────────────────
            case 'transactions':
                $ctrl = new DashboardController();
                switch ($sub) {
                    case 'create': $ctrl->createTransaction(); break;
                    case 'update': $ctrl->updateTransaction(); break;
                    case 'delete': $ctrl->deleteTransaction(); break;
                    default:       $ctrl->transactions();      break;
                }
                break;

            // ── Accounts ─────────────────────────────────────────────────
            case 'accounts':
                $ctrl = new DashboardController();
                switch ($sub) {
                    case 'create': $ctrl->createAccount(); break;
                    case 'update': $ctrl->updateAccount(); break;
                    case 'delete': $ctrl->deleteAccount(); break;
                    default:       $ctrl->accounts();      break;
                }
                break;

            // ── Categories ───────────────────────────────────────────────
            case 'categories':
                $ctrl = new DashboardController();
                switch ($sub) {
                    case 'create': $ctrl->createCategory(); break;
                    case 'update': $ctrl->updateCategory(); break;
                    case 'delete': $ctrl->deleteCategory(); break;
                    default:       $ctrl->categories();     break;
                }
                break;

            // ── Goals ────────────────────────────────────────────────────
            case 'goals':
                $ctrl = new DashboardController();
                switch ($sub) {
                    case 'create': $ctrl->createGoal(); break;
                    case 'update': $ctrl->updateGoal(); break;
                    case 'delete': $ctrl->deleteGoal(); break;
                    default:       $ctrl->goals();      break;
                }
                break;

            // ── Settings ─────────────────────────────────────────────────
            case 'settings':
                $ctrl = new DashboardController();
                switch ($sub) {
                    case 'profile':  $ctrl->updateProfile();  break;
                    case 'password': $ctrl->updatePassword(); break;
                    default:         $ctrl->settings();       break;
                }
                break;

            // ── Other dashboard pages ─────────────────────────────────────
            case 'analytics':
                (new DashboardController())->analytics();
                break;

            case 'notifications':
                (new DashboardController())->notifications();
                break;

            // ── Admin ─────────────────────────────────────────────────────
            case 'admin':
            case 'admin-dashboard':
                $ctrl = new AdminDashboardController();
                switch ($sub) {
                    case 'users': $ctrl->users(); break;
                    case 'blog':  $ctrl->blog();  break;
                    default:      $ctrl->index(); break;
                }
                break;

            // ── Blog ──────────────────────────────────────────────────────
            case 'blog':
                $ctrl = new BlogController();
                if (!empty($sub)) {
                    $ctrl->view($sub);
                } else {
                    $ctrl->index();
                }
                break;

            // ── 404 ──────────────────────────────────────────────────────
            default:
                http_response_code(404);
                (new HomeController())->index();
                break;
        }
    }
}
