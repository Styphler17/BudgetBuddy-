<?php
/**
 * Base Controller for PHP MVC
 */

class BaseController {
    
    /**
     * Render a view with optional data
     */
    protected function render($view, $data = []) {
        // Session timeout: 2 hours of inactivity
        $this->checkSessionTimeout();

        // Automatically sync session data from DB if user is logged in
        $this->syncSessionWithDatabase();

        // Extract layout name from data or default to 'main'
        $layout = $data['layout'] ?? 'main';
        
        // Extract data to make variables available in the view
        extract($data);
        
        // Define path to view file
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            die("View $view not found at $viewPath");
        }
        
        // Start output buffering for the view content
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Capture any modal content if defined in the view
        $modal_content = '';
        if (isset($GLOBALS['view_modal_content'])) {
            $modal_content = $GLOBALS['view_modal_content'];
            unset($GLOBALS['view_modal_content']);
        }
        
        // Include layout if specified
        if ($layout) {
            $layoutPath = APP_PATH . '/views/layouts/' . $layout . '.layout.php';
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Helper for JSON responses (API)
     */
    protected function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a URL (automatically prepends BASE_URL for relative paths)
     */
    protected function redirect($url) {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . '/' . ltrim($url, '/');
        }
        header("Location: " . $url);
        exit;
    }

    /**
     * Synchronize session data with database
     */
    private function syncSessionWithDatabase() {
        if (isset($_SESSION['user_id'])) {
            $userModel = new User();
            $user = $userModel->findById($_SESSION['user_id']);
            
            if ($user) {
                // Keep these critical session variables in sync with DB
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_profile_pic'] = $user['profile_pic'];
                
                // If account was deactivated or unverified since last check
                if (!$user['is_active'] || (defined('REQUIRE_VERIFICATION') && !$user['email_verified'])) {
                    session_destroy();
                    $this->redirect('/login');
                }
            } else {
                // User no longer exists
                session_destroy();
                $this->redirect('/login');
            }
        }
    }

    /**
     * Session inactivity timeout (2 hours)
     */
    private function checkSessionTimeout(): void {
        $timeout = 7200; // 2 hours
        if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
                $userId = $_SESSION['user_id'] ?? null;
                if ($userId) {
                    try {
                        (new AuditLog())->log($userId, 'Session Timeout', 'Session expired due to inactivity');
                    } catch (Exception $e) {}
                }
                session_destroy();
                $this->redirect('/login');
            }
            $_SESSION['last_activity'] = time();
        }
    }

    /**
     * CSRF Validation
     */
    protected function validateCsrfToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                http_response_code(403);
                die('CSRF token validation failed.');
            }
        }
    }

    /**
     * CSRF Field for forms
     */
    public static function csrfField() {
        return '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">';
    }
}
