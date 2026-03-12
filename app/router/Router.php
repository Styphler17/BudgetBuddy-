<?php
/**
 * BudgetBuddy Router
 * Handles URI mapping to Controllers and Methods using a switch-case structure.
 */

class Router {
    private $basePath = '/BudgetBuddy-';

    public function handleRequest() {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Normalize the request path
        if (strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }
        $uri = strtok($uri, '?');
        $uri = trim($uri, '/');

        // Split URI into segments for complex routing
        $segments = explode('/', $uri);
        $route = $segments[0];

        switch ($route) {
            case '':
            case 'home':
                $controller = new HomeController();
                $controller->index();
                break;

            case 'login':
                $controller = new AuthController();
                $controller->login();
                break;

            case 'admin-login':
                $controller = new AuthController();
                $controller->adminLogin();
                break;

            case 'register':
                $controller = new AuthController();
                $controller->register();
                break;

            case 'forgot-password':
                $controller = new AuthController();
                $controller->forgotPassword();
                break;

            case 'contact':
                $controller = new HomeController();
                $controller->contact();
                break;

            case 'privacy-policy':
                $controller = new HomeController();
                $controller->privacyPolicy();
                break;

            case 'help':
                $controller = new HomeController();
                $controller->help();
                break;

            case 'terms':
                $controller = new HomeController();
                $controller->terms();
                break;

            case 'security':
                $controller = new HomeController();
                $controller->security();
                break;

            case 'cookies':
                $controller = new HomeController();
                $controller->cookies();
                break;

            case 'dashboard':
                $controller = new DashboardController();
                $controller->index();
                break;

            case 'transactions':
                $controller = new DashboardController();
                $action = $segments[1] ?? 'list';
                if ($action === 'create') $controller->transactionCreate();
                elseif ($action === 'delete' && isset($segments[2])) $controller->transactionDelete($segments[2]);
                else $controller->transactions();
                break;

            case 'analytics':
                $controller = new DashboardController();
                $controller->analytics();
                break;

            case 'accounts':
                $controller = new DashboardController();
                $action = $segments[1] ?? 'list';
                if ($action === 'create') $controller->accountCreate();
                elseif ($action === 'delete' && isset($segments[2])) $controller->accountDelete($segments[2]);
                else $controller->accounts();
                break;

            case 'settings':
                $controller = new DashboardController();
                $controller->settings();
                break;

            case 'goals':
                $controller = new DashboardController();
                $action = $segments[1] ?? 'list';
                if ($action === 'create') $controller->goalCreate();
                elseif ($action === 'delete' && isset($segments[2])) $controller->goalDelete($segments[2]);
                else $controller->goals();
                break;

            case 'categories':
                $controller = new DashboardController();
                $action = $segments[1] ?? 'list';
                if ($action === 'create') $controller->categoryCreate();
                elseif ($action === 'delete' && isset($segments[2])) $controller->categoryDelete($segments[2]);
                else $controller->categories();
                break;

            case 'notifications':
                $controller = new DashboardController();
                $controller->notifications();
                break;

            case 'admin':
            case 'admin-dashboard':
                $controller = new AdminDashboardController();
                $subRoute = $segments[1] ?? 'index';
                switch ($subRoute) {
                    case 'users':
                        $action = $segments[2] ?? 'list';
                        if ($action === 'edit' && isset($segments[3])) {
                            $controller->userEdit($segments[3]);
                        } else {
                            $controller->users();
                        }
                        break;
                    case 'admins':
                        $action = $segments[2] ?? 'list';
                        if ($action === 'create') {
                            $controller->adminCreate();
                        } elseif ($action === 'edit' && isset($segments[3])) {
                            $controller->adminEdit($segments[3]);
                        } else {
                            $controller->admins();
                        }
                        break;
                    case 'blog':
                        $action = $segments[2] ?? 'list';
                        if ($action === 'create') {
                            $controller->blogCreate();
                        } elseif ($action === 'edit' && isset($segments[3])) {
                            $controller->blogEdit($segments[3]);
                        } elseif ($action === 'delete' && isset($segments[3])) {
                            $controller->blogDelete($segments[3]);
                        } elseif ($action === 'upload') {
                            $controller->blogUpload();
                        } else {
                            $controller->blog();
                        }
                        break;
                    case 'profile':
                        $controller->profile();
                        break;
                    case 'logs':
                        $controller->logs();
                        break;
                    default:
                        $controller->index();
                        break;
                }
                break;

            case 'logout':
                $controller = new AuthController();
                $controller->logout();
                break;

            case 'blog':
                $controller = new BlogController();
                if (isset($segments[1]) && !empty($segments[1])) {
                    // It's a single post view: blog/slug-name
                    $controller->view($segments[1]);
                } else {
                    // It's the listing page: blog/
                    $controller->index();
                }
                break;

            default:
                // Check if the route is a blog slug
                if (!empty($route)) {
                    $blogModel = new Blog();
                    $post = $blogModel->findBySlug($route);
                    if ($post) {
                        $controller = new BlogController();
                        $controller->view($route);
                        break;
                    }
                }

                // Handle 404 or Fallback
                $controller = new HomeController();
                $controller->index();
                break;
        }
    }
}
