<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | BudgetBuddy' : 'BudgetBuddy - Smart Financial Planning'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/BudgetBuddy-/public/favicon.ico">
    
    <!-- Meta Tags -->
    <meta name="description" content="Track your spending, manage budgets, and achieve financial goals with BudgetBuddy.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="/BudgetBuddy-/public/css/animations.css">
    
    <!-- EmailJS SDK -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <script type="text/javascript">
        (function() {
            emailjs.init({
              publicKey: "_v_vSRGl_66IsEq9-",
            });
        })();
    </script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: "#10237f", // Navy
                            foreground: "#ffffff",
                        },
                        secondary: {
                            DEFAULT: "#1e293b", // Slate-800
                            foreground: "#ffffff",
                        },
                        accent: {
                            DEFAULT: "#a2db21", // Lime
                            foreground: "#10237f",
                        },
                        brand: {
                            DEFAULT: "#100fb0", // Indigo
                            foreground: "#ffffff",
                        },
                        success: "#10b981",
                        warning: "#f59e0b",
                        destructive: "#ef4444",
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    fontWeight: {
                        display: '900',
                        h1: '700',
                        h2: '600',
                        body: '400',
                    },
                    spacing: {
                        '8px': '8px',
                        '16px': '16px',
                        '24px': '24px',
                        '32px': '32px',
                    },
                    boxShadow: {
                        toast: "0px 32px 64px -16px rgba(0,0,0,0.30), 0px 16px 32px -8px rgba(0,0,0,0.30), 0px 8px 16px -4px rgba(0,0,0,0.24), 0px 4px 8px -2px rgba(0,0,0,0.24), 0px -8px 16px -1px rgba(0,0,0,0.16), 0px 2px 4px -1px rgba(0,0,0,0.24), 0px 0px 0px 1px rgba(0,0,0,1.00), inset 0px 0px 0px 1px rgba(255,255,255,0.08), inset 0px 1px 0px 0px rgba(255,255,255,0.20)"
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #ffffff;
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        body.dark {
            background-color: #020617;
            color: #f1f5f9;
        }

        /* Typography Standardization */
        h1, .text-h1 { font-family: 'Outfit', sans-serif; font-weight: 700; line-height: 1.2; color: inherit; }
        h2, .text-h2 { font-family: 'Outfit', sans-serif; font-weight: 600; line-height: 1.3; color: inherit; }
        .text-display { font-family: 'Outfit', sans-serif; font-weight: 900; line-height: 1.1; color: inherit; }
        
        /* Micro-interactions */
        .btn-press:active { transform: scale(0.95); transition: transform 0.1s; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.5);
            border-radius: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dark .glass-card {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 10px;
        }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <script>
        // Dark mode initialization - 3 State Support
        (function() {
            const savedTheme = localStorage.getItem('theme-mode') || 'system';
            const isDark = savedTheme === 'dark' || 
                (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDark) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        })();
    </script>
    <!-- Header/Navigation -->
    <?php require_once APP_PATH . '/views/includes/Header.php'; ?>

    <!-- Main Content -->
    <main class="flex-grow">
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <?php require_once APP_PATH . '/views/includes/Footer.php'; ?>

    <!-- Back to Top -->
    <?php require_once APP_PATH . '/views/includes/BackToTop.php'; ?>

    <!-- Save Changes Toast -->
    <?php require_once APP_PATH . '/views/includes/ToastSave.php'; ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();
    </script>
</body>
</html>
