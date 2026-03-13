<?php
/**
 * Auth Layout - Standardized to match Main Layout
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | SpendScribe' : 'SpendScribe'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.png">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://spendscribe.creativeutil.com/">
    <meta property="og:title" content="SpendScribe – Simple Manual Budget & Spending Tracker">
    <meta property="og:description" content="SpendScribe is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    <meta property="og:image" content="<?php echo BASE_URL; ?>/public/og-image.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://spendscribe.creativeutil.com/">
    <meta property="twitter:title" content="SpendScribe – Simple Manual Budget & Spending Tracker">
    <meta property="twitter:description" content="SpendScribe is a free, simple manual budget tracker that helps you plan budgets, log expenses, and track your spending and savings without spreadsheets or bank connections.">
    <meta property="twitter:image" content="<?php echo BASE_URL; ?>/public/og-image.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/animations.css">
    
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
                            DEFAULT: "#1e293b",
                            foreground: "#ffffff",
                        },
                        accent: {
                            DEFAULT: "#a2db21", // Lime
                            foreground: "#10237f",
                        },
                        brand: {
                            DEFAULT: "#100fb0", // Indigo
                            foreground: "#ffffff",
                        }
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
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            background-color: #ffffff;
            transition: background-color 0.3s ease;
        }
        body.dark { background-color: #020617; color: #f1f5f9; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        h1, .text-h1 { font-family: 'Outfit', sans-serif; font-weight: 700; }
        h2, .text-h2 { font-family: 'Outfit', sans-serif; font-weight: 600; }
    </style>
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

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute("data-lucide", "eye-off");
            } else {
                input.type = "password";
                icon.setAttribute("data-lucide", "eye");
            }
            lucide.createIcons();
        }
    </script>
</head>
<body class="min-h-screen flex flex-col bg-white dark:bg-slate-950 text-gray-900 dark:text-slate-100 transition-colors duration-300">
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

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
      lucide.createIcons();
    </script>
</body>
</html>
