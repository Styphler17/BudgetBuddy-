<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | BudgetBuddy App' : 'BudgetBuddy Dashboard'; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/BudgetBuddy-/public/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: "#10237f",
                            foreground: "#ffffff",
                        },
                        secondary: {
                            DEFAULT: "#055448",
                            foreground: "#ffffff",
                        },
                        accent: {
                            DEFAULT: "#a2db21",
                            foreground: "#10237f",
                        },
                        success: {
                            DEFAULT: "#b3f8b1",
                            foreground: "#055448",
                        },
                        brand: {
                            DEFAULT: "#100fb0",
                            foreground: "#ffffff",
                        }
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(226, 232, 240, 0.5);
            border-radius: 1rem;
        }
    </style>
    <script>
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
<body class="min-h-screen bg-slate-50 flex">
    <!-- Sidebar -->
    <?php require_once APP_PATH . '/views/includes/DashboardSidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 lg:ml-64 min-h-screen flex flex-col">
        <!-- TopBar -->
        <?php require_once APP_PATH . '/views/includes/DashboardTopBar.php'; ?>

        <!-- Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
            <?php echo $content; ?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Mobile Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('dashboard-sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Desktop Collapsible Logic could go here
    </script>
</body>
</html>
