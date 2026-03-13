<?php
/**
 * Auth Controller
 */

class AuthController extends BaseController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userModel = new User();
            $user = $userModel->verify($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $this->redirect('/dashboard');
            } else {
                $error = "Invalid email or password.";
            }
        }
        
        $this->render('auth/login', [
            'title' => 'Sign In',
            'layout' => 'auth',
            'error' => $error ?? null
        ]);
    }

    public function adminLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $adminModel = new Admin();
            $admin = $adminModel->verify($email, $password);
            
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                $this->redirect('/admin');
            } else {
                $error = "Invalid admin credentials.";
            }
        }
        
        $this->render('auth/admin-login', [
            'title' => 'Admin Sign In',
            'layout' => 'auth',
            'error' => $error ?? null
        ]);
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'currency' => $_POST['currency'] ?? 'USD'
            ];
            
            // Basic validation
            if (!empty($data['email']) && !empty($data['password'])) {
                if ($userModel->create($data)) {
                    $this->redirect('/login');
                } else {
                    $error = "Failed to create account. Email might already exist.";
                }
            } else {
                $error = "Please fill in all required fields.";
            }
        }
        
        $this->render('auth/register', [
            'title' => 'Create Account',
            'layout' => 'auth',
            'error' => $error ?? null
        ]);
    }

    public function forgotPassword() {
        $this->render('auth/forgot-password', [
            'title' => 'Reset Password',
            'layout' => 'auth'
        ]);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
