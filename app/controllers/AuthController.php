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
                $_SESSION['user_profile_pic'] = $user['profile_pic'] ?? null;
                
                // Audit Log
                try {
                    (new AuditLog())->log($user['id'], 'Login', 'Successful login');
                } catch (Exception $e) {
                    // Ignore logging errors
                }
                
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
                    
                    // Audit Log
                    try {
                        (new AuditLog())->log($user['id'], '2FA Login', 'Successful 2FA verification');
                    } catch (Exception $e) {}
                    
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
                    
                    // Professional HTML Email Template
                    $message = "
                    <html>
                    <head>
                        <style>
                            .email-container { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
                            .header { background-color: #10237f; padding: 30px; text-align: center; }
                            .logo { color: #ffffff; font-size: 24px; font-weight: bold; text-decoration: none; }
                            .content { padding: 40px; background-color: #ffffff; }
                            .button { display: inline-block; padding: 14px 30px; background-color: #10237f; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
                            .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; background-color: #f8fafc; }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='header'>
                                <a href='" . BASE_URL . "' class='logo'>SpendScribe</a>
                            </div>
                            <div class='content'>
                                <h2>Welcome to SpendScribe, " . htmlspecialchars($data['name']) . "!</h2>
                                <p>Thank you for joining SpendScribe. To start tracking your finances with total privacy and control, please verify your email address by clicking the button below.</p>
                                <div style='text-align: center;'>
                                    <a href='" . $verifyLink . "' class='button'>Verify My Email Address</a>
                                </div>
                                <p style='margin-top: 30px; font-size: 14px; color: #64748b;'>If the button doesn't work, copy and paste this link into your browser:</p>
                                <p style='font-size: 12px; color: #10237f; word-break: break-all;'>" . $verifyLink . "</p>
                            </div>
                            <div class='footer'>
                                <p>&copy; " . date('Y') . " SpendScribe. Built by CreativeUtil.</p>
                                <p>You received this email because you signed up for SpendScribe.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ";

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    $headers .= "From: SpendScribe <noreply@creativeutil.com>" . "\r\n";
                    
                    @mail($data['email'], $subject, $message, $headers);
                    
                    // Audit Log
                    try {
                        $user = $userModel->findByEmail($data['email']);
                        if ($user) (new AuditLog())->log($user['id'], 'Registration', 'Account created, pending verification');
                    } catch (Exception $e) {}
                    
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
            // Audit Log (Need to find user by token before it's cleared, or just log success)
            // Since token is cleared, we'll just skip detailed log here or fetch before update
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

    public function adminLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $adminModel = new Admin();
            $admin = $adminModel->verify($email, $password);
            
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_profile_pic'] = $admin['profile_pic'] ?? null;
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

    public function forgotPassword() {
        $this->render('auth/forgot-password', [
            'title' => 'Reset Password',
            'layout' => 'auth'
        ]);
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            try {
                (new AuditLog())->log($_SESSION['user_id'], 'Logout', 'User logged out');
            } catch (Exception $e) {}
        }
        if (isset($_SESSION['admin_id'])) {
            // Log admin logout if needed
        }
        session_destroy();
        $this->redirect('/');
    }
}
