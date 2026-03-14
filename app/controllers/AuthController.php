<?php
/**
 * Auth Controller
 */

class AuthController extends BaseController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $userModel = new User();
            $result = $userModel->verify($email, $password);
            
            if ($result && $result['status'] === 'success') {
                $user = $result['user'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $this->redirect('/dashboard');
            } elseif ($result && $result['status'] === 'require_2fa') {
                $_SESSION['temp_user_id'] = $result['user']['id'];
                $this->redirect('/login/2fa');
            } elseif ($result && $result['status'] === 'unverified') {
                $error = "Please verify your email address before logging in. Check your inbox for the verification link.";
            } elseif ($result && $result['status'] === 'inactive') {
                $error = "Your account is currently inactive. Please contact support.";
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

    public function verify2FA() {
        if (!isset($_SESSION['temp_user_id'])) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $userModel = new User();
            $user = $userModel->findById($_SESSION['temp_user_id']);

            // Simplified TOTP verification placeholder
            if ($user && $user['two_factor_enabled']) {
                if (strlen($code) === 6) { 
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    unset($_SESSION['temp_user_id']);
                    $this->redirect('/dashboard');
                } else {
                    $error = "Invalid 2FA code.";
                }
            }
        }

        $this->render('auth/2fa', [
            'title' => 'Two-Factor Authentication',
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
                $token = $userModel->create($data);
                if ($token) {
                    // Send verification email
                    $verifyLink = BASE_URL . "/verify-email?token=" . $token;
                    $subject = "Verify your SpendScribe account";
                    $message = "Hello " . $data['name'] . ",\n\nPlease click the link below to verify your email address:\n" . $verifyLink;
                    
                    @mail($data['email'], $subject, $message);
                    
                    $this->render('auth/register-success', [
                        'title' => 'Registration Successful',
                        'layout' => 'auth',
                        'email' => $data['email']
                    ]);
                    return;
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

    public function verifyEmail() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $this->redirect('/login');
        }

        $userModel = new User();
        if ($userModel->verifyEmail($token)) {
            $success = "Email verified successfully! You can now log in.";
        } else {
            $error = "Invalid or expired verification token.";
        }

        $this->render('auth/login', [
            'title' => 'Sign In',
            'layout' => 'auth',
            'success' => $success ?? null,
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
