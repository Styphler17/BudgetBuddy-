<?php
/**
 * Auth Controller
 */

class AuthController extends BaseController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();

            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Rate limiting: 5 attempts per 15 min per IP
            if (RateLimiter::isLockedOut('login', $email)) {
                $mins  = ceil(RateLimiter::retryAfter('login', $email) / 60);
                $error = "Too many failed attempts. Please wait {$mins} minute(s) before trying again.";
                $this->render('auth/login', ['title' => 'Sign In', 'layout' => 'auth', 'error' => $error]);
                return;
            }

            $userModel = new User();
            $result    = $userModel->verify($email, $password);

            if ($result && $result['status'] === 'success') {
                RateLimiter::clear('login', $email);
                $user = $result['user'];

                session_regenerate_id(true);
                $_SESSION['user_id']          = $user['id'];
                $_SESSION['user_name']        = $user['name'];
                $_SESSION['user_email']       = $user['email'];
                $_SESSION['user_profile_pic'] = $user['profile_pic'] ?? null;
                $_SESSION['last_activity']    = time();

                try {
                    (new AuditLog())->log($user['id'], 'Login', 'Successful login');
                } catch (Exception $e) {}

                $this->redirect('/dashboard');

            } elseif ($result && $result['status'] === 'require_2fa') {
                RateLimiter::clear('login', $email);
                session_regenerate_id(true);
                $_SESSION['temp_user_id']  = $result['user']['id'];
                $_SESSION['last_activity'] = time();
                $this->redirect('/login/2fa');

            } elseif ($result && $result['status'] === 'unverified') {
                $error = "Please verify your email address before logging in.";

            } elseif ($result && $result['status'] === 'inactive') {
                $error = "Your account is currently inactive. Please contact support.";

            } else {
                RateLimiter::hit('login', $email);
                $error = "Invalid email or password.";
            }
        }

        $this->render('auth/login', [
            'title'  => 'Sign In',
            'layout' => 'auth',
            'error'  => $error ?? null
        ]);
    }

    public function verify2FA() {
        if (!isset($_SESSION['temp_user_id'])) {
            $this->redirect('/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();

            $userId = (int)$_SESSION['temp_user_id'];

            if (RateLimiter::isLockedOut('2fa', (string)$userId)) {
                $mins  = ceil(RateLimiter::retryAfter('2fa', (string)$userId) / 60);
                $error = "Too many failed attempts. Please wait {$mins} minute(s).";
                $this->render('auth/2fa', ['title' => 'Two-Factor Authentication', 'layout' => 'auth', 'error' => $error]);
                return;
            }

            $code      = $_POST['code'] ?? '';
            $userModel = new User();
            $user      = $userModel->findById($userId);

            if ($user && $user['two_factor_enabled']) {
                if (SecurityHelper::verifyTOTP($user['two_factor_secret'], $code)) {
                    RateLimiter::clear('2fa', (string)$userId);
                    session_regenerate_id(true);
                    $_SESSION['user_id']          = $user['id'];
                    $_SESSION['user_name']        = $user['name'];
                    $_SESSION['user_email']       = $user['email'];
                    $_SESSION['last_activity']    = time();
                    unset($_SESSION['temp_user_id']);

                    try {
                        (new AuditLog())->log($user['id'], '2FA Login', 'Successful 2FA verification');
                    } catch (Exception $e) {}

                    $this->redirect('/dashboard');
                } else {
                    RateLimiter::hit('2fa', (string)$userId);
                    $error = "Invalid 2FA code. Please try again.";
                }
            }
        }

        $this->render('auth/2fa', [
            'title'  => 'Two-Factor Authentication',
            'layout' => 'auth',
            'error'  => $error ?? null
        ]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();

            $userModel = new User();

            $data = [
                'name'     => trim($_POST['name'] ?? ''),
                'email'    => trim($_POST['email'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'currency' => $_POST['currency'] ?? 'USD'
            ];

            // Validation
            if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
                $error = "Please fill in all required fields.";
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = "Please enter a valid email address.";
            } elseif (strlen($data['password']) < 8) {
                $error = "Password must be at least 8 characters.";
            } else {
                $token = $userModel->create($data);
                if ($token) {
                    $verifyLink  = BASE_URL . "/verify-email?token=" . $token;
                    $subject     = "Verify your " . SITE_NAME . " account";
                    $from_email  = "noreply@spendscribe.creativeutil.com";

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
                                <a href='" . BASE_URL . "' class='logo'>" . SITE_NAME . "</a>
                            </div>
                            <div class='content'>
                                <h2>Welcome to " . SITE_NAME . ", " . htmlspecialchars($data['name']) . "!</h2>
                                <p>Thank you for joining " . SITE_NAME . ". To start tracking your finances, please verify your email address.</p>
                                <div style='text-align: center;'>
                                    <a href='" . $verifyLink . "' class='button'>Verify My Email Address</a>
                                </div>
                                <p style='margin-top: 30px; font-size: 14px; color: #64748b;'>If the button doesn't work, copy and paste this link:</p>
                                <p style='font-size: 12px; color: #10237f; word-break: break-all;'>" . $verifyLink . "</p>
                            </div>
                            <div class='footer'>
                                <p>&copy; " . date('Y') . " " . SITE_NAME . ". All rights reserved.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ";

                    $headers  = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                    $headers .= "From: " . SITE_NAME . " <" . $from_email . ">\r\n";
                    $headers .= "Reply-To: " . $from_email . "\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion();

                    @mail($data['email'], $subject, $message, $headers, "-f " . $from_email);

                    try {
                        $user = $userModel->findByEmail($data['email']);
                        if ($user) (new AuditLog())->log($user['id'], 'Registration', 'Account created, pending verification');
                    } catch (Exception $e) {}

                    $this->render('auth/register-success', [
                        'title'  => 'Registration Successful',
                        'layout' => 'auth',
                        'email'  => $data['email']
                    ]);
                    return;
                } else {
                    $error = "Failed to create account. Email might already be registered.";
                }
            }
        }

        $this->render('auth/register', [
            'title'  => 'Create Account',
            'layout' => 'auth',
            'error'  => $error ?? null
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
            'title'   => 'Sign In',
            'layout'  => 'auth',
            'success' => $success ?? null,
            'error'   => $error ?? null
        ]);
    }

    public function adminLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();

            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (RateLimiter::isLockedOut('admin_login', $email)) {
                $mins  = ceil(RateLimiter::retryAfter('admin_login', $email) / 60);
                $error = "Too many failed attempts. Please wait {$mins} minute(s).";
                $this->render('auth/admin-login', ['title' => 'Admin Sign In', 'layout' => 'auth', 'error' => $error]);
                return;
            }

            $adminModel = new Admin();
            $admin      = $adminModel->verify($email, $password);

            if ($admin) {
                RateLimiter::clear('admin_login', $email);
                session_regenerate_id(true);
                $_SESSION['admin_id']          = $admin['id'];
                $_SESSION['admin_name']        = $admin['name'];
                $_SESSION['admin_email']       = $admin['email'];
                $_SESSION['admin_role']        = $admin['role'];
                $_SESSION['admin_profile_pic'] = $admin['profile_pic'] ?? null;
                $_SESSION['last_activity']     = time();
                $this->redirect('/admin');
            } else {
                RateLimiter::hit('admin_login', $email);
                $error = "Invalid admin credentials.";
            }
        }

        $this->render('auth/admin-login', [
            'title'   => 'Admin Sign In',
            'layout'  => 'auth',
            'noIndex' => true,
            'error'   => $error ?? null
        ]);
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();

            $email = trim($_POST['email'] ?? '');

            // Rate limit: 3 requests per 15 min per IP
            if (RateLimiter::isLockedOut('forgot_password')) {
                $mins    = ceil(RateLimiter::retryAfter('forgot_password') / 60);
                $success = "If that email is registered you will receive a reset link shortly. (Wait {$mins} min if not received.)";
                $this->render('auth/forgot-password', ['title' => 'Reset Password', 'layout' => 'auth', 'success' => $success]);
                return;
            }

            RateLimiter::hit('forgot_password');

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $userModel = new User();
                $user      = $userModel->findByEmail($email);

                if ($user && $user['is_active'] && $user['email_verified']) {
                    $token   = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
                    $userModel->setResetToken($user['id'], $token, $expires);

                    $resetLink  = BASE_URL . "/reset-password?token=" . $token;
                    $from_email = "noreply@spendscribe.creativeutil.com";
                    $subject    = "Reset your " . SITE_NAME . " password";

                    $message = "
                    <html><body>
                    <p>You requested a password reset for your " . SITE_NAME . " account.</p>
                    <p><a href='" . $resetLink . "'>Click here to reset your password</a></p>
                    <p>This link expires in 1 hour. If you did not request this, ignore this email.</p>
                    <p style='font-size:12px;color:#64748b;word-break:break-all;'>" . $resetLink . "</p>
                    </body></html>";

                    $headers  = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
                    $headers .= "From: " . SITE_NAME . " <" . $from_email . ">\r\n";
                    $headers .= "Reply-To: " . $from_email . "\r\n";

                    @mail($email, $subject, $message, $headers, "-f " . $from_email);
                }
            }

            // Always show the same message to avoid user enumeration
            $success = "If that email is registered you will receive a password reset link shortly.";
        }

        $this->render('auth/forgot-password', [
            'title'   => 'Reset Password',
            'layout'  => 'auth',
            'success' => $success ?? null,
            'error'   => $error ?? null
        ]);
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? $_POST['token'] ?? '';

        if (empty($token)) {
            $this->redirect('/forgot-password');
        }

        $userModel = new User();
        $user      = $userModel->findByResetToken($token);

        if (!$user) {
            $this->render('auth/forgot-password', [
                'title'  => 'Reset Password',
                'layout' => 'auth',
                'error'  => 'This reset link is invalid or has expired. Please request a new one.'
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrfToken();

            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 8) {
                $error = "Password must be at least 8 characters.";
            } elseif ($password !== $confirm) {
                $error = "Passwords do not match.";
            } else {
                $userModel->resetPassword($user['id'], $password);

                try {
                    (new AuditLog())->log($user['id'], 'Password Reset', 'Password reset via email token');
                } catch (Exception $e) {}

                $this->render('auth/login', [
                    'title'   => 'Sign In',
                    'layout'  => 'auth',
                    'success' => 'Your password has been reset. You can now log in.'
                ]);
                return;
            }
        }

        $this->render('auth/reset-password', [
            'title'  => 'Set New Password',
            'layout' => 'auth',
            'token'  => htmlspecialchars($token),
            'error'  => $error ?? null
        ]);
    }

    public function logout() {
        if (isset($_SESSION['user_id'])) {
            try {
                (new AuditLog())->log($_SESSION['user_id'], 'Logout', 'User logged out');
            } catch (Exception $e) {}
        }
        session_destroy();
        $this->redirect('/');
    }
}
