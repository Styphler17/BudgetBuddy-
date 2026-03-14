<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | SpendScribe App' : 'SpendScribe Dashboard'; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/public/favicon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Static Build) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/animations.css">
    
    <!-- PWA -->
    <link rel="manifest" href="<?php echo BASE_URL; ?>/public/manifest.json">
    <meta name="theme-color" content="#10237f">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo BASE_URL; ?>/public/sw.js');
            });
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        body.dark {
            background-color: #020617;
            color: #f1f5f9;
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

    <!-- Global Modal Stack -->
    <div id="global-modal-stack" class="fixed inset-0 z-[9999] pointer-events-none">
        <?php echo $modal_content ?? ''; ?>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        // Global Glowing Effect Controller
        document.addEventListener('DOMContentLoaded', () => {
            const proximity = 150;
            
            const handlePointerMove = (e) => {
                const wrappers = document.querySelectorAll('.glowing-wrapper');
                wrappers.forEach(wrapper => {
                    const container = wrapper.querySelector('.glowing-effect-container');
                    if (!container) return;
                    
                    const rect = wrapper.getBoundingClientRect();
                    const mouseX = e.clientX;
                    const mouseY = e.clientY;

                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;

                    const isActive = 
                        mouseX > rect.left - proximity &&
                        mouseX < rect.right + proximity &&
                        mouseY > rect.top - proximity &&
                        mouseY < rect.bottom + proximity;

                    if (isActive) {
                        container.style.setProperty('--active', '1');
                        const angle = Math.atan2(mouseY - centerY, mouseX - centerX) * (180 / Math.PI) + 90;
                        container.style.setProperty('--start', angle);
                    } else {
                        container.style.setProperty('--active', '0');
                    }
                });
            };

            window.addEventListener('pointermove', handlePointerMove);
        });

        // Mobile Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('dashboard-sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Desktop Collapsible Logic could go here

        // Modal Helpers
        window.openModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        };

        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        };
    </script>
</body>
</html>
