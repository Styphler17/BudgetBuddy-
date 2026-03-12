<?php
/**
 * Admin Layout
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel'; ?> - BudgetBuddy Intelligence Hub</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/BudgetBuddy-/public/BudgetBuddy.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tiny.cloud/1/ky4xtv1lrw74kgz3s89jm1m0tw6d1supmj4xpnbibfjk5qkz/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Custom Animations -->
        <link rel="stylesheet" href="/BudgetBuddy-/public/css/animations.css">
        
        <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#10237f",
                        accent: "#a2db21",
                        brand: "#100fb0",
                        secondary: "#055448",
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .font-outfit { font-family: 'Outfit', sans-serif; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
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
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">
    
    <!-- Sidebar Wrapper -->
    <div class="flex min-h-screen">
        <?php require_once APP_PATH . '/views/includes/AdminSidebar.php'; ?>

        <!-- Main Content Area -->
        <div class="flex-1 lg:ml-64 min-h-screen flex flex-col transition-all">
            <!-- Header -->
            <header class="sticky top-0 z-30 h-16 bg-white/80 backdrop-blur-md border-b border-gray-200 px-4 md:px-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button id="admin-mobile-toggle" class="lg:hidden p-2 rounded-md hover:bg-gray-100 transition-colors">
                        <i data-lucide="menu" class="h-5 w-5"></i>
                    </button>
                    <h1 class="text-lg font-bold text-gray-900 font-outfit">Admin Dashboard</h1>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-sm font-bold text-gray-900"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Administrator</span>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm ring-2 ring-primary/10">
                        <?php echo isset($_SESSION['admin_name']) ? strtoupper(substr($_SESSION['admin_name'], 0, 1)) : 'A'; ?>
                    </div>
                </div>
            </header>
            
            <main class="flex-1 p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    <?php echo $content; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            lucide.createIcons();
        });

        // Mobile toggle logic
        document.getElementById('admin-mobile-toggle')?.addEventListener('click', () => {
            const sidebar = document.getElementById('admin-sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        });
    </script>
</body>
</html>
