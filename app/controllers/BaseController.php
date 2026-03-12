<?php
/**
 * Base Controller for PHP MVC
 */

class BaseController {
    
    /**
     * Render a view with optional data
     */
    protected function render($view, $data = []) {
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
        require_once $viewPath;
        $content = ob_get_clean();
        
        // Include layout if specified
        if ($layout) {
            $layoutPath = APP_PATH . '/views/layouts/' . $layout . '.layout.php';
            if (file_exists($layoutPath)) {
                require_once $layoutPath;
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
     * Redirect to a URL
     */
    protected function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}
